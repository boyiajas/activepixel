@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Invoices</h4>
        </div>
        <div class="card-body">
            @include('partials.common-table', ['data' => $invoices, 'viewRoute' => 'customer.invoices.show'])
        </div>
    </div>
</div>
@endsection
