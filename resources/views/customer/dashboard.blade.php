@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="row pt-5">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ Auth::user()->profile_picture_url }}" class="rounded-circle img-thumbnail" alt="User Profile Picture" style="max-width: 150px;">
                    <h4 class="mt-3">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item"><a href="{{ route('customer.orders') }}">Order History</a></li>
                    <li class="list-group-item"><a href="{{ route('customer.invoices') }}">Invoices</a></li>
                    <li class="list-group-item"><a href="{{ route('customer.downloads') }}">Digital Downloads</a></li>
                    <li class="list-group-item"><a href="{{ route('customer.account.settings') }}">Account Settings</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Dashboard</h4>
                </div>
                <div class="card-body">
                    <!-- Dashboard Widgets -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                                    <h5 class="card-title mt-2">Order History</h5>
                                    <p class="card-text">View your past orders.</p>
                                    <a href="{{ route('customer.orders') }}" class="btn btn-primary">View Orders</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
                                    <h5 class="card-title mt-2">Invoices</h5>
                                    <p class="card-text">Manage your invoices.</p>
                                    <a href="{{ route('customer.invoices') }}" class="btn btn-success">View Invoices</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-download fa-2x text-warning"></i>
                                    <h5 class="card-title mt-2">Digital Downloads</h5>
                                    <p class="card-text">Access your purchased photos.</p>
                                    <a href="{{ route('customer.downloads') }}" class="btn btn-warning">Download Photos</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- More Widgets -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-cog fa-2x text-info"></i>
                                    <h5 class="card-title mt-2">Account Settings</h5>
                                    <p class="card-text">Manage your account settings.</p>
                                    <a href="{{ route('customer.account.settings') }}" class="btn btn-info">Manage Settings</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-sign-out-alt fa-2x text-danger"></i>
                                    <h5 class="card-title mt-2">Logout</h5>
                                    <p class="card-text">Sign out of your account.</p>
                                    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
