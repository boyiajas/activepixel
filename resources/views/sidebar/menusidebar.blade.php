<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!-- Dashboard -->
                <li class="{{ set_active(['home']) }}">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- User Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-user-plus"></i>
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['admin.users.index']) }}" href="{{ route('admin.users.index') }}">All Users</a></li>
                        <li><a class="{{ set_active(['admin.users.create']) }}" href="{{ route('admin.users.create') }}">Add User</a></li>
                        <li><a class="{{ set_active(['admin.users.log']) }}" href="{{ route('admin.users.log') }}">User Log Activity</a></li>
                    </ul>
                </li>

                <!-- Photo Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-image"></i>
                        <span>Photos</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['admin.photos.index']) }}" href="{{ route('admin.photos.index') }}">All Photos</a></li>
                        <li><a class="{{ set_active(['admin.photos.create']) }}" href="{{ route('admin.photos.create') }}">Add Photo</a></li>
                    </ul>
                </li>

                <!-- Category Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['admin.categories.index']) }}" href="{{ route('admin.categories.index') }}">All Categories</a></li>
                        <li><a class="{{ set_active(['admin.categories.create']) }}" href="{{ route('admin.categories.create') }}">Add Category</a></li>
                    </ul>
                </li>

                <!-- Event Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['admin.events.index']) }}" href="{{ route('admin.events.index') }}">All Events</a></li>
                        <li><a class="{{ set_active(['admin.events.create']) }}" href="{{ route('admin.events.create') }}">Add Event</a></li>
                    </ul>
                </li>

                <!-- Payment Method Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-credit-card"></i>
                        <span>Payment Methods</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['payment-methods.index']) }}" href="{{ route('payment-methods.index') }}">All Payment Methods</a></li>
                        <li><a class="{{ set_active(['payment-methods.create']) }}" href="{{ route('payment-methods.create') }}">Add Payment Method</a></li>
                    </ul>
                </li>

                <!-- Cart Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Cart</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['cart.index']) }}" href="{{ route('cart.index') }}">View Cart</a></li>
                    </ul>
                </li>

                <!-- Orders Management -->
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-file-invoice"></i>
                        <span>Orders</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['admin.orders.index']) }}" href="{{ route('admin.orders.index') }}">All Orders</a></li>
                    </ul>
                </li>

                <!-- Settings -->
                <li>
                    <a href="{{ route('settings') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
