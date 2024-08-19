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
                            <h4 class="card-title float-left mt-2">Create Photo</h4>
                            <a href="{{ route('admin.photos.index') }}" class="btn btn-primary float-right veiwbutton">< Go Back</a> 
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.photos.store') }}" method="POST" id="photo-upload-form" enctype="multipart/form-data">
                @csrf
                <div class="row pb-5">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Photo Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Race Number</label>
                                    <input type="number" class="form-control @error('race_number') is-invalid @enderror" name="race_number" value="{{ old('race_number') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <Label>Description</Label>
                                    <textarea class="form-control" name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stock Status</label>
                                    <select class="form-control @error('stock_status') is-invalid @enderror" name="stock_status">
                                        <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Event Selection -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event</label>
                                    <select class="form-control @error('event_id') is-invalid @enderror" name="event_id">
                                        <option value="">Select Event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Downloadable</label><br/>
                                    <input type="checkbox" name="downloadable" value="1" {{ old('downloadable') ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <br/>
                                    <button type="submit" class="btn btn-primary buttonedit1">Save Photo</button>
                    
                                </div>
                            </div>

                        </div>
                       
                       
                        
                    </div>
                    
                
                </div>
            </form>
        </div>
    </div>

@endsection
