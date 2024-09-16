@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Digital Downloads</h4>
        </div>
        <div class="card-body">
            @include('partials.common-table', ['data' => $downloads, 'viewRoute' => 'customer.downloads.show'])
        </div>
    </div>
</div>
@endsection
