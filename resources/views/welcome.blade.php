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
<div class="Ecard">
    <div class="events-text">
        <h2>2024 EVENTS</h2>
    </div>
</div>
@endsection