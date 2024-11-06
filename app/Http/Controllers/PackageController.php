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
        'services' => 'required|array',
        'services.*' => 'required|string|max:255',
        'package_type' => 'required|in:Not Wedding,Wedding',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:40960',
    ], [
        'package_name.required' => 'Package name is required.',
        'persons.required' => 'Number of persons is required.',
        'price.required' => 'Price is required.',
        'area_name.required' => 'Please select an area.',
        'menus.*.menu_name.required' => 'Menu name is required.',
        'menus.*.foods.*.food.required' => 'Food name is required.',
        'menus.*.foods.*.category.required' => 'Please select a category.',
        'services.*.required' => 'Service is required.',
        'package_type.required' => 'Please select if this package is for a wedding or not.',
        'package_type.in' => 'Invalid selection. Please choose "Wedding" or "Not Wedding".',
    ]);

    // Remove commas from price
    $validatedData['price'] = str_replace(',', '', $validatedData['price']);

    if ($request->hasFile('image')) {
        // Store the image in the correct public directory
        $imagePath = $request->file('image')->store('packages/images', 'public');
        $imageUrl = str_replace('public/', 'storage/', $imagePath); // Create relative path for the image
    } else {
        $imageUrl = null; // No image uploaded
    }

    try {
        // Store the package details
        $packageId = $this->database->getReference('packages')->push([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'area_name' => $validatedData['area_name'],
            'package_type' => $validatedData['package_type'],
            'is_displayed' => true,
            'image_url' => $imageUrl,
        ])->getKey();

        // Store each menu and its related foods and categories
        foreach ($validatedData['menus'] as $menuIndex => $menu) {
            $menuRef = $this->database->getReference("packages/{$packageId}/menus/{$menuIndex}");
            $menuRef->set([
                'menu_name' => $menu['menu_name'],
            ]);

            foreach ($menu['foods'] as $foodIndex => $food) {
                $this->database->getReference("packages/{$packageId}/menus/{$menuIndex}/foods/{$foodIndex}")->set([
                    'food' => $food['food'],
                    'category' => $food['category'],
                ]);
            }
        }

        // Store services
        foreach ($validatedData['services'] as $serviceIndex => $service) {
            $this->database->getReference("packages/{$packageId}/services/{$serviceIndex}")->set([
                'service' => $service,
            ]);
        }

        return redirect()->route('admin.packages')->with('status', 'Package created successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
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
        'package_type' => 'required|in:Not Wedding,Wedding',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:40960',
    ], [
        'package_name.required' => 'Package name is required.',
        'persons.required' => 'Number of persons is required.',
        'price.required' => 'Price is required.',
        'area_name.required' => 'Please select an area.',
        'menus.*.menu_name.required' => 'Menu name is required.',
        'menus.*.foods.*.food.required' => 'Food name is required.',
        'menus.*.foods.*.category.required' => 'Please select a category.',
        'services.*.required' => 'Service is required.',
        'package_type.required' => 'Please select if this package is for a wedding or not.',
        'package_type.in' => 'Invalid selection. Please choose "Wedding" or "Not Wedding".',
    ]);

    // Remove commas from the price
    $validatedData['price'] = str_replace(',', '', $validatedData['price']);

    if ($request->hasFile('image')) {
        // Store the image in the correct public directory
        $imagePath = $request->file('image')->store('packages/images', 'public');
        $imageUrl = str_replace('public/', 'storage/', $imagePath); // Create relative path for the image
    } else {
        $imageUrl = null; // No image uploaded
    }

    try {
        // Update package details
        $this->database->getReference("packages/{$packageId}")->update([
            'package_name' => $validatedData['package_name'],
            'persons' => $validatedData['persons'],
            'price' => $validatedData['price'],
            'area_name' => $validatedData['area_name'],
            'package_type' => $validatedData['package_type'],
            'image_url' => $imageUrl,
        ]);

        // Clear and update menus
        $menusRef = $this->database->getReference("packages/{$packageId}/menus");
        $menusRef->remove();

        foreach ($validatedData['menus'] as $index => $menu) {
            $menuId = (string) $index;
            $menusRef->getChild($menuId)->set([
                'menu_name' => $menu['menu_name'],
            ]);

            foreach ($menu['foods'] as $foodIndex => $food) {
                $this->database->getReference("packages/{$packageId}/menus/{$menuId}/foods/{$foodIndex}")->set([
                    'food' => $food['food'],
                    'category' => $food['category'],
                ]);
            }
        }

        // Clear and update services
        $servicesRef = $this->database->getReference("packages/{$packageId}/services");
        $servicesRef->remove();

        foreach ($validatedData['services'] as $serviceIndex => $service) {
            $servicesRef->getChild($serviceIndex)->set([
                'service' => $service,
            ]);
        }

        return redirect()->route('admin.packages')->with('status', 'Package updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'An error occurred during update: ' . $e->getMessage()]);
    }
}


    public function destroy($id)
    {
        $key = $id;

        $package_data = $this->database->getReference($this->packages.'/'.$key)->getValue();

        $archive_data = $this->database->getReference($this->archived_packages.'/'.$key)->set($package_data);

        $del_data = $this->database->getReference($this->packages.'/'.$key)->remove();

        if ($del_data && $archive_data) {
            return redirect('admin/packages')->with('status', 'Package Archived Successfully');
        } else {
            return redirect('admin/packages')->with('status', 'Package Not Archived');
        }
    }

    public function toggleDisplay($packageId)
{
    $packageRef = $this->database->getReference("packages/{$packageId}");
    $package = $packageRef->getValue();

    if ($package) {
        $currentStatus = $package['is_displayed'] ?? false;
        // Toggle the display status
        $packageRef->update(['is_displayed' => !$currentStatus]);

        return redirect()->back()->with('status', 'Package display status updated successfully!');
    } else {
        return redirect()->back()->with('status', 'Package not found.');
    }
}
}