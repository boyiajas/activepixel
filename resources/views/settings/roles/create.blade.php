@extends('layouts.admin2')

@section('content')

    {{-- message --}}
    {!! Toastr::message() !!}

	<div class="main-wrapper">
		<div class="page-wrapper">
			<div class="content container-fluid">
				<div class="page-header">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="page-title mt-5">Add Role</h3> </div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf
							<div class="form-group">
								<label>Role Name <span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="name"> </div>
							<div class="form-group">
								<label class="display-block">Status</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="role_active" value="option1" checked>
									<label class="form-check-label" for="role_active"> Active </label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="role_inactive" value="option2">
									<label class="form-check-label" for="role_inactive"> Inactive </label>
								</div>
							</div>
							<div class="m-t-20">
								<button class="btn btn-primary submit-btn">Create Role</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script src="assets/js/jquery-3.5.1.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script>
	$(function() {
		$('#datetimepicker3').datetimepicker({
			format: 'LT'
		});
	});
	</script>

@endsection