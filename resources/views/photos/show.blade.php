@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-7">
            <!-- Lead Image Display -->
            <div class="lead-image mb-4">
                <img src="{{ asset($lead_image) }}" alt="{{ $photo->name }}" class="img-fluid" style="height: 550px;">
            </div>

            <!-- Regular Images Display -->
            <div class="regular-images mb-4">
                <h5>More Views</h5>
                <div class="row">
                    @foreach($regular_images as $image)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset($image->file_path) }}" alt="{{ $photo->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recommended Photos Section -->
            <div class="recommended-photos mt-5">
                <h5>Recommended Photos</h5>
                <div class="row">
                    @foreach($recommendedPhotos as $recommended)
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('photos.show', $recommended->id) }}">
                                <img src="{{ asset('storage/'.$recommended->lead_image->file_path) }}" alt="{{ $recommended->name }}" class="img-fluid">
                                <p class="mt-2">{{ $recommended->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <!-- Event Details and Add to Cart Section -->
            <div class="event-details">
                <h3>{{ $photo->event->name }}</h3>
                <p><strong>Slug:</strong> {{ $photo->event->slug }}</p>
                <p>{{ $photo->event->description }}</p>

                <!-- Add to Cart Button -->
                <div class="add-to-cart mt-4">
                    <form action="{{ route('cart.add', ['photo_id' => $photo->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-cart"></i> Add to Cart - R{{ number_format($photo->price, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
