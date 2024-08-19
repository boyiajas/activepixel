@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
        <h2 class="mt-4">Order Successful</h2>
        <div class="alert alert-success mt-4">
            <p>Thank you for your order! Your payment has been received, and your order is being processed.</p>
        </div>
        <a href="{{ route('customer.orders') }}" class="btn btn-primary mt-3">
            <i class="fas fa-box"></i> View Your Orders
        </a>
    </div>
</div>
@endsection
