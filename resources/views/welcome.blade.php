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
                        <p class="card-text"><strong>Start Date:</strong>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</p>
                        <a href="{{ route('events.photos', $event->id) }}"
                            class="btn btn-primary form-control text-white">View Event</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No events found.</p>
        @endforelse
    </div>
</div>
<div class="bg-white mb-5 pb-5">
    <div class="container">
        <h2>How to Order</h2>

        <div class="step-section">
            <div class="step-box">
                <div class="step-number"><i class="fa fa-search"></i></div>
                <div class="step-content">
                    <h3>Search Your Event</h3>
                    <p>Find your event by browsing through recent events or using the search bar.</p>
                </div>
            </div>

            <div class="step-box">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Browse Images</h3>
                    <p>Search using your club name or race number to find your images.</p>
                </div>
            </div>

            <div class="step-box">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Add to Cart</h3>
                    <p>Select the images you want, add them to your cart, and proceed to checkout.</p>
                </div>
            </div>

            <div class="step-box">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Complete Payment</h3>
                    <p>Provide your details, pay, and receive your images immediately.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection