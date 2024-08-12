<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ set_active(['home']) }}"> <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-user-plus"></i> 
                        <span> User Management </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['users/list/page']) }}" href="{{ route('users/list/page') }}">All User</a></li>
                        <li><a class="{{ set_active(['users/add/new']) }}" href="{{ route('users/add/new') }}">Add User</a></li>
                        <li><a class="{{ set_active(['users/add/new']) }}" href="{{ route('users/add/new') }}">User Log Activity</a></li>
                    </ul>
                </li>
                <li> <a href="{{ route('settings') }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span></a> 
                </li>
            </ul>
        </div>
    </div>
</div>