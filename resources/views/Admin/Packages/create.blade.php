@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Package</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('admin.packages') }}" class="btn btn-danger">Back</a>
                </div>

                <!-- Add Package Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('admin.packages.add') }}" method="POST" id="package-form">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="package_name" class="form-label">Package Name</label>
                                    <input type="text" name="package_name" value="{{ old('package_name') }}" class="form-control @error('package_name') is-invalid @enderror" placeholder="Enter package name">
                                    @error('package_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="persons" class="form-label">Persons</label>
                                    <input type="number" name="persons" value="{{ old('persons') }}" class="form-control @error('persons') is-invalid @enderror" placeholder="Enter number of persons" >
                                    @error('persons')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="menu_name" class="form-label">Menu Name</label>
                                    <input type="text" name="menu_name" value="{{ old('menu_name') }}" class="form-control @error('menu_name') is-invalid @enderror" placeholder="Enter menu name">
                                    @error('menu_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="services" class="form-label">Services</label>
                                        <div id="services-list">
                                            @foreach(old('services', ['']) as $index => $service)
                                                <div class="row mb-2">
                                                    @if ($index === 0)
                                                        <div class="col-md-12"> <!-- Full width for the first service input -->
                                                            <div class="input-group">
                                                                <input type="text" name="services[]" class="form-control @error("services.$index") is-invalid @enderror" placeholder="Enter service" value="{{ old("services.$index", $service) }}">
                                                            </div>
                                                            @error("services.$index")
                                                                <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @else
                                                        <div class="col-md-10"> <!-- Input field column for subsequent services -->
                                                            <div class="input-group">
                                                                <input type="text" name="services[]" class="form-control @error("services.$index") is-invalid @enderror" placeholder="Enter service" value="{{ old("services.$index", $service) }}">
                                                            </div>
                                                            @error("services.$index")
                                                                <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2"> <!-- Button column -->
                                                            <button class="btn btn-danger remove-item" type="button">Remove</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    <button id="add-service" class="btn btn-primary mt-2" type="button">Add More Services</button>
                                </div>

                                <div class="col-md-6">
                                    <label for="foods" class="form-label">Foods & Categories</label>
                                        <div id="food-list">
                                            @foreach(old('foods', [['food' => '', 'category' => '']]) as $index => $food)
                                                <div class="row mb-2">
                                                    @if ($index === 0)
                                                        <div class="col-md-5">
                                                            <input type="text" name="foods[{{ $index }}][food]" class="form-control @error("foods.$index.food") is-invalid @enderror" placeholder="Enter food" value="{{ old("foods.$index.food", $food['food']) }}">
                                                            @error("foods.$index.food")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-7">
                                                            <input type="text" name="foods[{{ $index }}][category]" class="form-control @error("foods.$index.category") is-invalid @enderror" placeholder="Enter category" value="{{ old("foods.$index.category", $food['category']) }}">
                                                            @error("foods.$index.category")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    @else
                                                        <div class="col-md-5">
                                                            <input type="text" name="foods[{{ $index }}][food]" class="form-control @error("foods.$index.food") is-invalid @enderror" placeholder="Enter food" value="{{ old("foods.$index.food", $food['food']) }}">
                                                            @error("foods.$index.food")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="foods[{{ $index }}][category]" class="form-control @error("foods.$index.category") is-invalid @enderror" placeholder="Enter category" value="{{ old("foods.$index.category", $food['category']) }}">
                                                            @error("foods.$index.category")
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
                                    <button id="add-more-food" class="btn btn-primary mt-2" type="button">Add More Foods</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-service').addEventListener('click', function () {
            const servicesList = document.getElementById('services-list');
            const serviceCount = servicesList.children.length; // Get the current count of services
            const newInputGroup = document.createElement('div');
            newInputGroup.classList.add('row', 'mb-2'); // Create a new row

            if (serviceCount === 0) {
                // First service input (full width)
                newInputGroup.innerHTML = `
                    <div class="col-md-12">
                        <input type="text" name="services[]" class="form-control" placeholder="Enter service">
                    </div>
                `;
            } else {
                // Subsequent service inputs (two columns with remove button)
                newInputGroup.innerHTML = `
                    <div class="col-md-10">
                        <input type="text" name="services[]" class="form-control" placeholder="Enter service">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-danger remove-item" type="button">Remove</button>
                    </div>
                `;
            }
            servicesList.appendChild(newInputGroup);
        });

        document.getElementById('add-more-food').addEventListener('click', function () {
            const foodList = document.getElementById('food-list');
            const foodCount = foodList.children.length; // Get the current count of foods
            const newInputGroup = document.createElement('div');
            newInputGroup.classList.add('row', 'mb-2'); // Create a new row
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

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.row').remove(); // Remove the closest row for both services and foods
            }
        });

        document.getElementById('package-form').addEventListener('keypress', function (event) {
            if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
                event.preventDefault();
                event.target.form.submit();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const priceInput = document.querySelector('input[name="price"]');

        // Function to format number with commas
        function formatWithCommas(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // If there is a value in the price input (old value), format it on page load
        if (priceInput.value) {
            let value = priceInput.value.replace(/,/g, ''); // Remove any existing commas
            priceInput.value = formatWithCommas(value); // Add commas
        }

        // Listen to price input event and format value dynamically
        priceInput.addEventListener('input', function () {
            let value = this.value.replace(/,/g, ''); // Remove existing commas
            if (!isNaN(value)) {
                // Format the number with commas
                this.value = formatWithCommas(value);
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
