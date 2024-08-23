@extends('layouts.admin')

@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">{{ $event->name }} - Event Details</h4>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-primary float-right veiwbutton">< Go Back</a> 
                            <a href="{{ route('admin.photos.create', ['event_id' => $event->id]) }}" class="btn btn-primary float-right mr-2">Add Photo</a>
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary float-right mr-2">Edit Event</a>
                            <a href="{{ route('admin.photos.bulk.import', ['event_id' => $event->id]) }}" class="btn btn-primary float-right mr-2">Import Photos</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Event Details</h4>
                </div>
                <div class="card-body">

                    <h4 class="mb-2">Event Name: {{ $event->name }}</h4>
                    <p><strong>Slug:</strong> {{ $event->slug }}</p>
                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}</p>
                    <br/><br/><br/>
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item">
                            <a class="nav-link active" href="#event-info" data-toggle="tab">Event Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#photos" data-toggle="tab">Photos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#categories" data-toggle="tab">Categories</a>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Event Information Tab -->
                        <div class="tab-pane show active" id="event-info">
                            <form>
                                <div class="row formtype">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Event Name</label>
                                            <input class="form-control" type="text" value="{{ $event->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input class="form-control" type="text" value="{{ $event->start_date }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input class="form-control" type="text" value="{{ $event->end_date }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location</label>
                                            <input class="form-control" type="text" value="{{ $event->location }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @if($event->event_image)
                                                <img src="{{ $event->event_image_url }}" alt="Event Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input class="form-control" type="text" value="{{ $event->category->name ?? 'No category' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $event->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Photos Tab -->
                        <div class="tab-pane" id="photos">
                            <div class="table-responsive">
                                <h5 class="card-title">Photos</h5>
                                <table class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Photo ID</th>
                                            <th>Lead Image</th>
                                            <th>Race Number</th>
                                            <th>Price</th>
                                            <th>Stock Status</th>
                                            <th>Photo Type</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->photos as $photo)
                                        <tr>
                                            <td>{{ $photo->id }}</td>
                                            <td>
                                                @if($photo->leadImage())
                                                    <img src="{{ asset($photo->leadImage()?->file_path) }}" alt="lead Image" class="img-round" style="max-width: 50px;">
                                                @else
                                                    <img src="{{ asset('assets/img/placeholder.jpg') }}" alt="Lead Image" class="img-round" style="max-width: 50px;">
                                                @endif
                                            </td>
                                            <td>{{ $photo->race_number }}</td>
                                            <td>{{ $photo->price }}</td>
                                            <td>{{ $photo->stock_status }}</td>
                                            <td>{{ $photo->photo_type }}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('admin.photos.edit', $photo->id) }}"><i class="fas fa-pencil-alt m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_photo_{{ $photo->id }}"><i class="fas fa-trash-alt m-r-5"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Categories Tab -->
                        <div class="tab-pane" id="categories">
                            <div class="table-responsive">
                                <h5 class="card-title">Categories</h5>
                                <table class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Category ID</th>
                                            <th>Category Name</th>
                                            <th>Category Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Photo Modal -->
            @foreach($event->photos as $photo)
            <div id="delete_photo_{{ $photo->id }}" class="modal fade delete-modal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('assets/img/sent.png') }}" alt="" width="50" height="46">
                            <h3 class="delete_class">Are you sure want to delete this photo?</h3>
                            <div class="m-t-20">
                                <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                                <form action="{{ route('admin.photos.destroy', $photo->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
