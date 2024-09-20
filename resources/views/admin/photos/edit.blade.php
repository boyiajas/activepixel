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
                            <h4 class="card-title float-left mt-2">Edit Photo</h4>
                            <a href="{{ route('admin.photos.index') }}" class="btn btn-primary float-right veiwbutton">< Go Back</a> 
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.photos.update', $photo->id) }}" method="POST" id="photo-upload-form" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="row pb-5">
                    <div class="col-lg-12 mb-5">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Photo Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $photo->name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Race Number</label>
                                    <input type="number" class="form-control @error('race_number') is-invalid @enderror" name="race_number" value="{{ old('race_number', $photo->race_number) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $photo->price) }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $photo->description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stock Status</label>
                                    <select class="form-control @error('stock_status') is-invalid @enderror" name="stock_status">
                                        <option value="in_stock" {{ old('stock_status', $photo->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="out_of_stock" {{ old('stock_status', $photo->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
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
                                            <option value="{{ $event->id }}" {{ old('event_id', $photo->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
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
                                            <option value="{{ $type }}" {{ old('category_types', 'Club') == $type ? 'selected' : ''  }}>
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
                                            <option value="{{ $club->id }}" {{ old('club_id', $photo->category_id) == $club->id ? 'selected' : '' }}>
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
                                    <input type="checkbox" name="downloadable" value="1" {{ old('downloadable', $photo->downloadable) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <br/>
                                    <button type="submit" class="btn btn-primary buttonedit1">Update Photo</button>
                                </div>
                            </div>

                        </div>
                        <hr/>
                            <!-- Image Upload Section start here -->
                        <div class="col-lg-12">
                            <div id="ImagesVids" class="tab-pane in active">
                                <br>
                                <h4>Upload Race Images</h4>
                                <input id="userId" name="userId" type="hidden" value="12397">
                                <input name="id" type="hidden" value="4118">
                                                            <input name="lead_image" type="hidden" value="/assets/img/placeholder.jpg">
                                
                                                            <input name="id_photos" type="hidden">
                                
                                                            <input name="resolution" type="hidden">
                                
                                                            <input name="image_gallery" type="hidden">
                                
                                <div class="form-group">
                                    <label for="lead_image">Lead Image:</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if($lead_image == '' || $lead_image == null)
                                                <img id="lead_image" class="img" style="border:5px solid #f04f23;height: 215px;" src="/assets/img/placeholder.jpg"
                                            alt="Photo Lead Image Placeholder"/>
                                            @else
                                                <img id="lead_image" class="img" style="border:5px solid #f04f23;height: 215px;"
                                                    src="/{{ $lead_image }}"
                                                    alt="{{$photo->name}} Photo Lead Image"/>
                                            @endif
                                             
                                        </div>
                                        <div class="col-md-3">
                                            <div id="fileUpload" class="dropzone">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider">
                                    <i class="icon-circle"></i>
                                </div>

                                <div id="image_gallery_div">
                                    <h4>Image Gallery</h4>
                                    <p>A minimum of 3 images of the race are required. Maximum 24.</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="multifileUpload" class="dropzone square"></div>
                                        </div>
                                       
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- Image upload section ends here -->
                        </div>

                       
                        
                    </div>
                    
                
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <!-- Dropzone.js script -->
    

    {{-- drop zone style --}}
	<link rel="stylesheet" href="{{ URL::to('assets/css/dropzone.css') }}"> 
    <script src="{{ URL::to('assets/js/jquery.js') }}"></script>
    <script src="{{ URL::to('assets/js/dropzone.js') }}"></script>
	
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script> -->
    
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

            function removeAmpAndApostropheFromString(str) {
                return str.replace(/amp;/g, '').replace(/&#039;/g, "'");
            }

            Dropzone.autoDiscover = false;
            var token = "{!! csrf_token() !!}";
            var baseUrl = "{{ url('') }}"; // Gets the current base URL

            $("#fileUpload").dropzone({
                paramName: 'file',
                url: '{{ route('uploads.image.store') }}',//i
                clickable: true,
                enqueueForUpload: true,
                addRemoveLinks: true,
                maxFilesize: 10,
                uploadMultiple: false,
                params: {
                    _token: token,
                    photo_type: 'lead_image',
                    photo_id: "{{ $photo->id}}"
                },

                maxFiles: 1,
                init: function () {
                    this.on("addedfile", function (file) {
                        
                        this.options.url = '{{ route('uploads.image.store') }}'; //'/upload/image/lead_ima
                    });
                    this.on("maxfilesexceeded", function (file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    });
                    this.on("success", function (file, response) {
                        console.log(response)
                        $("input[name=lead_image]").val(response.file_path);
                        d = new Date();
                        $("#lead_image").attr("src", "/" + response.file_path + '?' + d.getTime());
                        $("#lead_url").html('').append(baseUrl + "/" + response.file_path);
                    
                    });
                    this.on("error", function (file, response){
                        alert('failed to upload');
                        console.log(response);
                    });

                    this.on("removedfile", function (file, response) {

                        var response = JSON.parse(file.xhr.response);
                         // Get the ID from the parsed response
                        var id = response.id;
                        //console.log("file to be remove is below");
                        //console.log(id);
                        
                        if (id) {
                            $.ajax({
                                type: 'GET',
                                url: '{{ route('upload.image.delete') }}',
                                data: { id: id }, // Pass the file server ID to the delete route
                                success: function(data){
                                    $("input[name=lead_image]").val('');
                                    $("#lead_image").attr("src", '/assets/img/placeholder.jpg'); // Clear the image preview
                                    $("#lead_url").html(''); // Clear the URL display
                                },
                                error: function() {
                                    console.log('Failed to delete file.');
                                }
                            });
                        }
                    });


                }
            });




            Dropzone.autoDiscover = false;
            var token = "{!! csrf_token() !!}";
            Dropzone.options.myDropzone = {
                init: function() {
                    this.on("processing", function(file) {
                    });
                }
            };
            $("#multifileUpload").dropzone({

                paramName: 'file',
                url: '{{ route('uploads.image.store') }}',//'/upload/image/image/
                dictDefaultMessage: "Drag your images",
                clickable: true,
                enqueueForUpload: false,
                maxFilesize: 10,
                uploadMultiple: false,
                addRemoveLinks: true,
                autoProcessQueue: true,
                parallelUploads: 1,
                params: {
                    _token: token,
                    photo_type: 'regular',
                    photo_id: "{{ $photo->id}}"
                },

                init: function () {
                    thisDropzone = this;
                    var count = 0;
                    @foreach ($regular_images as $file)
                            count++;
                    var mockFile = {name: '{{$file->id}}', id: '{{$file->id}}'};
                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    var filterUrl = removeAmpAndApostropheFromString('/{{ $file->file_path}}');
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, filterUrl);
                    @endforeach
                    this.on("addedfile", function (file) {

                        this.options.url =  '{{ route('uploads.image.store') }}';//'/
                        count++;
                        if (count > 0) {
                            $("input[name=image_gallery]").val(1);
                        } else {
                            $("input[name=image_gallery]").val(null);
                        }
                    });
                    this.on("removedfile", function (file) {
                        count--;
                        
                        if (file.xhr) {
                            //var id = JSON.parse(file.xhr.response);
                            //fileid = id;
                            var response = JSON.parse(file.xhr.response);
                            id = response.id; // Get the ID from the parsed response
                        } else {
                            id = file['id'];
                        } 

                        $.ajax({
                            type: 'GET', // Using GET method for deletion (or use DELETE if the backend supports it)
                            url: '{{ route('upload.image.delete') }}', // Adjust the route to match your delete route
                            data: { id: id }, // Pass the file server ID to the delete route
                            headers: {
                                'X-CSRF-TOKEN': token
                            },
                            success: function (data) {
                                console.log('File deleted successfully');
                                if (count > 0) {
                                    $("input[name=image_gallery]").val(1);
                                } else {
                                    $("input[name=image_gallery]").val(null);
                                }
                                //$("input[name=image_gallery]").val(''); // Clear the hidden input value
                            },
                            error: function () {
                                console.log('Failed to delete file.');
                            }
                        });
                        
                    });

                }
            });

       });
       
        // Regular Images Dropzone Configuration
      
    </script>
    @endsection

@endsection
