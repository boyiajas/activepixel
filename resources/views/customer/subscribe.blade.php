@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
        <h2 class="mt-4">Subscription Successful</h2>
        <div class="alert alert-success mt-4">
            <p>Thank you for subscribing! You will now receive updates on the latest event listings, upcoming events, and new service offerings at your email: <strong>{{ $userEmail }}</strong>.</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home"></i> Go to Homepage
        </a>
    </div>
</div>
@endsection
