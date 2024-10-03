@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="text-center">
    <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
        <h2 class="mt-4">Unsubscribed Successfully</h2>
        <div class="alert alert-danger mt-4">
            <p>You have successfully unsubscribed from receiving updates on events and offers at your email: <strong>{{ $userEmail }}</strong>.</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home"></i> Go to Homepage
        </a>
    </div>
</div>
@endsection
