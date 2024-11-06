@extends('layouts.adminlayout')

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

							<!-- Start of row -->
							<div class="row">
								
								<!-- First Name -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>First Name</label>
										<input type="text" name="first_name" value="{{ old('first_name', $editdata['fname']) }}" class="form-control">
										@error('first_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Last Name -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Last Name</label>
										<input type="text" name="last_name" value="{{ old('last_name', $editdata['lname']) }}" class="form-control">
										@error('last_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Email -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Email</label>
										<input type="text" name="email" value="{{ old('email', $editdata['email']) }}" class="form-control">
										@error('email')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- User Role -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>User Role</label>
										<select name="user_role" class="form-control">
											<option value="Super Admin" {{ $editdata['user_role'] == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
											<option value="Admin" {{ $editdata['user_role'] == 'Admin' ? 'selected' : '' }}>Admin</option>
											<option value="Manager" {{ $editdata['user_role'] == 'Manager' ? 'selected' : '' }}>Manager</option>
											<option value="Staff" {{ $editdata['user_role'] == 'Staff' ? 'selected' : '' }}>Staff</option>
										</select>
										@error('user_role')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Password -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>New Password</label>
										<input type="password" name="password" class="form-control">
										@error('password')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Confirm Password -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Confirm New Password</label>
										<input type="password" name="password_confirmation" class="form-control">
										@error('password_confirmation')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

							</div>
							<!-- End of row -->

							<div class="form-group mb-3">
								<button type="submit" class="btn btn-primary float-end">Update User</button>
							</div>

						</form>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

@endsection
