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
        return view('admin.packages.create', compact('isExpanded'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'package_name' => 'required|string|max:255',
            'persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'menu_name' => 'required|string|max:255',
            'services' => 'required|array',
            'services.*' => 'required|string|max:255',
            'foods' => 'required|array',
            'foods.*.food' => 'required|string|max:255', // for each food
            'foods.*.category' => 'required|string|max:255', // for each food category
        ], [
            'package_name.required' => 'Package name is required.',
            'persons.required' => 'Number of persons is required.',
            'price.required' => 'Price is required.',
            'menu_name.required' => 'Menu name is required.',
            'services.required' => 'At least one service is required.',
            'services.*' => 'Service is required.',
            'foods.required' => 'At least one food is required.',
            'foods.*.food.required' => 'Food name is required.',
            'foods.*.category.required' => 'Food category is required.',
        ]);

        $validatedData['price'] = str_replace(',', '', $validatedData['price']);

        $packageId = $this->database->getReference('packages')->push([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'menu_name' => $validatedData['menu_name'],
        ])->getKey();

        foreach ($validatedData['services'] as $service) {
            $this->database->getReference("packages/{$packageId}/services")->push([
                'service' => $service,
            ]);
        }

        foreach ($validatedData['foods'] as $food) {
            $this->database->getReference("packages/{$packageId}/foods")->push([
                'food' => $food['food'],
                'category' => $food['category'],
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

        $services = isset($package['services']) ? array_values($package['services']) : [];
        $foods = isset($package['foods']) ? array_values($package['foods']) : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.packages.edit', compact('package', 'services', 'foods', 'packageId', 'isExpanded'));
    }

    public function update(Request $request, $packageId)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'package_name' => 'required|string|max:255',
            'persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'menu_name' => 'required|string|max:255',
            'services' => 'required|array',
            'services.*' => 'required|string|max:255',
            'foods' => 'required|array',
            'foods.*.food' => 'required|string|max:255',
            'foods.*.category' => 'required|string|max:255',
        ], [
            'package_name.required' => 'Package name is required.',
            'persons.required' => 'Number of persons is required.',
            'price.required' => 'Price is required.',
            'menu_name.required' => 'Menu name is required.',
            'services.required' => 'At least one service is required.',
            'services.*' => 'Service is required.',
            'foods.required' => 'At least one food is required.',
            'foods.*.food.required' => 'Food name is required.',
            'foods.*.category.required' => 'Food category is required.',
        ]);

        // Remove commas from the price
        $validatedData['price'] = str_replace(',', '', $validatedData['price']);

        // Update the package details
        $this->database->getReference("packages/{$packageId}")->update([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'menu_name' => $validatedData['menu_name'],
        ]);

        // Update services
        $servicesRef = $this->database->getReference("packages/{$packageId}/services");
        // Clear existing services before updating
        $servicesRef->remove();

        foreach ($validatedData['services'] as $service) {
            $servicesRef->push([
                'service' => $service,
            ]);
        }

        // Update foods
        $foodsRef = $this->database->getReference("packages/{$packageId}/foods");
        // Clear existing foods before updating
        $foodsRef->remove();

        foreach ($validatedData['foods'] as $food) {
            $foodsRef->push([
                'food' => $food['food'],
                'category' => $food['category'],
            ]);
        }

        return redirect()->route('admin.packages')->with('status', 'Package updated successfully!');
    }

}
