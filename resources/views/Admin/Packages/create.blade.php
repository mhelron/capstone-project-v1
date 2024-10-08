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
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" name="price" value="{{ old('price') }}" class="form-control" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="area_name" class="form-label">Area</label>
                                    <input type="text" name="area_name" value="{{ old('area_name') }}" class="form-control" placeholder="Enter area">
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
                                            <div id="food-list-{{ $index }}" class="food-list">
                                                @foreach($menu['foods'] as $foodIndex => $food)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <input type="text" name="menus[{{ $index }}][foods][{{ $foodIndex }}][food]" class="form-control" placeholder="Enter food" value="{{ old("menus.$index.foods.$foodIndex.food", $food['food']) }}">
                                                            @error("menus.$index.foods.$foodIndex.food")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="menus[{{ $index }}][foods][{{ $foodIndex }}][category]" class="form-control" placeholder="Enter category" value="{{ old("menus.$index.foods.$foodIndex.category", $food['category']) }}">
                                                            @error("menus.$index.foods.$foodIndex.category")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="btn btn-sm btn-success mt-2 float-end" type="button" onclick="addMoreFoods({{ $index }})">Add More Foods</button>
                                        </div>
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
                                        @foreach(old('services', ['']) as $index => $service)
                                            <div class="row mb-2">
                                                @if ($index === 0)
                                                    <div class="col-md-12">
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
            <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
            <input type="text" name="menus[${index}][menu_name]" class="form-control" placeholder="Enter menu name">
        </div>
        <div class="col-md-6">
            <label for="foods" class="form-label">Foods & Categories <span class="text-danger">*</span></label>
            <div id="food-list-${index}" class="food-list">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <input type="text" name="menus[${index}][foods][0][food]" class="form-control" placeholder="Enter food">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="menus[${index}][foods][0][category]" class="form-control" placeholder="Enter category">
                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-success mt-2 float-end" type="button" onclick="addMoreFoods(${index})">Add More Foods</button>
        </div>
        <div class="col-md-12 mt-2">
            <button class="btn btn-danger btn-sm remove-menu" type="button">Remove Menu</button>
        </div>
    </div>
    `;

    menuSection.insertAdjacentHTML('beforeend', menuGroup);
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-menu')) {
        event.target.closest('.menu-group').remove();
    }
});

function addMoreFoods(menuIndex) {
    var foodList = document.getElementById('food-list-' + menuIndex);
    var foodIndex = foodList.querySelectorAll('.row').length;

    var foodGroup = `
    <div class="row mb-2">
        <div class="col-md-6">
            <input type="text" name="menus[${menuIndex}][foods][${foodIndex}][food]" class="form-control" placeholder="Enter food">
        </div>
        <div class="col-md-6">
            <input type="text" name="menus[${menuIndex}][foods][${foodIndex}][category]" class="form-control" placeholder="Enter category">
        </div>
    </div>
    `;

    foodList.insertAdjacentHTML('beforeend', foodGroup);
}

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

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-item')) {
        event.target.closest('.row').remove();
    }
});
</script>

@endsection
