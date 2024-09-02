@extends('layouts.guest2')
@section('title') Home @stop
@section('content')

<div class="crumb">
    <div class="overlay-text">
        <h1>@yield('crumb-overlay-text') </h1>
        <div class="search-section">
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Search by Event">
                <button class="search-button">Find Event</button>
            </div>

            

        </div>
    </div>
</div>
</div>
<div class="banner">
    <div class="events-text">
        <h2>2024 EVENTS</h2>
    </div>
</div>

<div class="container">

    <!-- Events Listing -->
    <div class="row event-gallery">
        @forelse($events as $event)
        <div class="col-md-3 mb-4">
            <div class="card event-card h-30">
                <img src="{{ $event->getEventImageUrlAttribute() }}" alt="{{ $event->name }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p class="card-text"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</p>
                    <a href="{{ route('events.photos', $event->id) }}" class="btn btn-primary form-control text-white">View Event</a>
                </div>
            </div>
        </div>
        @empty
        <p>No events found.</p>
        @endforelse
    </div>
</div>
@endsection