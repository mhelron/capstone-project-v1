@extends('layouts.adminLayout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Package</h1>
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
                        <form action="{{ route('admin.packages.add') }}" method="POST" id="package-form">
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
                                    <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                                    <input type="text" name="menu_name" class="form-control" placeholder="Enter menu name">
                                    @error('menu_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
                                    <div id="food-list">
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <input type="text" name="foods[0][food]" class="form-control" placeholder="Enter food">
                                                @error("foods.0.food")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="foods[0][category]" class="form-control" placeholder="Enter category">
                                                @error("foods.0.category")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button id="add-more-food" class="btn btn-sm btn-success mt-2" type="button">Add More Foods</button>
                                </div>
                            </div>

                            <div id="menu-section"></div>

                            <button id="add-menu" class="btn btn-sm btn-success mt-2" type="button">Add Another Menu</button>
                            <button type="submit" class="btn btn-primary mt-3 float-end">Add Package</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

document.getElementById('add-menu').addEventListener('click', function() {
    var menuSection = document.getElementById('menu-section');
    var index = menuSection.querySelectorAll('.menu-group').length;

    var menuGroup = `
        <div class="row mt-3 menu-group">
            <div class="col-md-6">
                <label for="menu_name" class="form-label">Menu Name</label>
                <input type="text" name="menu_name[]" class="form-control" placeholder="Enter menu name">
            </div>
            <div class="col-md-6">
                <div id="food-list-${index}" class="food-list">
                    <label for="foods" class="form-label">Foods & Categories</label>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" name="foods[${index}][0][food]" class="form-control" placeholder="Enter food">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="foods[${index}][0][category]" class="form-control" placeholder="Enter category">
                        </div>
                    </div>
                    <button class="btn btn-sm btn-success mt-2" type="button" onclick="addMoreFoods(${index})">Add More Foods</button>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>
            </div>
        </div>`;
    menuSection.insertAdjacentHTML('beforeend', menuGroup);
    attachRemoveMenuEvent(); // Attach event to new remove buttons
});

document.getElementById('add-more-food').addEventListener('click', function () {
    const foodList = document.getElementById('food-list');
    const foodCount = foodList.children.length; // Count the current number of foods
    const newInputGroup = document.createElement('div');
    newInputGroup.classList.add('row', 'mb-2'); // Create a new input field
    newInputGroup.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="foods[0][${foodCount}][food]" class="form-control" placeholder="Enter food item">
        </div>
        <div class="col-md-5">
            <input type="text" name="foods[0][${foodCount}][category]" class="form-control" placeholder="Enter category">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-danger remove-item" type="button">Remove</button>
        </div>`;
    foodList.appendChild(newInputGroup);
});

// Event delegation for removing food input fields
document.getElementById('food-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.row').remove(); // Remove the closest row
    }
});

// Attach event to remove menu groups
function attachRemoveMenuEvent() {
    document.querySelectorAll('.remove-menu').forEach(button => {
        button.onclick = function() {
            var menuGroup = this.closest('.menu-group');
            menuGroup.remove();
        };
    });
}

function addMoreFoods(menuIndex) {
    const foodList = document.getElementById(`food-list-${menuIndex}`);
    const foodCount = foodList.children.length; // Count the current number of foods
    const newInputGroup = document.createElement('div');
    newInputGroup.classList.add('row', 'mb-2');
    newInputGroup.innerHTML = `
         <div class="col-md-5">
            <input type="text" name="foods[${menuIndex}][${foodCount}][food]" class="form-control" placeholder="Enter food item">
        </div>
        <div class="col-md-5">
            <input type="text" name="foods[${menuIndex}][${foodCount}][category]" class="form-control" placeholder="Enter category">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-danger remove-item" type="button">Remove</button>
        </div>`;

    foodList.appendChild(newInputGroup);

    // Insert the "Add More Foods" button after the new input group
    const addMoreButton = document.createElement('button');
    addMoreButton.classList.add('btn', 'btn-sm', 'btn-success', 'mt-2');
    addMoreButton.type = 'button';
    addMoreButton.textContent = 'Add More Foods';
    addMoreButton.onclick = function() {
        addMoreFoods(menuIndex); // Reuse the function to add more foods
    };

    // Check if the button already exists and remove it before appending a new one
    const existingButton = foodList.querySelector('.btn-success');
    if (existingButton) {
        existingButton.remove();
    }
    foodList.appendChild(addMoreButton);
}


// Event delegation for removing food input fields within the new menu groups
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.row').remove(); // Remove the closest row
    }
});

</script>

@endsection
