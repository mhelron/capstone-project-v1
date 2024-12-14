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

    public function update(Request $request, $packageId)
    {
        // Verify package is still visible before allowing customization
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        if (!$package || ($package['is_displayed'] ?? false) === false) {
            return redirect()->route('guest.home')->with('error', 'Package is no longer available.');
        }

        // Verify selected foods are from visible packages
        $visibleFoods = $this->getVisibleFoods();
        foreach ($request->foods as $category => $food) {
            if (!isset($visibleFoods[$category]) || !in_array($food, $visibleFoods[$category])) {
                return redirect()->back()->with('error', 'Some selected items are no longer available.');
            }
        }

        $menuId = Str::random(10);
        $customizedMenu = [
            'menu_name' => $request->menu_name . ' (Customized)',
            'menu_id' => $menuId,
            'foods' => [],
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
}