@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Order History</h4>
        </div>
        <div class="card-body">
            @include('partials.common-table', ['data' => $orders, 'viewRoute' => 'customer.orders.show'])
        </div>
    </div>
</div>
@endsection
