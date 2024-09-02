@extends('layouts.admin')
@section('content')
    <style>
        .progress{
            height: 30px;
        }
        .progress-bar{
            background-color: #0097b2;
        }
    </style>
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
                                    <input type="file" class="form-control @error('zip_file') is-invalid @enderror" name="zip_file" id="zip_file" accept=".zip">
                                    @error('zip_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted mt-2">Upload a ZIP file containing images of photos for the event. The folder name should follow the format <code>price_description</code>. Example: <strong><i>(54_this_is_our_description)</i></strong> and each image should be named with its race number.</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select Photo Type</label>
                                    <select class="form-control @error('photo_type') is-invalid @enderror" name="photo_type" required>
                                        <option value="" disabled>Select Photo Type</option>
                                       
                                        <option value="lead_image">Lead Images</option>
                                        <option value="regular">Regular Images</option>
                                        
                                    </select>
                                    @error('photo_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                        <option value="" disabled>Select Category</option>
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
                                    <label>Import Options</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="import_option" id="skip_existing" value="skip" checked>
                                        <label class="form-check-label" for="skip_existing">
                                            Skip existing photo if a photo with the same race number and photo type already exists.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="import_option" id="replace_existing" value="replace">
                                        <label class="form-check-label" for="replace_existing">
                                            Replace and update the old photo if a photo with the same race number and photo type already exists.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="import_option" id="duplicate_existing" value="duplicate">
                                        <label class="form-check-label" for="duplicate_existing">
                                            Duplicate the photo if a photo with the same race number and photo type already exists in the database.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <br/>
                                    <button type="button" class="btn btn-primary buttonedit1" id="upload-button">Import Photos</button>
                                    <button type="button" class="btn btn-warning buttonedit1 mr-2" id="pause-button" style="display: none;">Pause Import</button>
                                </div>
                            </div>

                            <!-- Resumable.js Progress Bar -->
                            <div class="col-md-12">
                                <div id="progress-container" style="display: none;">
                                    <div class="progress">
                                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;height: 30px;">
                                            0%
                                        </div>
                                    </div>
                                    <small id="upload-status"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumable.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let form = document.getElementById('bulk-photo-import-form');
            let photoType = form.querySelector('select[name="photo_type"]').value;
            let category_id = form.querySelector('select[name="category_id"]').value;
            let importOption = form.querySelector('input[name="import_option"]:checked').value;

            let resumable = new Resumable({
                target: '{{ route('admin.photos.bulk.store', $event->id) }}',
                query: {
                    _token: '{{ csrf_token() }}',
                    photo_type: photoType,
                    category_id: category_id,
                    import_option: importOption
                },
                fileParameterName: 'zip_file',
                fileType: ['zip'],
                chunkSize: 10 * 1024 * 1024, // 10MB
                simultaneousUploads: 1,
                testChunks: false,
                throttleProgressCallbacks: 100,
                testChunksMethod: 'GET', // The HTTP method for the test chunks
                chunkRetryInterval: 5000, // Retry interval if chunk fails
                maxChunkRetries: 3, // Number of retries per chunk
            });

            resumable.assignBrowse(document.getElementById('zip_file'));

            resumable.on('fileAdded', function () {
                //$('#progress-container').show();
                //$('#pause-button').show();
            });

            resumable.on('fileProgress', function (file) {
                let progress = Math.floor(file.progress() * 100);
                $('#progress-bar').css('width', progress + '%').text(progress + '%');
            });

            resumable.on('fileSuccess', function (file, response) {
                console.log(file);
                console.log(response);
                $('#upload-status').text('Upload Complete');
                $('#pause-button').hide();
                $('#progress-bar').css('background-color', '#42bd42');
            });

            resumable.on('fileError', function (file, response) {
                $('#upload-status').text('Upload Failed');
            });

            document.getElementById('upload-button').addEventListener('click', function () {

                // Update the query parameters before uploading
                photoType = form.querySelector('select[name="photo_type"]').value;
                category_id = form.querySelector('select[name="category_id"]').value;
                importOption = form.querySelector('input[name="import_option"]:checked').value;

                resumable.opts.query.photo_type = photoType;
                resumable.opts.query.import_option = importOption;

                resumable.upload();
                $('#progress-container').show();
                $('#pause-button').show();
            });

            document.getElementById('pause-button').addEventListener('click', function () {
                if (resumable.isUploading()) {
                    resumable.pause();
                    $('#pause-button').text('Continue Import');
                } else {
                    resumable.upload();
                    $('#pause-button').text('Pause Import');
                }
            });
        });
    </script>
@endsection
