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

@endsection
