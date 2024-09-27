@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Edit User</h1>
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

				<!-- Edit User Form -->
				<div class="card">
					<div class="card-body">
						<form action="{{ url('admin/users/update-user/'.$key) }}" method="POST">
							@csrf
							@method('PUT')

							<div class="form-group mb-3">
								<label>First Name</label>
								<input type="text" name="first_name" value="{{ old('first_name', $editdata['fname']) }}" class="form-control">
								@if ($errors->has('first_name'))
								  <small class="text-danger">{{ $errors->first('first_name') }}</small>
								@endif
							</div>

							<div class="form-group mb-3">
								<label>Last Name</label>
								<input type="text" name="last_name" value="{{ old('last_name', $editdata['lname']) }}" class="form-control">
								@if ($errors->has('last_name'))
								  <small class="text-danger">{{ $errors->first('last_name') }}</small>
								@endif
							</div>

							<div class="form-group mb-3">
								<label>Email</label>
								<input type="text" name="email" value="{{ old('email', $editdata['email']) }}" class="form-control">
								@if ($errors->has('email'))
								  <small class="text-danger">{{ $errors->first('email') }}</small>
							   @endif
							</div>

							<div class="form-group mb-3">
								<label>User Role</label>
								<div class="d-flex align-items-center">
								  <select name="user_role" class="form-control">
									  <option value="Super Admin" {{ $editdata['user_role'] == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
									  <option value="Admin" {{ $editdata['user_role'] == 'Admin' ? 'selected' : '' }}>Admin</option>
									  <option value="Manager" {{ $editdata['user_role'] == 'Manager' ? 'selected' : '' }}>Manager</option>
									  <option value="Staff" {{ $editdata['user_role'] == 'Staff' ? 'selected' : '' }}>Staff</option>
								  </select>
								</div>

								@if ($errors->has('user_role'))
								  <small class="text-danger">{{ $errors->first('user_role') }}</small>
							   @endif
							</div>

							<div class="form-group mb-3">
								<label>New Password</label>
								<input type="password" name="password" class="form-control">

								@if ($errors->has('password') && $errors->first('password') !== 'The password confirmation does not match.')
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
							</div>

							<div class="form-group mb-3">
								<label>Confirm New Password</label>
								<input type="password" name="password_confirmation" class="form-control">

								@if (!$errors->has('password') && $errors->has('password_confirmation'))
                                    <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                                @elseif ($errors->has('password') && $errors->first('password') === 'The password confirmation does not match.')
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
							</div>

							<div class="form-group mb-3">
								<button type="submit" class="btn btn-primary">Update</button>
							</div>
						</form>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

@endsection