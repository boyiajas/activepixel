<!-- resources/views/partials/sidebar.blade.php -->

<div class="col-md-3">
    <div class="list-group">
        <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action {{ request()->is('customer/dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action {{ request()->is('customer/orders') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> Order History
        </a>

       <!--  <a href="{{ route('customer.invoices') }}" class="list-group-item list-group-item-action {{ request()->is('customer/invoices') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> Invoices
        </a> -->

        <a href="{{ route('customer.downloads') }}" class="list-group-item list-group-item-action {{ request()->is('customer/downloads') ? 'active' : '' }}">
            <i class="fas fa-download"></i> Digital Downloads
        </a>

        <a href="{{ route('customer.account.settings') }}" class="list-group-item list-group-item-action {{ request()->is('customer/account/settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Account Settings
        </a>

        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>
