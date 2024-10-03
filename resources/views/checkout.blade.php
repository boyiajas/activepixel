@extends('layouts.guest')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Checkout</h2>
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h4>Billing Details</h4>

                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="billing_first_name">First Name <span class="text-red">*</span></label>
                                    <input type="text" name="billing_first_name" id="billing_first_name" class="form-control" value="{{ old('billing_first_name', $billingInformation['billing_first_name'] ?? '') }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="billing_last_name">Last Name <span class="text-red">*</span></label>
                                    <input type="text" name="billing_last_name" id="billing_last_name" class="form-control" value="{{ old('billing_last_name', $billingInformation['billing_last_name'] ?? '') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="billing_email">Email Address <span class="text-red">*</span></label>
                                <input type="email" name="billing_email" id="billing_email" class="form-control" value="{{ old('billing_email', $billingInformation['billing_email'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_phone">Phone Number <span class="text-red">*</span></label>
                                <input type="text" name="billing_phone" id="billing_phone" class="form-control" value="{{ old('billing_phone', $billingInformation['billing_phone'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_address">Address</label>
                                <input type="text" name="billing_address" id="billing_address" class="form-control" value="{{ old('billing_address', $billingInformation['billing_address'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_city">City <span class="text-red">*</span></label>
                                <input type="text" name="billing_city" id="billing_city" class="form-control" value="{{ old('billing_city', $billingInformation['billing_city'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_state">Province <span class="text-red">*</span></label>
                                <input type="text" name="billing_state" id="billing_state" class="form-control" value="{{ old('billing_state', $billingInformation['billing_state'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_zip">Zip/Postal Code</label>
                                <input type="text" name="billing_zip" id="billing_zip" class="form-control" value="{{ old('billing_zip', $billingInformation['billing_zip'] ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="billing_country">Country</label>
                                <input type="text" name="billing_country" id="billing_country" class="form-control" value="{{ old('billing_country', $billingInformation['billing_country'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                            <input type="checkbox" class="optin" name="optin" value="optin" {{ old('optin', Auth::user()->optin) ? 'checked' : '' }}>
                             <span style="font-size: 17px;font-weight: 400; color: #333;">I want to subscribe for receive news on the latest event listings, upcoming events, new service offerings and Whatsapp communication.</span>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Place Order</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h4>Order Summary</h4>
                            
                            <ul class="list-group mt-4">
                                <li class="list-group-item d-flex justify-content-between">
                                    <h6 class="my-0"><strong>Product</strong></h6>
                                    <h6 class="my-0"><strong>Sub Total</strong> </h6>
                                </li>
                            </ul>
                            <ul class="list-group mb-3">
                                @foreach($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">{{ $item->photo->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <span class="text-muted">R{{ number_format($item->photo->price * $item->quantity, 2) }}</span>
                                </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (ZAR)</span>
                                    <strong>R{{ number_format($totalPrice, 2) }}</strong>
                                </li>
                            </ul>

                            <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-block">Back to Cart</a>
                            <img class="mx-auto mt-4 mb-3" src="/images/Payfast.png" alt="Pay Fast" style="height: 100px;display:block;">
                            <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#">privacy policy</a></p>
                            <button type="submit" class="btn btn-primary mt-3 btn-block">Place Order</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
@endsection
