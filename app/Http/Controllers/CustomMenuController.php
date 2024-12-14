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
        // Get the original package and menu
        $package = $this->database->getReference('packages/' . $packageId)->getValue();
        
        // Find the selected menu
        $selectedMenu = null;
        foreach ($package['menus'] as $menu) {
            if ($menu['menu_name'] === $menuName) {
                $selectedMenu = $menu;
                break;
            }
        }

        // Get all available foods from all packages for substitution
        $availableFoods = [];
        $allPackages = $this->database->getReference('packages')->getValue();
        
        foreach ($allPackages as $pkg) {
            foreach ($pkg['menus'] as $menu) {
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

        $content = $database->getReference('contents')->getValue();
        return view('guest.reservation.customize', compact('content', 'selectedMenu', 'availableFoods', 'package', 'packageId'));
    }

    public function update(Request $request, $packageId)
    {
        // Generate a unique identifier for this customized menu
        $menuId = Str::random(10);
        
        $customizedMenu = [
            'menu_name' => $request->menu_name . ' (Customized)',
            'menu_id' => $menuId,
            'foods' => []
        ];

        foreach ($request->foods as $category => $food) {
            $customizedMenu['foods'][] = [
                'category' => $category,
                'food' => $food
            ];
        }

        // Store the customized menu in session with the unique ID
        session([
            'customized_menu_' . $menuId => $customizedMenu,
            'selected_package' => $request->package_name
        ]);

        // Redirect with package, menu, and menu ID parameters
        return redirect()->route('guest.reserve', [
            'package' => $request->package_name,
            'menu' => $customizedMenu['menu_name'],
            'menu_id' => $menuId
        ])->with('status', 'Menu customized successfully');
    }
}