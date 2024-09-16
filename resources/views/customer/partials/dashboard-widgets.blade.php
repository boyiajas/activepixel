<!-- resources/views/partials/dashboard-widgets.blade.php -->

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

   <!--  <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
                <h5 class="card-title mt-2">Invoices</h5>
                <p class="card-text">Manage your invoices.</p>
                <a href="{{ route('customer.invoices') }}" class="btn btn-success">View Invoices</a>
            </div>
        </div>
    </div>
 -->
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

<!-- Additional Widgets -->
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
