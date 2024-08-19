@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <i class="fas fa-times-circle text-danger" style="font-size: 72px;"></i>
        <h2 class="mt-4">Order Canceled</h2>
        <div class="alert alert-danger mt-4">
            <p>Your payment was not successful, and your order has been canceled.</p>
        </div>
        <a href="{{ route('cart.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-shopping-cart"></i> Back to Cart
        </a>
    </div>
</div>
@endsection
