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
                @foreach($downloadLinks as $item)
                    <li class="list-group-item">
                        <a href="{{ $item['link'] }}" download>
                            {{ strpos(basename($item['link']), 'lead') !== false ? 
                            'Download Photo - No Event Details'.' - '.$item['race_number'].' - '.$item['event']: 
                            'Download Photo - With Event Details'.' - '.$item['race_number'].' - '.$item['event']
                            }}
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
