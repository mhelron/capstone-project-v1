@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Reservation</h1>
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

                @if (session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('admin.reservation')}}" class="btn btn-danger">Back</a>
                </div>

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('admin.reserve.reserve') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6 mb-3">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control">
                                    @if ($errors->has('first_name'))
                                        <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                    @if ($errors->has('last_name'))
                                        <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                @if ($errors->has('address'))
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                @endif
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                    @if ($errors->has('phone'))
                                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                    @if ($errors->has('email'))
                                        <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="package_name">Package</label>
                                    <select onchange="packageChange()" name="package_name" id="package_name" class="form-control">
                                        <option value="" disabled selected>Select a Package</option>
                                        
                                    </select>

                                    @if ($errors->has('package_name'))
                                        <small class="text-danger">{{ $errors->first('package_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">  

                                <div class="col-md-6 mb-3">
                                    <label>Date of Event</label>
                                    <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-control">
                                    @if ($errors->has('event_date'))
                                        <small class="text-danger">{{ $errors->first('event_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Number of Guests</label>
                                    <input type="number" name="guests_number" value="{{ old('guests_number') }}" class="form-control">
                                    @if ($errors->has('guests_number'))
                                        <small class="text-danger">{{ $errors->first('guests_number') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Number of principal sponsors (ninong, ninang & parents)</label>
                                    <input type="number" id="sponsors" name="sponsors" value="{{ old('sponsors') }}" class="form-control" disabled>
                                    @if ($errors->has('sponsors'))
                                        <small class="text-danger">{{ $errors->first('sponsors') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Location or Venue</label>
                                    <input type="text" name="venue" value="{{ old('venue') }}" class="form-control">
                                    @if ($errors->has('venue'))
                                        <small class="text-danger">{{ $errors->first('venue') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Serving Time / Time of Event</label>
                                    <input type="time" name="event_time" value="{{ old('event_time') }}" class="form-control">
                                    @if ($errors->has('event_time'))
                                        <small class="text-danger">{{ $errors->first('event_time') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Color Motif or Theme</label>
                                <input type="text" name="theme" value="{{ old('theme') }}" class="form-control">
                                @if ($errors->has('theme'))
                                    <small class="text-danger">{{ $errors->first('theme') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Others (Request)</label>
                                <textarea name="other_requests" class="form-control">{{ old('other_requests') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Add Booking</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<script>
    var wed = "Wedding Package";

    if ($('#package_name :selected').text() == wed) {
        document.getElementById('sponsors').disabled = false;
    } else {
        document.getElementById('sponsors').disabled = true;
    }

document.getElementById('myForm').addEventListener('submit', function(event) {
        const sponsorsField = document.getElementById('sponsors');
        sponsorsField.disabled = false;
        
        if (sponsorsField.value === '') {
            sponsorsField.value = '0';
        }
    });
</script>
 
@endsection