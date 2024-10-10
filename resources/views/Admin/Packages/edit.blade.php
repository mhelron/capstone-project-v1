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
                                @foreach(old('menus', $package['menus']) as $index => $menu)
                                    <div class="row mt-3 menu-group">
                                        <div class="col-md-6">
                                            <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                            <input type="text" name="menus[{{ $index }}][menu_name]" class="form-control" placeholder="Enter menu name" value="{{ old("menus.$index.menu_name", $menu['menu_name']) }}" }}">
                                            @error("menus.$index.menu_name")
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
                                            <div id="food-list-{{ $index }}" class="food-list">
                                                @foreach($menu['foods'] as $foodIndex => $food)
                                                    <div class="row mb-2">
                                                        @if ($foodIndex === 0)
                                                            <div class="col-md-6">
                                                                <select name="menus[{{ $index }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $index }})">
                                                                    <option value="" disabled selected>Select category</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                                                                </select>
                                                                @error("menus.$index.foods.$foodIndex.category")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" name="menus[{{ $index }}][foods][{{ $foodIndex }}][food]" class="form-control" placeholder="Enter food" value="{{ old("menus.$index.foods.$foodIndex.food", $food['food']) }}">
                                                                @error("menus.$index.foods.$foodIndex.food")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        @else
                                                            <div class="col-md-5">
                                                                <select name="menus[{{ $index }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $index }})">
                                                                    <option value="" disabled selected>Select category</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$index.foods.$foodIndex.category", $food['category'] ?? '') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                                                                </select>
                                                                @error("menus.$index.foods.$foodIndex.category")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" name="menus[{{ $index }}][foods][{{ $foodIndex }}][food]" class="form-control" placeholder="Enter food" value="{{ old("menus.$index.foods.$foodIndex.food", $food['food']) }}">
                                                                @error("menus.$index.foods.$foodIndex.food")
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button class="btn btn-danger remove-item" type="button">Remove</button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button id="food-{{$index}}" class="btn btn-sm btn-success mt-2 float-end" type="button" onclick="addMoreFoods({{ $index }})">Add More Foods</button>
                                        </div>

                                        @if($index > 0)
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
        updateAllCategoryOptions(); // Ensure that all fields are updated when the page loads

        // Initialize existing food inputs with category options
        document.querySelectorAll('.food-list').forEach(foodList => {
            const menuIndex = foodList.id.split('-').pop(); // Extract menu index from the food list ID
            updateCategoryOptions(menuIndex);
        });
    });

    // Function to add more food inputs
    window.addMoreFoods = function(index) {
        const foodList = document.getElementById(`food-list-${index}`);
        const foodCount = foodList.children.length;

        if (foodCount < 9) {
            const foodRow = document.createElement('div');
            foodRow.className = 'row mb-2';
            foodRow.innerHTML = `
                <div class="col-md-5">
                    <select name="menus[${index}][foods][${foodCount}][category]" class="form-control category-select" onchange="updateCategoryOptions(${index})">
                        <option value="" disabled selected>Select category</option>
                        ${generateCategoryOptions()}
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="menus[${index}][foods][${foodCount}][food]" class="form-control" placeholder="Enter food">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger remove-item" type="button" onclick="removeFood(this)">Remove</button>
                </div>
            `;
            foodList.appendChild(foodRow);
            updateCategoryOptions(index); // Update category options after adding new food
        }
    }

    // Function to remove food inputs
    window.removeFood = function(button) {
        const foodRow = button.closest('.row');
        foodRow.remove();
    }

    // Function to update category options
    window.updateCategoryOptions = function(index) {
        const selectedCategories = Array.from(document.querySelectorAll(`#food-list-${index} .category-select`))
            .map(select => select.value);

        document.querySelectorAll(`#food-list-${index} .category-select`).forEach(select => {
            Array.from(select.options).forEach(option => {
                option.disabled = selectedCategories.includes(option.value) && option.value !== select.value;
            });
        });
    }

    // Add menu dynamically
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
                        <div class="col-md-6">
                            <select name="menus[${index}][foods][0][category]" class="form-control category-select" onchange="updateCategoryOptions(${index})">
                                <option value="" disabled selected>Select category</option>
                                ${generateCategoryOptions()}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="menus[${index}][foods][0][food]" class="form-control" placeholder="Enter food">
                        </div>
                    </div>
                </div>
                <button id="food-${index}" class="btn btn-sm btn-success mt-2 float-end" type="button" onclick="addMoreFoods(${index})">Add More Foods</button>
            </div>
            <div class="col-md-12 mt-2">
                ${index > 0 ? `<button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>` : ''}
            </div>
        </div>
        `;

        menuSection.insertAdjacentHTML('beforeend', menuGroup);
        updateAllCategoryOptions(); // Update after adding a new menu
    });

    // Remove dynamically added menus or foods/services using event delegation
    document.addEventListener('click', function(event) {
        // Check if the clicked element is a remove button for menu
        if (event.target.classList.contains('remove-menu')) {
            event.target.closest('.menu-group').remove();
            updateAllCategoryOptions(); // Update options after removing a menu
        } 
        // Check if the clicked element is a remove button for food or service
        else if (event.target.classList.contains('remove-item')) {
            event.target.closest('.row').remove();
            updateAllCategoryOptions(); // Update options after removing an item
        }
    });

    // Function to generate category options
    function generateCategoryOptions(selectedValues = [], currentValue = "") {
        const categories = [
            "Main Course (Chicken)", "Main Course (Pork)", "Main Course (Beef)", "Main Course (Fish)", 
            "Side Dish", "Pasta", "Rice", "Dessert", "Drinks"
        ];

        return categories
            .filter(category => !selectedValues.includes(category) || category === currentValue)
            .map(category => `<option value="${category}">${category}</option>`)
            .join('');
    }

    // Add service dynamically
    document.getElementById('add-service').addEventListener('click', function() {
        const servicesList = document.getElementById('services-list');
        const serviceIndex = servicesList.querySelectorAll('input').length;

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