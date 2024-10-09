<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class PackageController extends Controller
{
    protected $database, $packages, $archived_packages;

    public function __construct(Database $database){
        $this->database = $database;
        $this->packages = 'packages';
        $this->archived_packages = 'archived_packages';
    }

    public function index(){
        $packages = $this->database->getReference('packages')->getValue();
        $packages = is_array($packages) ? $packages : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.packages.index', compact('isExpanded', 'packages'));
    }

    public function create(){
        $isExpanded = session()->get('sidebar_is_expanded', true);
        $menus = [];
        return view('admin.packages.create', compact('isExpanded', 'menus'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'package_name' => 'required|string|max:255',
            'persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'area_name' => 'required|string|max:255',
            'menus' => 'required|array',
            'menus.*.menu_name' => 'required|string|max:255',
            'menus.*.foods' => 'required|array',
            'menus.*.foods.*.food' => 'required|string|max:255',
            'menus.*.foods.*.category' => 'required|string|max:255',
            'services' => 'required|array',
            'services.*' => 'required|string|max:255',
        ], [
            'package_name.required' => 'Package name is required.',
            'persons.required' => 'Number of persons is required.',
            'price.required' => 'Price is required.',
            'area_name.required' => 'Area is required.',
            'menus.*.menu_name.required' => 'Menu name is required.',
            'menus.*.foods.*.food.required' => 'Food name is required.',
            'menus.*.foods.*.category.required' => 'Select a category.',
            'services.*.required' => 'Service is required.',
        ]);

        $validatedData['price'] = str_replace(',', '', $validatedData['price']);

        // Store the package details
        $packageId = $this->database->getReference('packages')->push([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'area_name' => $validatedData['area_name'],
        ])->getKey();

        // Store each menu and its related foods and categories
        foreach ($validatedData['menus'] as $menu) {
            $menuId = $this->database->getReference("packages/{$packageId}/menus")->push([
                'menu_name' => $menu['menu_name'],
            ])->getKey();

            foreach ($menu['foods'] as $food) {
                $this->database->getReference("packages/{$packageId}/menus/{$menuId}/foods")->push([
                    'food' => $food['food'],
                    'category' => $food['category'],
                ]);
            }
        }

        // Store services
        foreach ($validatedData['services'] as $service) {
            $this->database->getReference("packages/{$packageId}/services")->push([
                'service' => $service,
            ]);
        }

        return redirect()->route('admin.packages')->with('status', 'Package created successfully!');
    }

    public function edit($packageId)
    {
        $packageRef = $this->database->getReference("packages/{$packageId}")->getValue();

        if (!$packageRef) {
            return redirect()->route('admin.packages')->withErrors('Package not found.');
        }

        $package = $packageRef;

        $menus = isset($package['menus']) ? array_values($package['menus']) : [];
        $services = isset($package['services']) ? array_values($package['services']) : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.packages.edit', compact('package', 'menus', 'services', 'packageId', 'isExpanded'));
    }

    public function update(Request $request, $packageId)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'package_name' => 'required|string|max:255',
            'persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'area_name' => 'required|string|max:255',
            'menus' => 'required|array',
            'menus.*.menu_name' => 'required|string|max:255',
            'menus.*.foods' => 'required|array',
            'menus.*.foods.*.food' => 'required|string|max:255',
            'menus.*.foods.*.category' => 'required|string|max:255',
            'services.*' => 'required|string|max:255',
        ], [
            'package_name.required' => 'Package name is required.',
            'persons.required' => 'Number of persons is required.',
            'price.required' => 'Price is required.',
            'area_name.required' => 'Area name is required.', 
            'menus.*.menu_name.required' => 'Menu name is required.',
            'menus.*.foods.*.food.required' => 'Food name is required.',
            'menus.*.foods.*.category.required' => 'Food category is required.',
            'services.required' => 'At least one service is required.',
        ]);

        // Remove commas from the price
        $validatedData['price'] = str_replace(',', '', $validatedData['price']);

        // Update package details
        $this->database->getReference("packages/{$packageId}")->update([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'area_name' => $validatedData['area_name'],
        ]);

        // Update menus and foods
        $menusRef = $this->database->getReference("packages/{$packageId}/menus");
        // Clear existing menus before updating
        $menusRef->remove();

        foreach ($validatedData['menus'] as $menu) {
            $menuId = $menusRef->push([
                'menu_name' => $menu['menu_name'],
            ])->getKey();

            foreach ($menu['foods'] as $food) {
                $this->database->getReference("packages/{$menuId}/foods")->push([
                    'food' => $food['food'],
                    'category' => $food['category'],
                ]);
            }
        }

        // Update services
        $servicesRef = $this->database->getReference("packages/{$packageId}/services");
        // Clear existing services before updating
        $servicesRef->remove();

        foreach ($validatedData['services'] as $service) {
            $servicesRef->push([
                'service' => $service,
            ]);
        }

        return redirect()->route('admin.packages')->with('status', 'Package updated successfully!');
    }
}
