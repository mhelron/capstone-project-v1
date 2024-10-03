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
                                    <label for="package_name" class="form-label">Package Name</label>
                                    <input type="text" name="package_name" value="{{ old('package_name', $package['package_name']) }}" class="form-control @error('package_name') is-invalid @enderror" placeholder="Enter package name">
                                    @error('package_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="persons" class="form-label">Persons</label>
                                    <input type="number" name="persons" value="{{ old('persons', $package['persons']) }}" class="form-control @error('persons') is-invalid @enderror" placeholder="Enter number of persons" >
                                    @error('persons')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" name="price" id="price" value="{{ old('price', number_format($package['price'])) }}" class="form-control @error('price') is-invalid @enderror" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="menu_name" class="form-label">Menu Name</label>
                                    <input type="text" name="menu_name" value="{{ old('menu_name', $package['menu_name']) }}" class="form-control @error('menu_name') is-invalid @enderror" placeholder="Enter menu name">
                                    @error('menu_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="services" class="form-label">Services</label>
                                    <div id="services-list">
                                        @php
                                            $oldServices = old('services', $services);
                                            $oldServices = is_array($oldServices) ? $oldServices : [];
                                        @endphp

                                        @foreach($oldServices as $index => $service)
                                            <div class="row mb-2">
                                                <div class="col-md-10">
                                                    <input type="text" name="services[]" class="form-control @error('services.' . $index) is-invalid @enderror" value="{{ is_array($service) ? $service['service'] : $service }}" placeholder="Enter service">
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
                                    <button id="add-service" class="btn btn-primary mt-2" type="button">Add More Services</button>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="foods" class="form-label">Foods & Categories</label>
                                    <div id="food-list">
                                        @foreach(old('foods', $foods) as $index => $food)
                                            <div class="row mb-2">
                                                <div class="col-md-5">
                                                    <input type="text" name="foods[{{ $index }}][food]" class="form-control @error('foods.' . $index . '.food') is-invalid @enderror" value="{{ is_array($food) ? $food['food'] : '' }}" placeholder="Enter food item">
                                                    @error('foods.' . $index . '.food')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="foods[{{ $index }}][category]" class="form-control @error('foods.' . $index . '.category') is-invalid @enderror" value="{{ is_array($food) ? $food['category'] : '' }}" placeholder="Enter category">
                                                    @error('foods.' . $index . '.category')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-danger remove-item" type="button">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button id="add-food" class="btn btn-primary mt-2" type="button">Add More Foods</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success">Update Package</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to add a new service
    document.getElementById('add-service').addEventListener('click', function () {
        const servicesList = document.getElementById('services-list');
        const serviceCount = servicesList.children.length; // Get the current count of services
        const newInputGroup = document.createElement('div');
        newInputGroup.classList.add('row', 'mb-2'); // Create a new row

        // Create the new service input group
        newInputGroup.innerHTML = `
            <div class="col-md-10">
                <input type="text" name="services[]" class="form-control" placeholder="Enter service">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-danger remove-item" type="button">Remove</button>
            </div>
        `;
        servicesList.appendChild(newInputGroup);
    });

    // Function to add a new food item
    document.getElementById('add-food').addEventListener('click', function () {
        const foodList = document.getElementById('food-list');
        const foodCount = foodList.children.length; // Get the current count of foods
        const newInputGroup = document.createElement('div');
        newInputGroup.classList.add('row', 'mb-2'); // Create a new row
        
        // Create the new food input group
        newInputGroup.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="foods[${foodCount}][food]" class="form-control" placeholder="Enter food item">
            </div>
            <div class="col-md-5">
                <input type="text" name="foods[${foodCount}][category]" class="form-control" placeholder="Enter category">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-danger remove-item" type="button">Remove</button>
            </div>
        `;
        foodList.appendChild(newInputGroup);
    });

    // Event delegation for removing items
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.row').remove(); // Remove the closest row for both services and foods
        }
    });

    // Price formatting code (kept from your original)
    const priceInput = document.querySelector('input[name="price"]');

    // Function to format number with commas
    function formatWithCommas(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Format price input on page load
    if (priceInput.value) {
        let value = priceInput.value.replace(/,/g, ''); // Remove any existing commas
        priceInput.value = formatWithCommas(value); // Add commas
    }

    // Listen to price input event and format value dynamically
    priceInput.addEventListener('input', function () {
        let value = this.value.replace(/,/g, ''); // Remove existing commas
        if (!isNaN(value)) {
            this.value = formatWithCommas(value); // Format the number with commas
        }
    });

    // Listen to form submit event to strip commas before submitting
    const packageForm = document.getElementById('package-form');
    packageForm.addEventListener('submit', function () {
        priceInput.value = priceInput.value.replace(/,/g, ''); // Remove commas on submit
    });
});
</script>
@endsection
