@extends('layouts.adminlayout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 35px;">Add Package</h1>
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
                        <form action="{{ route('admin.packages.add') }}" method="POST" id="package-form" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="package_name" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" name="package_name" value="{{ old('package_name') }}" class="form-control" placeholder="Enter package name">
                                    @error('package_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="persons" class="form-label">Persons <span class="text-danger">*</span></label>
                                    <input type="number" name="persons" value="{{ old('persons') }}" class="form-control" placeholder="Enter number of persons">
                                    @error('persons')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="text" name="price" value="{{ old('price') }}" class="form-control" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="area_name" class="form-label">Area <span class="text-danger">*</span></label>
                                    <select name="area_name" id="area_name" class="form-control">
                                        <option value="" disabled selected>Select an Area</option>
                                        <option value="Marikina" {{ old('area_name') == 'Marikina'? 'selected' : '' }}>Marikina</option>
                                        <option value="San Mateo" {{ old('area_name') == 'San Mateo'? 'selected' : '' }}>San Mateo</option>
                                        <option value="Montalban" {{ old('area_name') == 'Montalban'? 'selected' : '' }}>Montalban</option>
                                    </select>
                                    @error('area_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div id="menu-section">
                                @foreach(old('menus', [['menu_name' => '', 'foods' => [['food' => '', 'category' => '']]]]) as $index => $menu)
                                    <div class="row mt-3 menu-group">
                                        <div class="col-md-6">
                                            <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                            <input type="text" name="menus[{{ $index }}][menu_name]" class="form-control" placeholder="Enter menu name" value="{{ old("menus.$index.menu_name", $menu['menu_name']) }}">
                                            @error("menus.$index.menu_name")
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
                                            <div id="food-list-{{ $index }}" class="food-list" data-index="{{ $loop->index }}">
                                                 @foreach($menu['foods'] as $foodIndex => $food)
                                                    <div class="row mb-2">
                                                        @if ($foodIndex === 0)
                                                            <div class="col-md-5">
                                                                <select name="menus[{{ $index }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $index }})">
                                                                    <option value="" disabled selected>Select a category</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$index.foods.$foodIndex.category") == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$index.foods.$foodIndex.category") == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$index.foods.$foodIndex.category") == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$index.foods.$foodIndex.category") == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$index.foods.$foodIndex.category") == 'Drinks' ? 'selected' : '' }}>Drinks</option>
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
                                                        @else
                                                            <div class="col-md-5">
                                                                <select name="menus[{{ $index }}][foods][{{ $foodIndex }}][category]" class="form-control category-select" onchange="updateCategoryOptions({{ $index }})">
                                                                    <option value="" disabled selected>Select a category</option>
                                                                    <option value="Main Course (Beef)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Beef)' ? 'selected' : '' }}>Main Course (Beef)</option>
                                                                    <option value="Main Course (Chicken)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Chicken)' ? 'selected' : '' }}>Main Course (Chicken)</option>
                                                                    <option value="Main Course (Pork)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Pork)' ? 'selected' : '' }}>Main Course (Pork)</option>
                                                                    <option value="Main Course (Fish)" {{ old("menus.$index.foods.$foodIndex.category") == 'Main Course (Fish)' ? 'selected' : '' }}>Main Course (Fish)</option>
                                                                    <option value="Side Dish" {{ old("menus.$index.foods.$foodIndex.category") == 'Side Dish' ? 'selected' : '' }}>Side Dish</option>
                                                                    <option value="Pasta" {{ old("menus.$index.foods.$foodIndex.category") == 'Pasta' ? 'selected' : '' }}>Pasta</option>
                                                                    <option value="Rice" {{ old("menus.$index.foods.$foodIndex.category") == 'Rice' ? 'selected' : '' }}>Rice</option>
                                                                    <option value="Dessert" {{ old("menus.$index.foods.$foodIndex.category") == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                                                    <option value="Drinks" {{ old("menus.$index.foods.$foodIndex.category") == 'Drinks' ? 'selected' : '' }}>Drinks</option>
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
                                            <button id="food-{{$index}}" class="btn btn-sm btn-success mt-2 float-start" type="button" onclick="addMoreFoods({{ $index }})">Add More Foods</button>
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
                                    <label for="services" class="form-label mt-3">Services <span class="text-danger">*</span></label>
                                    <div id="services-list">
                                        @foreach(old('services', ['']) as $index => $service)
                                            <div class="row mb-2">
                                                @if ($index === 0)
                                                    <div class="col-md-10">
                                                        <div class="input-group">
                                                            <input type="text" name="services[]" class="form-control" placeholder="Enter service" value="{{ old("services.$index", $service) }}">
                                                        </div>
                                                        @error("services.$index")
                                                            <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                @else
                                                    <div class="col-md-10">
                                                        <div class="input-group">
                                                            <input type="text" name="services[]" class="form-control" placeholder="Enter service" value="{{ old("services.$index", $service) }}">
                                                        </div>
                                                        @error("services.$index")
                                                            <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-danger remove-item" type="button">Remove</button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <button id="add-service" class="btn btn-sm btn-success mt-2" type="button">Add More Services</button>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Is this a Wedding Package? <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="package_type" id="wedding" value="Not Wedding" {{ old('package_type') == 'Not Wedding' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="wedding">
                                            Not Wedding
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="package_type" id="not_wedding" value="Wedding" {{ old('package_type') == 'Wedding' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="not_wedding">
                                            Wedding
                                        </label>
                                    </div>
                                    @error('package_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="image">Upload Package Image</label>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 float-end">Add Package</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateAllCategoryOptions();
    });

    // Add menu button
    document.getElementById('add-menu').addEventListener('click', function() {
        var menuSection = document.getElementById('menu-section');
        var index = menuSection.querySelectorAll('.menu-group').length;

        var menuGroup = `
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
                                <option value="" disabled selected>Select a category</option>
                                ${generateCategoryOptions()}
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="menus[${index}][foods][0][food]" class="form-control" placeholder="Enter food">
                        </div>
                    </div>
                </div>
                <button id="food-${index}" class="btn btn-sm btn-success mt-2 float-start" type="button" onclick="addMoreFoods(${index})">Add More Foods</button>
            </div>
            <div class="col-md-12 mt-2">
                <button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>
            </div>
        </div>
        `;

        menuSection.insertAdjacentHTML('beforeend', menuGroup);
        updateAllCategoryOptions();
    });

    // Remove food or services
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-menu')) {
            event.target.closest('.menu-group').remove();
            updateAllCategoryOptions();
        } 
        else if (event.target.classList.contains('remove-item')) {
            event.target.closest('.row').remove();
            updateAllCategoryOptions();
        }
    });

    function addMoreFoods(menuIndex) {
        var foodList = document.getElementById('food-list-' + menuIndex);
        var foodCount = foodList.querySelectorAll('.row').length;
        var addButton = document.getElementById('food-' + menuIndex);

        if (foodCount < 9) { // Maximum of 8 foods
            var foodIndex = foodCount; // Set the next index for the new food

            var foodGroup = `
            <div class="row mb-2">
                <div class="col-md-5">
                    <select name="menus[${menuIndex}][foods][${foodIndex}][category]" class="form-control category-select" onchange="updateCategoryOptions(${menuIndex})">
                        <option value="">Select a category</option>
                        ${generateCategoryOptions(getSelectedCategories(menuIndex))}
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="menus[${menuIndex}][foods][${foodIndex}][food]" class="form-control" placeholder="Enter food">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger remove-item" type="button" onclick="removeFood(this, ${menuIndex})">Remove</button>
                </div>
            </div>
            `;

            foodList.insertAdjacentHTML('beforeend', foodGroup);

            // Disable the add button if 9 items are reached
            if (foodCount + 1 >= 9) {
                addButton.disabled = true;
            }

            updateCategoryOptions(menuIndex); // Ensure options are updated for new field
        }
    }

    function removeFood(button, menuIndex) {
        // Remove the food item from the DOM
        var foodRow = button.closest('.row');
        foodRow.remove();

        // Re-count food items
        var foodList = document.getElementById('food-list-' + menuIndex);
        var foodCount = foodList.querySelectorAll('.row').length;
        var addButton = document.getElementById('food-' + menuIndex);

        // Enable the add button if foodCount is less than 9
        if (foodCount < 9) {
            addButton.disabled = false;
        }

        updateCategoryOptions(menuIndex); // Update options after removal
    }

    function getSelectedCategories(menuIndex) {
        var foodList = document.getElementById('food-list-' + menuIndex);
        var selects = foodList.querySelectorAll('.category-select');
        return Array.from(selects).map(select => select.value).filter(value => value !== '');
    }

    function updateCategoryOptions(menuIndex) {
        var selectedValues = getSelectedCategories(menuIndex);
        var foodList = document.getElementById('food-list-' + menuIndex);
        var selects = foodList.querySelectorAll('.category-select');

        selects.forEach(select => {
            var currentValue = select.value;
            var newOptions = `
                <option value="" disabled selected>Select category</option>
                ${generateCategoryOptions(selectedValues, currentValue)}
            `;
            select.innerHTML = newOptions;
            select.value = currentValue; // Preserve the selected value
        });
    }

    function generateCategoryOptions(selectedValues = [], currentValue = "") {
        const categories = [
            "Main Course (Beef)", "Main Course (Chicken)", "Main Course (Pork)",  "Main Course (Fish)", "Side Dish",
            "Pasta", "Rice", "Dessert", "Drinks"
        ];

        return categories
            .filter(category => !selectedValues.includes(category) || category === currentValue)
            .map(category => `<option value="${category}">${category}</option>`)
            .join('');
    }

    // Add service button
    document.getElementById('add-service').addEventListener('click', function() {
        var servicesList = document.getElementById('services-list');
        var serviceIndex = servicesList.querySelectorAll('input').length;

        var serviceGroup = `
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
