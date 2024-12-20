<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class CustomMenuController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function edit(Database $database, $packageId, $menuName)
    {
        // Get the original package and check if it's displayed
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        
        // Redirect if package is hidden or doesn't exist
        if (!$package || ($package['is_displayed'] ?? false) === false) {
            return redirect()->route('guest.home')->with('error', 'Package is not available.');
        }
        
        // Find the selected menu
        $selectedMenu = null;
        foreach ($package['menus'] as $menu) {
            if ($menu['menu_name'] === $menuName) {
                $selectedMenu = $menu;
                break;
            }
        }

        // Get all available foods from visible packages only
        $availableFoods = [];
        $allPackages = $this->database->getReference('packages')->getValue();
        
        if ($allPackages) {
            foreach ($allPackages as $pkg) {
                // Skip if package is hidden
                if (($pkg['is_displayed'] ?? false) === false) {
                    continue;
                }

                if (isset($pkg['menus'])) {
                    foreach ($pkg['menus'] as $menu) {
                        if (isset($menu['foods'])) {
                            foreach ($menu['foods'] as $food) {
                                if (!isset($availableFoods[$food['category']])) {
                                    $availableFoods[$food['category']] = [];
                                }
                                if (!in_array($food['food'], $availableFoods[$food['category']])) {
                                    $availableFoods[$food['category']][] = $food['food'];
                                }
                            }
                        }
                    }
                }
            }
        }

        // If no available foods (all packages hidden), redirect
        if (empty($availableFoods)) {
            return redirect()->route('guest.home')->with('error', 'No menus are currently available.');
        }

        $content = $database->getReference('contents')->getValue();
        return view('guest.reservation.customize', compact('content', 'selectedMenu', 'availableFoods', 'package', 'packageId'));
    }

    public function editHalal(Database $database, $packageId, $menuName)
    {
        // Get the original package
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        
        if (!$package) {
            return redirect()->route('guest.home')->with('error', 'Package not available.');
        }
        
        // Find the selected menu
        $selectedMenu = null;
        foreach ($package['menus'] as $menu) {
            if ($menu['menu_name'] === $menuName) {
                $selectedMenu = $menu;
                break;
            }
        }

        // Initialize available foods with all possible categories
        $availableFoods = [
            'Chicken' => [],
            'Beef' => [],
            'Fish' => [],
            'Side Dish' => [],
            'Pasta' => [],
            'Rice' => [],
            'Dessert' => [],
            'Drinks' => []
        ];
        
        $allPackages = $this->database->getReference('packages')->getValue();
        
        if ($allPackages) {
            foreach ($allPackages as $pkg) {
                if (($pkg['is_displayed'] ?? false) === false) {
                    continue;
                }

                if (isset($pkg['menus'])) {
                    foreach ($pkg['menus'] as $menu) {
                        if (isset($menu['foods'])) {
                            foreach ($menu['foods'] as $food) {
                                // Skip any items containing pork
                                if (stripos($food['category'], 'pork') !== false || 
                                    stripos($food['food'], 'pork') !== false) {
                                    continue;
                                }

                                // Get the base category
                                $category = null;
                                if (stripos($food['category'], 'Chicken') !== false) {
                                    $category = 'Chicken';
                                } elseif (stripos($food['category'], 'Beef') !== false) {
                                    $category = 'Beef';
                                } elseif (stripos($food['category'], 'Fish') !== false) {
                                    $category = 'Fish';
                                } elseif (stripos($food['category'], 'Side Dish') !== false) {
                                    $category = 'Side Dish';
                                } elseif (stripos($food['category'], 'Pasta') !== false) {
                                    $category = 'Pasta';
                                } elseif (stripos($food['category'], 'Rice') !== false) {
                                    $category = 'Rice';
                                } elseif (stripos($food['category'], 'Dessert') !== false) {
                                    $category = 'Dessert';
                                } elseif (stripos($food['category'], 'Drinks') !== false) {
                                    $category = 'Drinks';
                                }

                                // Add to appropriate category if categorized
                                if ($category && !in_array($food['food'], $availableFoods[$category])) {
                                    $availableFoods[$category][] = $food['food'];
                                }
                            }
                        }
                    }
                }
            }
        }

        // Convert the original menu to halal by removing pork items
        $halalMenu = ['foods' => []];
        $selectedFoods = [];
        $counter = 0;
        
        // First, add all non-pork items
        foreach ($selectedMenu['foods'] as $food) {
            if (stripos($food['category'], 'pork') === false && 
                stripos($food['food'], 'pork') === false) {
                $halalMenu['foods'][] = $food;
                $selectedFoods[] = $food['food'];
                
                // Count only main course items
                if (stripos($food['category'], 'Chicken') !== false || 
                    stripos($food['category'], 'Beef') !== false || 
                    stripos($food['category'], 'Fish') !== false) {
                    $counter++;
                    
                    // After third main course item, add the extra selection
                    if ($counter === 2) {
                        $halalMenu['foods'][] = [
                            'category' => 'Extra Main Course (Beef, Chicken, or Fish)',
                            'food' => 'Select Extra Item'
                        ];
                    }
                }
            }
        }

        // If we didn't add the extra selection yet (less than 3 main course items)
        if ($counter < 2) {
            $halalMenu['foods'][] = [
                'category' => 'Extra Main Course (Beef, Chicken, or Fish)',
                'food' => 'Select Extra Item'
            ];
        }

        $content = $database->getReference('contents')->getValue();
        return view('guest.reservation.halal', compact(
            'content',
            'halalMenu',
            'availableFoods',
            'package',
            'packageId',
            'selectedFoods'
        ));
    }

    public function update(Request $request, $packageId, $isHalal = false)
    {
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        if (!$package || ($package['is_displayed'] ?? false) === false) {
            return redirect()->route('guest.home')->with('error', 'Package is no longer available.');
        }

        // Verify selected foods based on menu type
        $visibleFoods = $isHalal ? $this->getVisibleHalalFoods() : $this->getVisibleFoods();
        foreach ($request->foods as $category => $food) {
            if (!isset($visibleFoods[$category]) || !in_array($food, $visibleFoods[$category])) {
                return redirect()->back()->with('error', 'Some selected items are no longer available.');
            }
        }

        $menuId = Str::random(10);
        $customizedMenu = [
            'menu_name' => $request->menu_name . ' (Customized' . ($isHalal ? ' - Halal' : '') . ')',
            'menu_id' => $menuId,
            'foods' => [],
            'is_halal' => $isHalal,
            'original_package_id' => $packageId
        ];

        foreach ($request->foods as $category => $food) {
            $customizedMenu['foods'][] = [
                'category' => $category,
                'food' => $food
            ];
        }

        session([
            'customized_menu_' . $menuId => $customizedMenu,
            'selected_package' => $request->package_name
        ]);

        return redirect()->route('guest.reserve', [
            'package' => $request->package_name,
            'menu' => $customizedMenu['menu_name'],
            'menu_id' => $menuId
        ])->with('status', 'Menu customized successfully');
    }

    // New method specifically for Halal menu updates
    public function updateHalal(Request $request, $packageId)
    {
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        if (!$package) {
            return redirect()->route('guest.home')->with('error', 'Package is no longer available.');
        }

        $menuId = Str::random(10);
        $customizedMenu = [
            'menu_name' => $request->menu_name . ' (Halal)',
            'menu_id' => $menuId,
            'foods' => [],
            'is_halal' => true,
            'original_package_id' => $packageId
        ];

        foreach ($request->foods as $category => $food) {
            $customizedMenu['foods'][] = [
                'category' => $category,
                'food' => $food
            ];
        }

        session([
            'customized_menu_' . $menuId => $customizedMenu,
            'selected_package' => $request->package_name
        ]);

        return redirect()->route('guest.reserve', [
            'package' => $request->package_name,
            'menu' => $customizedMenu['menu_name'],
            'menu_id' => $menuId
        ])->with('status', 'Halal menu customized successfully');
    }

    private function getVisibleFoods()
    {
        $visibleFoods = [];
        $allPackages = $this->database->getReference('packages')->getValue();
        
        if ($allPackages) {
            foreach ($allPackages as $pkg) {
                if (($pkg['is_displayed'] ?? false) === false) {
                    continue;
                }

                if (isset($pkg['menus'])) {
                    foreach ($pkg['menus'] as $menu) {
                        if (isset($menu['foods'])) {
                            foreach ($menu['foods'] as $food) {
                                if (!isset($visibleFoods[$food['category']])) {
                                    $visibleFoods[$food['category']] = [];
                                }
                                if (!in_array($food['food'], $visibleFoods[$food['category']])) {
                                    $visibleFoods[$food['category']][] = $food['food'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $visibleFoods;
    }

    private function getVisibleHalalFoods()
    {
        $visibleFoods = [];
        $allPackages = $this->database->getReference('packages')->getValue();
        
        if ($allPackages) {
            foreach ($allPackages as $pkg) {
                if (($pkg['is_displayed'] ?? false) === false) {
                    continue;
                }

                if (isset($pkg['halal_menus'])) {
                    foreach ($pkg['halal_menus'] as $menu) {
                        if (isset($menu['foods'])) {
                            foreach ($menu['foods'] as $food) {
                                if (!isset($visibleFoods[$food['category']])) {
                                    $visibleFoods[$food['category']] = [];
                                }
                                if (!in_array($food['food'], $visibleFoods[$food['category']])) {
                                    $visibleFoods[$food['category']][] = $food['food'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $visibleFoods;
    }
}