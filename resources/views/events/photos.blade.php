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
            <li class="breadcrumb-item"><a href="#">{{ $event->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Photos</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-12">
            <p><strong>Description:</strong> {{ $event->description }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Filters Section -->
        <div class="row mb-4 col-2 mr-2" style="background-color:#f1fdff;">
            <form action="{{ route('events.all') }}" method="GET" class="">
                <div class="mt-3">
                    <label for="year">Year:</label>
                    <select name="year" id="year" class="form-control">
                        <option value="">All Years</option>
                        @foreach(range(date('Y'), date('Y') - 10) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <label for="month">Month:</label>
                    <select name="month" id="month" class="form-control">
                        <option value="">All Months</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <label for="name">Race:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Race No">
                </div>
                <div class="mt-3">
                    <label for="location">Location:</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Location">
                </div>
                <button type="submit" class="btn btn-primary form-control mt-3">Filter</button>
            </form>
        </div>

        <div class="col-10">

            <!-- Photo Gallery -->
            <div class="row photo-gallery" id="photoGallery">
                @foreach($photos as $photo)
                    <div class="col-md-2 mb-0">
                        <div class="card photo-card h-30 mt-0">
                        @php
                            // Find the position of the last dot (.) before the extension
                            $lastDotPosition = strrpos($photo->leadImage()?->file_path, '.');

                            // Extract the base name and the extension
                            $baseName = substr($photo->leadImage()?->file_path, 0, $lastDotPosition);
                            $extension = substr($photo->leadImage()?->file_path, $lastDotPosition);

                            // Create the watermarked image path
                            $watermarked_image = $baseName . '.watermark' . $extension;
                        @endphp
                            <img src="/{{ $watermarked_image }}" alt="{{ $photo->name }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Race No: {{ $photo->race_number }}</h5>
                                <p class="card-text">Price: R{{ number_format($photo->price, 2) }}</p>
                                
                                <form action="{{ route('cart.add', ['photo_id' => $photo->id]) }}" method="POST" class="d-flex align-items-center justify-content-between">
                                    @csrf
                                    <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width:49%;"><i class="fas fa-shopping-cart"></i></button>
                                    <a href="{{ route('individual.photos', $photo->id) }}" class="btn btn-primary btn-sm" style="width:49%;">View</a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Load More Button -->
            <div class="pull-right">
                <button id="loadMore" class="btn btn-primary">Load More</button>
            </div>
        </div>

        
    </div>
</div>

<script>
    let currentPage = 1;
    let lastPage = {{ $photos->lastPage() }};
    let eventId = {{ $event->id }};

    document.getElementById('loadMore').addEventListener('click', function() {
        if (currentPage < lastPage) {
            currentPage++;
            fetchMorePhotos(currentPage);
        } else {
            this.disabled = true;
            this.innerText = "No More Photos";
        }
    });

    function fetchMorePhotos(page) {
        fetch(`/events/{{ $event->id }}/photos?page=${page}`)
            .then(response => response.json())
            .then(data => {
                let gallery = document.getElementById('photoGallery');
                data.photos.forEach(photo => {
                    let photoCard = `
                        <div class="col-md-2">
                            <div class="card photo-card h-30 mt-0">
                                <img src="/${photo.lead_image}" alt="${photo.name}" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">Race No: ${photo.race_number}</h5>
                                    <p class="card-text">Price: R${photo.price}</p>
                                    <form action="/cart/add/${photo.id}" method="POST" class="d-flex align-items-center justify-content-between">
                                        @csrf
                                        <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                                        <button type="submit" class="btn btn-primary btn-sm" style="width:49%;"><i class="fas fa-shopping-cart"></i></button>
                                        <a href="/individual-photos/${photo.id}" class="btn btn-primary btn-sm" style="width:49%;">View</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                    gallery.insertAdjacentHTML('beforeend', photoCard);
                });
            });
    }
</script>
@endsection
