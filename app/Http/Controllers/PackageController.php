<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


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

            $user = Session::get('firebase_user');
            
            Log::info('Activity Log', [
                'user' => $user->email,
                'action' => 'Added new package: ' . $validatedData['package_name'] . '.'
            ]);

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
            $existingPackage = $this->database->getReference("packages/{$packageId}")->getValue();
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

            $user = Session::get('firebase_user');
            $changes = [];

            if ($existingPackage['package_name'] !== $validatedData['package_name']) {
                $changes[] = "Package name from '{$existingPackage['package_name']}' to '{$validatedData['package_name']}'";
            }
            if ($existingPackage['persons'] !== $validatedData['persons']) {
                $changes[] = "Persons from '{$existingPackage['persons']}' to '{$validatedData['persons']}'";
            }
            if ($existingPackage['price'] !== $validatedData['price']) {
                $changes[] = "Price from '{$existingPackage['price']}' to '{$validatedData['price']}'";
            }
            if ($existingPackage['area_name'] !== $validatedData['area_name']) {
                $changes[] = "Area from '{$existingPackage['area_name']}' to '{$validatedData['area_name']}'";
            }
            if ($existingPackage['package_type'] !== $validatedData['package_type']) {
                $changes[] = "Package type from '{$existingPackage['package_type']}' to '{$validatedData['package_type']}'";
            }

            // Compare menus and foods
            $existingMenus = $existingPackage['menus'] ?? [];
            foreach ($validatedData['menus'] as $index => $menu) {
                $menuId = (string)$index;
                $existingMenu = $existingMenus[$menuId] ?? null;

                if (!$existingMenu || $existingMenu['menu_name'] !== $menu['menu_name']) {
                    $changes[] = "Menu name changed for menu {$menuId}: '{$existingMenu['menu_name']}' to '{$menu['menu_name']}'";
                }

                $existingFoods = $existingMenu['foods'] ?? [];
                foreach ($menu['foods'] as $foodIndex => $food) {
                    $existingFood = $existingFoods[$foodIndex] ?? null;

                    if (!$existingFood || $existingFood['food'] !== $food['food']) {
                        $changes[] = "Food name changed for menu {$menuId}, food {$foodIndex}: '{$existingFood['food']}' to '{$food['food']}'";
                    }
                    if (!$existingFood || $existingFood['category'] !== $food['category']) {
                        $changes[] = "Food category changed for menu {$menuId}, food {$foodIndex}: '{$existingFood['category']}' to '{$food['category']}'";
                    }
                }
            }

            // Compare services
            $existingServices = $existingPackage['services'] ?? [];
            foreach ($validatedData['services'] as $index => $service) {
                $existingService = $existingServices[$index] ?? null;

                if (!$existingService || $existingService['service'] !== $service) {
                    $changes[] = "Service changed at index {$index}: '{$existingService['service']}' to '{$service}'";
                }
            }

            if (!empty($changes)) {
                $changesText = implode(', ', $changes);
                Log::info('Activity Log', [
                    'user' => $user->email,
                    'action' => "Updated package: {$changesText}."
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
           // Get the package name from the fetched data
           $packageName = $package_data['package_name'];

           // Log the activity
           $user = Session::get('firebase_user');
           Log::info('Activity Log', [
               'user' => $user->email,
               'action' => "Archived package: '{$packageName}'."
           ]);
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
            $newStatus = !$currentStatus;  // Toggle the display status
            $packageRef->update(['is_displayed' => $newStatus]);

            $user = Session::get('firebase_user');

            // Log the change based on the action
            $action = $newStatus ? 'shown' : 'hidden';

            Log::info('Activity Log', [
                'user' => $user->email,
                'action' => "Package {$package['package_name']} has been {$action}."
            ]);

            return redirect()->back()->with('status', "Package display status updated successfully! It is now {$action}.");
        } else {
            return redirect()->back()->with('status', 'Package not found.');
        }
    }



}