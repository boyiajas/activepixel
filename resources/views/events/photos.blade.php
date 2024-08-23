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
    }
    .photo-gallery .col-md-2 {
        margin-bottom: 20px;
    }
    body {
        background-color: #fff;
    }
</style>
<div class="container mt-5">
    <h2 class="mb-4">{{ $event->name }} Photos</h2>

    <div class="row mb-4">
        <div class="col-md-12">
            <p><strong>Description:</strong> {{ $event->description }}</p>
        </div>
    </div>

    <!-- Photo Gallery -->
    <div class="row photo-gallery">
        @forelse($photos as $photo)
        <div class="col-md-2">
            <div class="card photo-card h-30">
                <img src="/{{ $photo->leadImage()?->file_path }}" alt="{{ $photo->name }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $photo->name }}</h5>
                    <div class="card-text1">
                        <p class="card-text">Price: R{{ number_format($photo->price, 2) }}</p>
                    </div>
                    
                    <form action="{{ route('cart.add', ['photo_id' => $photo->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                        <button type="submit" class="btn btn-primary">Add to <i class="fas fa-shopping-cart"></i></button>
                        <a href="{{ route('individual.photos', $photo->id) }}" class="btn btn-primary">View</a>
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
        {{ $photos->links() }}
    </div>
</div>
@endsection
