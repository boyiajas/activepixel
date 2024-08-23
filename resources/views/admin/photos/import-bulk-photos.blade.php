@extends('layouts.admin')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5 mr-2">
                            <h4 class="card-title float-left mt-2">Import Bulk Photos for Event</h4>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-primary float-right veiwbutton">< Go Back</a> 
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.photos.bulk.store', $event->id) }}" method="POST" id="bulk-photo-import-form" enctype="multipart/form-data">
                @csrf
                <div class="row pb-5">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event Name</label>
                                    <input type="text" class="form-control" name="event_name" value="{{ $event->name }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ $event->start_date }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ $event->end_date }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload ZIP File</label>
                                    <input type="file" class="form-control @error('zip_file') is-invalid @enderror" name="zip_file" accept=".zip">
                                    @error('zip_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted mt-2">Upload a ZIP file containing folders of photos for the event. The folder name should follow the format <code>race_number_price_description</code>. example: <strong><i>(43001_54_this_is_our_description)</i></strong></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <br/>
                                    <button type="submit" class="btn btn-primary buttonedit1">Import Photos</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
