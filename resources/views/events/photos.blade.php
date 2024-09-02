@extends('layouts.guest')

@section('content')
<style>
    .photo-card {
        padding: 0px;
    }
    .photo-card .card-img-top {
        height: 200px;
    }
    .photo-card .card-body {
        padding: 10px;
    }
    .photo-gallery {
        margin-bottom: 30px;
        min-height: 300px;
    }
    .photo-gallery .col-md-2 {
        margin-bottom: 20px;
    }
    body {
        background-color: #fff;
        height: fit-content;
    }
</style>
<div class="container mt-5">
    <h2 class="mb-4">{{ $event->name }} Photos</h2>

     <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white pl-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('events.all') }}">Events</a></li>
            <li class="breadcrumb-item"><a href="#">{{$event->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Photos</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-12">
            <p><strong>Description:</strong> {{ $event->description }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <form action="{{ route('events.all') }}" method="GET" class="form-inline d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div class="form-group mr-2">
                <label for="year" class="mr-2">Year:</label>
                <select name="year" id="year" class="form-control">
                    <option value="">All Years</option>
                    @foreach(range(date('Y'), date('Y') - 10) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <label for="month" class="mr-2">Month:</label>
                <select name="month" id="month" class="form-control">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <label for="name" class="mr-2">Race:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Race No">
            </div>
            <div class="form-group mr-2">
                <label for="location" class="mr-2">Location:</label>
                <input type="text" name="location" id="location" class="form-control" placeholder="Location">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>


    <!-- Photo Gallery -->
    <div class="row photo-gallery">
        @forelse($photos as $photo)
        <div class="col-md-2">
            <div class="card photo-card h-30">
                <img src="/{{ $photo->leadImage()?->file_path }}" alt="{{ $photo->name }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">Race No:{{ $photo->name }}</h5>
                    <div class="card-text1">
                        <p class="card-text">Price: R{{ number_format($photo->price, 2) }}</p>
                    </div>
                    
                    <form action="{{ route('cart.add', ['photo_id' => $photo->id]) }}" method="POST" class="flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                        @csrf
                        <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                        <button type="submit" class="btn btn-primary btn-sm" style="width:49%;">Add to <i class="fas fa-shopping-cart"></i></button>
                        <a href="{{ route('individual.photos', $photo->id) }}" class="btn btn-primary btn-sm" style="width:49%;">View</a>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p>No photos found for this event.</p>
        @endforelse
    </div>

    <!-- Pagination (if needed) -->
    <div class="row justify-content-center">
        <div class="col-auto" style="width:100%;">
        {{ $photos->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
