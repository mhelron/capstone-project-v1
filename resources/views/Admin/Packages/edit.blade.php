@extends('layouts.adminLayout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Package</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('admin.packages') }}" class="btn btn-danger">Back</a>
                </div>

                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('admin.packages.update', $packageId) }}" method="POST" id="package-form">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="package_name" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" name="package_name" value="{{ old('package_name', $package['package_name']) }}" class="form-control" placeholder="Enter package name">
                                    @error('package_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="persons" class="form-label">Persons <span class="text-danger">*</span></label>
                                    <input type="number" name="persons" value="{{ old('persons', $package['persons']) }}" class="form-control" placeholder="Enter number of persons">
                                    @error('persons')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="text" name="price" value="{{ old('price', number_format($package['price'])) }}" class="form-control" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="area_name" class="form-label">Area <span class="text-danger">*</span></label>
                                    <select name="area_name" id="area_name" class="form-control">
                                        <option value="" disabled selected>Select an Area</option>
                                        <option value="Marikina" {{ old('area_name', $package['area_name']) == 'Marikina'? 'selected' : '' }}>Marikina</option>
                                        <option value="San Mateo" {{ old('area_name', $package['area_name']) == 'San Mateo'? 'selected' : '' }}>San Mateo</option>
                                        <option value="Montalban" {{ old('area_name', $package['area_name']) == 'Montalban'? 'selected' : '' }}>Montalban</option>
                                    </select>
                                    @error('area_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div id="menu-section">
                                @foreach(old('menus', $package['menus']) as $menuIndex => $menu)
                                    <div class="row mt-3 menu-group">
                                        <div class="col-md-6">
                                            <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                            <input type="text" name="menus[{{ $menuIndex }}][menu_name]" class="form-control" placeholder="Enter menu name" value="{{ old("menus.$menuIndex.menu_name", $menu['menu_name']) }}" data-index="{{ $loop->index }}">
                                            @error("menus.$menuIndex.menu_name")
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
                                            <div id="food-list-{{ $loop->index }}" class="food-list">
                                                @foreach($menu['foods'] as $foodIndex => $food)
                                                    <div class="row mb-2">
                                                        @if ($loop->index === 0)
                                                            <div class="col-md-5">
                                                                <select name="menus[{{ $menuIndex }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $loop->parent->index }})">
                                                                    <option value="" disabled selected>Select category</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                                                                </select>
                                                                @error("menus.$menuIndex.foods.$foodIndex.category")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" name="menus[{{ $menuIndex }}][foods][{{ $foodIndex }}][food]" class="form-control" placeholder="Enter food" value="{{ old("menus.$menuIndex.foods.$foodIndex.food", $food['food']) }}">
                                                                @error("menus.$menuIndex.foods.$foodIndex.food")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        @else
                                                            <div class="col-md-5">
                                                                <select name="menus[{{ $menuIndex }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $loop->parent->index }})">
                                                                    <option value="" disabled selected>Select category</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$menuIndex.foods.$foodIndex.category", $food['category'] ?? '') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" name="menus[{{ $menuIndex }}][foods][{{ $foodIndex }}][food]" class="form-control" placeholder="Enter food" value="{{ old("menus.$menuIndex.foods.$foodIndex.food", $food['food']) }}">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button class="btn btn-danger remove-item" type="button" onclick="removeFood(this)">Remove</button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button id="food-{{ $loop->index }}" class="btn btn-sm btn-success mt-2 float-start add-more-foods" data-index="{{ $loop->index }}" type="button">Add More Foods</button>
                                        </div>

                                        @if($loop->index > 0)
                                            <div class="col-md-12 mt-2">
                                                <button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add More Menu Button -->
                            <div class="d-flex justify-content-start mb-2">
                                <button id="add-menu" class="btn btn-sm btn-success mt-2" type="button">Add More Menu</button>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="services" class="form-label mt-3">Services</label>
                                    <div id="services-list">
                                        @php
                                            $oldServices = old('services', $services);
                                            $oldServices = is_array($oldServices) ? $oldServices : [];
                                        @endphp

                                        @foreach($oldServices as $index => $service)
                                            <div class="row mb-2">
                                                <div class="col-md-10">
                                                    <input type="text" name="services[]" class="form-control" value="{{ is_array($service) ? $service['service'] : $service }}" placeholder="Enter service">
                                                    @error('services.' . $index)
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-danger remove-item" type="button">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button id="add-service" class="btn btn-sm btn-success mt-2" type="button">Add More Services</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 float-end">Edit Package</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initializeAddMoreFoods();
    });

    function initializeAddMoreFoods() {
        // Update category options for initial fields
        const initialFoodLists = document.querySelectorAll('.food-list');
        initialFoodLists.forEach((foodList, index) => {
            updateCategoryOptions(index);
        });

        document.querySelectorAll('.add-more-foods').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                addMoreFoods(index);
            });
        });
    }

    window.addMoreFoods = function(index) {
        const foodList = document.getElementById(`food-list-${index}`);
        const foodCount = foodList.children.length;

        if (foodCount < 8) {  // Ensure maximum of 8 items
            const foodRow = document.createElement('div');
            foodRow.className = 'row mb-2';
            foodRow.innerHTML = `
                <div class="col-md-5">
                    <select name="menus[${index}][foods][${foodCount}][category]" class="form-control category-select" onchange="updateCategoryOptions(${index})">
                        <option value="" disabled selected>Select category</option>
                        ${generateCategoryOptions(index)}
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="menus[${index}][foods][${foodCount}][food]" class="form-control" placeholder="Enter food">
                </div>
                <div class="col-md-2">
                    ${foodCount > 0 ? `<button class="btn btn-danger remove-item" type="button" onclick="removeFood(this)">Remove</button>` : '' }
                </div>
            `;
            foodList.appendChild(foodRow);
            updateCategoryOptions(index);  // Update options after adding
        }
    };

    // Remove food item functionality
    window.removeFood = function(button) {
        const foodRow = button.closest('.row');
        foodRow.remove();
        const index = button.closest('.food-list').id.split('-').pop();
        updateCategoryOptions(index);  // Update categories after removal
    };

    // Update category options to prevent duplicates
    function updateCategoryOptions(menuIndex) {
        var foodList = document.getElementById('food-list-' + menuIndex);
        var selects = foodList.querySelectorAll('.category-select');

        // Collect selected values for the current menu
        var selectedValues = Array.from(selects).map(select => select.value).filter(value => value !== '');

        selects.forEach(select => {
            const currentValue = select.value;
            const newOptions = generateCategoryOptions(menuIndex, selectedValues, currentValue);
            select.innerHTML = newOptions;
            select.value = currentValue; // Keep the current selection
        });
    }

    // Generate category options with the selected values excluded
    function generateCategoryOptions(menuIndex, selectedValues = [], currentValue = '') {
        const categories = [
            "Main Course (Chicken)", "Main Course (Pork)", "Main Course (Beef)", "Main Course (Fish)", 
            "Side Dish", "Pasta", "Rice", "Dessert", "Drinks"
        ];

        return categories
            .filter(category => !selectedValues.includes(category) || category === currentValue)
            .map(category => `<option value="${category}">${category}</option>`)
            .join('');
    }

    // Add menu functionality
    document.getElementById('add-menu').addEventListener('click', function() {
        const menuSection = document.getElementById('menu-section');
        const index = menuSection.querySelectorAll('.menu-group').length;

        const menuGroup = `
        <div class="row mt-3 menu-group">
            <div class="col-md-6">
                <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                <input type="text" name="menus[${index}][menu_name]" class="form-control" placeholder="Enter menu name">
            </div>
            <div class="col-md-6">
                <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
                <div id="food-list-${index}" class="food-list">
                    <div class="row mb-2">
                        <div class="col-md-5">
                            <select name="menus[${index}][foods][0][category]" class="form-control category-select" onchange="updateCategoryOptions(${index})">
                                <option value="" disabled selected>Select category</option>
                                ${generateCategoryOptions(index)}
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="menus[${index}][foods][0][food]" class="form-control" placeholder="Enter food">
                        </div>
                    </div>
                </div>
                <button class="btn btn-sm btn-success mt-2 float-start add-more-foods" data-index="${index}" type="button">Add More Foods</button>
            </div>
            <div class="col-md-12 mt-2">
                ${index > 0 ? `<button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>` : ''}
            </div>
        </div>
        `;

        menuSection.insertAdjacentHTML('beforeend', menuGroup);
        initializeAddMoreFoods();
    });

    // Event delegation for remove menu or food items
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-menu')) {
            event.target.closest('.menu-group').remove();
        }
    });

    // Add service functionality
    document.getElementById('add-service').addEventListener('click', function() {
        const servicesList = document.getElementById('services-list');
        const serviceGroup = `
        <div class="row mb-2">
            <div class="col-md-10">
                <div class="input-group">
                    <input type="text" name="services[]" class="form-control" placeholder="Enter service">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger remove-item" type="button">Remove</button>
            </div>
        </div>
        `;

        servicesList.insertAdjacentHTML('beforeend', serviceGroup);
    });
</script>


@endsection