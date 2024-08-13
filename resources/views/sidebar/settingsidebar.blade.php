<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home') }}"><i class="fe fe-home"></i> <span>Back to Home</span></a>
                </li>
                <li class="{{ set_active(['home']) }}"> <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
               
                <li class="active">
                    <a href="settings.html"><i class="fe fe-vector"></i> <span>Company Settings</span></a>
                </li>
                <li>
                    <a href="localization.html"><i class="fe fe-vector"></i> <span>Localization</span></a>
                </li>
                <li>
                    <a href="theme-settings.html"><i class="fe fe-warning"></i> <span>Theme Settings</span></a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}"><i class="fe fe-vector {{ set_active(['roles.index']) }}"></i> <span>Roles &
                            Permissions</span></a><!-- roles-permissions.html -->
                </li>
                <li>
                    <a href="email-settings.html"><i class="fe fe-warning"></i> <span>Email Settings</span></a>
                </li>
                <li>
                    <a href="invoice-settings.html"><i class="fe fe-vector"></i> <span>Invoice
                            Settings</span></a>
                </li>
                <li>
                    <a href="salary-settings.html"><i class="fe fe-vector"></i> <span>Salary Settings</span></a>
                </li>
                <li>
                    <a href="notifications-settings.html"><i class="fe fe-warning"></i>
                        <span>Notifications</span></a>
                </li>
                <li>
                    <a href="change-password.html"><i class="fe fe-vector"></i> <span>Change Password</span></a>
                </li>
                <li>
                    <a href="leave-type.html"><i class="fe fe-warning"></i> <span>Leave Type</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>