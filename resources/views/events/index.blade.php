@extends('layouts.guest')

@section('content')
<style>
    .event-card{
        padding: 0px;
    }
    .event-card .card-img-top{
        height: 200px;
    }
    .event-card .card-body{
        padding: 10px;
    }
    .event-gallery{
        min-height: 300px;
    }
    body{
        background-color: #fff;
        height: fit-content;
    }
</style>
<div class="container mt-5">

    

    
        <h2 class="mb-4">All Events</h2>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Events</li>
            </ol>
        </nav>

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
                    <label for="name" class="mr-2">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Event Name">
                </div>
                <div class="form-group mr-2">
                    <label for="location" class="mr-2">Location:</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Location">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

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

        <!-- Pagination (if needed) -->
        <div class="row justify-content-center">
            <div class="col-auto" style="width:100%;">
            {{ $events->links('pagination::bootstrap-5') }}
            </div>
        </div>
    
</div>

@endsection
