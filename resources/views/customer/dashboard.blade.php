@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="row pt-5">
        <!-- Sidebar -->
        @include('customer.partials.sidebar')

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card mt-0">
                <div class="card-header">
                    @if(Request::routeIs('customer.orders'))
                        <h4 class="card-title">Order History</h4>
                    @elseif(Request::routeIs('customer.invoices'))
                        <h4 class="card-title">Invoices</h4>
                    @elseif(Request::routeIs('customer.downloads'))
                        <h4 class="card-title">Digital Downloads</h4>
                    @elseif(Request::routeIs('customer.account.settings'))
                        <h4 class="card-title">Account Settings</h4>
                    @else
                        <h4 class="card-title">Customer Dashboard <span class="pull-right">{{ 'Welcome, ' }} {{ Auth::user()->name }}</span></h4>
                    @endif
                </div>
                <div class="card-body">
                    @if(Request::routeIs('customer.orders'))
                        @include('customer.partials.view-order-history')
                    @elseif(Request::routeIs('customer.invoices'))
                        @include('customer.partials.view-invoices')
                    @elseif(Request::routeIs('customer.downloads'))
                        @include('customer.partials.view-digital-downloads')
                    @elseif(Request::routeIs('customer.account.settings'))
                        <!-- Account Settings Form -->
                        @include('customer.partials.account-settings')
                    @else
                        <!-- Default Dashboard Widgets -->
                        @include('customer.partials.dashboard-widgets')
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
