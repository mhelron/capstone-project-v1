@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add User</h1>
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
                    <a href="{{ route('admin.users') }}" class="btn btn-danger">Back</a>
                </div>
                
                <!-- Add User Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label>First Name <span class="text-danger"> *</span></label></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control">

                                @if ($errors->has('first_name'))
                                    <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Last Name <span class="text-danger"> *</span></label></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">

                                @if ($errors->has('last_name'))
                                    <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Email <span class="text-danger"> *</span></label></label>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                                
                                @if ($errors->has('email'))
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                @endif
                            </div>
              
                            <div class="form-group mb-3">
                                <label>User Role <span class="text-danger"> *</span></label></label>
                                <select name="user_role" class="form-control">
                                    <option value="" disabled selected>Select a role</option>
                                    <option value="Super Admin" {{ old('user_role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="Admin" {{ old('user_role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Manager" {{ old('user_role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Staff" {{ old('user_role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                </select>

                                @if ($errors->has('user_role'))
                                    <small class="text-danger">{{ $errors->first('user_role') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Password <span class="text-danger"> *</span></label></label>
                                <input type="password" name="password" class="form-control">

                                @if ($errors->has('password') && $errors->first('password') !== 'The password confirmation does not match.')
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Confirm Password <span class="text-danger"> *</span></label></label></label>
                                <input type="password" name="password_confirmation" class="form-control">

                                @if (!$errors->has('password') && $errors->has('password_confirmation'))
                                    <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                                @elseif ($errors->has('password') && $errors->first('password') === 'The password confirmation does not match.')
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection