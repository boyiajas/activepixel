@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
        <h2 class="mt-4">Order Successful</h2>
        <div class="alert alert-success mt-4">
        <p>Thank you for your order! Your payment has been received, and your photos have been sent to your email: <strong>{{ $userEmail }}</strong>.</p>
        </div>

        <!-- Display download links for the purchased photos -->
        @if(!empty($downloadLinks))
            <h4 class="mt-4">You can also download your photos directly below:</h4>
            <ul class="list-group mt-3">
                @foreach($downloadLinks as $link)
                    <li class="list-group-item">
                        <a href="{{ $link }}" download>
                            {{ strpos(basename($link), 'lead') !== false ? 'Download Photo - No Event Details' : 'Download Photo - With Event Details' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('customer.orders') }}" class="btn btn-primary mt-3">
            <i class="fas fa-box"></i> View Your Orders
        </a>
    </div>
</div>
@endsection
