@extends('layouts.guest2')
@section('title') Home @stop
@section('content')

<style>
    .event-card {
        position: relative;
        padding: 0;
        overflow: hidden; /* Ensure the overlay is contained */
        border-radius: 15px;
    }

    .event-card .card-img-top {
        height: 200px;
        width: 100%;
        transition: transform 0.5s ease-in-out;
    }

    .event-card:hover .card-img-top {
        transform: scale(1.3); /* Slight zoom on hover */
    }

    /* Overlay styles */
    .event-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: rgba(0, 151, 178, 0.5); /* Blue color with 50% opacity */
        transform: translateY(100%); /* Hidden initially by pushing it down */
        transition: transform 0.4s ease; /* Smooth animation for the overlay */
    }

    .event-card:hover .event-overlay {
        transform: translateY(0); /* Slide overlay up on hover */
    }

    .event-card .card-body {
        padding: 10px;
    }

    .event-gallery {
        min-height: 300px;
    }

    body {
        background-color: #fff;
        height: fit-content;
    }

    .search-section {
        margin-top: 20px;
    }

    .dropdown-container {
        flex: 1;
        max-width: 150px;
    }

    .search-bar-container {
        flex: 2;
    }

    .search-button-container {
        flex: 1;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width:100%;
    }

    #search-category {
        padding: 8px;
    }

    #search-bar {
        padding: 10px;
        width: 80%;
    }

    #search-button {
        width: 10%;
    }

    .search-section {
    margin-top: 100px;
}

.search-bar-wrapper {
    display: flex;
    width: 100%;
}

.dropdown-container {
    position: relative;
}

.search-dropdown {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none; /* Remove border between dropdown and input */
    height: 45px;
    padding: 8px 12px;
    font-size: 16px;
    width: 120px;
}

.search-input {
    flex-grow: 1;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    height: 45px;
    font-size: 16px;
    padding-left: 10px;
}

.search-button {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    height: 45px;
    font-size: 16px;
}

.container {
    
    justify-content: center;
    align-items: center;
}

.step-number .fa-search, .step-number .fa-shopping-cart, .step-number .fa-university, .step-number .fa-camera{
    font-size: 100px;
}

.how-to-order-section{
    background-color: #fafafa;
    padding-top: 100px;
    padding-bottom: 100px;
}

.how-to-order-title{
    font-weight: bolder;
    color:#0097b2;
}
</style>


<div class="crumb">
    <div class="overlay-container">
        <div class="overlay-text">
            <h1>@yield('crumb-overlay-text') </h1>
            <div class="search-section container">
                <div class="search-container d-flex justify-content-center">
                    <!-- Search input field with dropdown inside -->
                    <form action="{{ route('items.search') }}" method="GET" class="search-bar-wrapper d-flex">
                        <div class="dropdown-container" style="color: #fff;background-color: #ffffff00 !important;">
                            <select name="search_category" class="form-select search-dropdown" id="search-category">
                                <option value="event">By Event</option>
                                <option value="club">By Club</option>
                                <option value="race_no">By Race No</option>
                            </select>
                        </div>
                        <input type="text" name="search_query" class="form-control search-input" id="search-bar" placeholder="Search..." required>
                        <button type="submit" class="btn btn-primary search-button" id="search-button">Find</button>
                    </form>
                    <!-- <div class="search-bar-wrapper d-flex">
                    
                        <div class="dropdown-container" style="color: #fff;background-color: #ffffff00 !important;">
                            <select class="form-select search-dropdown" id="search-category">
                                <option value="event">By Event</option>
                                <option value="club">By Club</option>
                                <option value="race_no">By Race No</option>
                            </select>
                        </div>
                        <input type="text" class="form-control search-input" id="search-bar" placeholder="Search...">
                        <button class="btn btn-primary search-button" id="search-button">Find</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="banner">
    <div class="events-text">
        <h2 class="mb-0">UPCOMING EVENTS</h2>
    </div>
</div>

<div class="container">

     <!-- Events Listing -->
    <div class="row event-gallery">
        @php
            $upcomingevents = [];
        @endphp
        @forelse($upcomingevents as $event)
        <div class="col-md-3 mb-4">
            <div class="card event-card h-30">
                <a href="{{ route('events.photos', $event->id) }}">
                    <img src="{{ $event->getEventImageUrlAttribute() }}" alt="{{ $event->name }}" class="card-img-top" />
                    <div class="event-overlay"></div> <!-- Overlay div -->
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p class="card-text"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</p>
                    <!-- <a href="{{ route('events.photos', $event->id) }}" class="btn btn-primary form-control text-white">View Event</a> -->
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 no-item" style="">
                <center>
                    <div>
                        <i class="fa fa-folder-open mb-3 mt-5" aria-hidden="true" style="font-size: 100px;border-radius: 50%;padding: 20px;background-color: #e8fbff;color: #fff;"></i>
                    </div>
                    <p>No upcoming events found.</p>
                </center>
            </div>
        @endforelse
    </div>
</div>

<div class="banner">
    <div class="events-text">
        <h2 class="mb-0">2024 EVENTS</h2>
    </div>
</div>

<div class="container">

     <!-- Events Listing -->
    <div class="row event-gallery">
        @forelse($events as $event)
        <div class="col-md-3 mb-4">
            <div class="card event-card h-30">
                <a href="{{ route('events.photos', $event->id) }}">
                    <img src="{{ $event->getEventImageUrlAttribute() }}" alt="{{ $event->name }}" class="card-img-top" />
                    <div class="event-overlay"></div> <!-- Overlay div -->
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p class="card-text"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</p>
                    <!-- <a href="{{ route('events.photos', $event->id) }}" class="btn btn-primary form-control text-white">View Event</a> -->
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 no-item" style="">
                <center>
                    <div>
                        <i class="fa fa-folder-open mb-3 mt-5" aria-hidden="true" style="font-size: 100px;border-radius: 50%;padding: 20px;background-color: #e8fbff;color: #fff;"></i>
                    </div>
                    <p>No events found.</p>
                </center>
            </div>
        @endforelse
    </div>
</div>
<div class="mb-5 how-to-order-section" id="how-to-order">
    <div class="container">
        <h2 class="mb-5 bold how-to-order-title">How to Order</h2>

        <div class="step-section">
            <div class="step-box">
                <div class="row pt-4">
                    <div class="step-number col-4"><i class="fa fa-search"></i></div>
                    <div class="step-content col-8">
                        <h3>Search Your Event</h3>
                        <p>Find your event by browsing through recent events or using the search bar.</p>
                    </div>
                </div>
            </div>

            <div class="step-box">
                <div class="row pt-4">
                    <div class="step-number col-4"><i class="fa fa-camera"></i></div>
                    <div class="step-content col-8">
                        <h3>Browse Images</h3>
                        <p>Search using your club name or race number to find your images.</p>
                    </div>
                </div>
            </div>

            <div class="step-box">
                <div class="row pt-4">
                    <div class="step-number col-4"><i class="fa fa-shopping-cart"></i></div>
                    <div class="step-content col-8">
                        <h3>Add to Cart</h3>
                        <p>Select the images you want, add them to your cart, and proceed to checkout.</p>
                    </div>
                </div>
            </div>

            <div class="step-box">
                <div class="row pt-4">
                    <div class="step-number col-4"><i class="fa fa-university"></i></div>
                    <div class="step-content col-8">
                        <h3>Complete Payment</h3>
                        <p>Provide your details, pay, and receive your images immediately.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection