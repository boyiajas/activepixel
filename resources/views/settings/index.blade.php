@extends('layouts.admin2')

@section('content')

     {{-- message --}}
     {!! Toastr::message() !!}
    <div class="main-wrapper">

        



        <div class="page-wrapper">
            <div class="content mt-5">
                <div class="row ">
                    <div class="col-lg-12">
                        <form>
                            <h3 class="page-title">Basic Settings</h3>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State/Province</label>
                                        <select class="form-control" id="sel1" name="sellist1">
                                            <option>Select</option>
                                            <option>California</option>
                                            <option>Tamil nadu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email ID</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fax</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Website URL</label>
                                        <input class="form-control " value="Daniel Porter" type="text">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary buttonedit mr-5 mt-5">Save</button>
        </div>

    </div>


    <script src="assets/js/select2.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script>
        $(function () {
            $('#datetimepicker3').datetimepicker({
                format: 'LT'

            });
        });
    </script>

@endsection