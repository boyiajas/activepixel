@extends('layouts.guest')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Your Cart</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(count($cartItems) > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item Photo</th>
                                <th>Slug & Description</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Remove</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->photo->leadImage()->file_path }}" alt="{{ $item->photo->slug }}" style="width: 100px; height: 100px;">
                                </td>
                                <td>
                                    <strong>{{ $item->photo->slug }}</strong>
                                    <p>{{ $item->photo->description }}</p>
                                </td>
                                <td>R{{ number_format($item->photo->price, 2) }}</td>
                                <td>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" data-item-id="{{ $item->id }}" disabled>
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                                <td>R{{ number_format($item->photo->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Total: R{{ number_format($totalPrice, 2) }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
                        </div>
                    </div>
                    @else
                    <p>Your cart is empty. <a href="{{ route('welcome') }}">Shop Now</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
