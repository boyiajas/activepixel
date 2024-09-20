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
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="35.00">
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
                                    <label>Category Type</label>
                                    <select class="form-control @error('category_types') is-invalid @enderror" name="category_types" id="category_types">
                                        <option value="">Select Category</option>
                                        @foreach($category_types as $type)
                                            <option value="{{ $type }}" {{ old('category_types') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_types')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <!-- Club Selection (shown if 'Category Type' is 'Club') -->
                             <div class="col-md-4" id="club_select_div" style="display: none;">
                                <div class="form-group">
                                    <label>Select Club</label>
                                    <select class="form-control @error('club_id') is-invalid @enderror" name="club_id">
                                        <option value="">Select Club</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : ''}}>
                                                {{ $club->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('club_id')
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
    @section('script')      
                
        <script>
            
        $(document).ready(function(){

                // Initially hide the 'Select Club' dropdown
                var categoryType = $('#category_types').val();
                if (categoryType === 'Club') {
                
                    $('#club_select_div').show();
                }

                // Toggle the visibility of 'Select Club' dropdown based on 'Category Type'
                $('#category_types').change(function() {
                    
                    var selectedType = $(this).val();
                    if (selectedType === 'Club') {
                        $('#club_select_div').show();
                    } else {
                        $('#club_select_div').hide();
                    }
                });
            });
        </script>
    @endsection

@endsection
