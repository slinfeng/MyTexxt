<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{route('home')}}"><i class="la la-home"></i> <span>{{ __('Back to Home') }}</span></a>
                </li>
                <li class="menu-title">{{ __('Settings') }}</li>
                <li class="{{ (request()->is('settings')) ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}"><i class="la la-building"></i> <span>{{ __('Site Settings') }}</span></a>
                </li>
                {{--                <li>--}}
                {{--                    <a href="localization.html"><i class="la la-clock-o"></i> <span>Localization</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="theme-settings.html"><i class="la la-photo"></i> <span>Theme Settings</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="roles-permissions.html"><i class="la la-key"></i> <span>Roles & Permissions</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="email-settings.html"><i class="la la-at"></i> <span>Email Settings</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="invoice-settings.html"><i class="la la-pencil-square"></i> <span>Invoice Settings</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="salary-settings.html"><i class="la la-money"></i> <span>Salary Settings</span></a>--}}
                {{--                </li>--}}
                {{--                <li>--}}
                {{--                    <a href="notifications-settings.html"><i class="la la-globe"></i> <span>{{ __('Notifications') }}</span></a>--}}
                {{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="change-password.html"><i class="la la-lock"></i> <span>{{ __('Change Password') }}</span></a>--}}
{{--                </li>--}}

                <li class="{{ (request()->is('activity')) ? 'active' : '' }}">
                    <a href="{{route('activity')}}"><i class="la la-cogs"></i> <span>{{ __('Activity Logs') }}</span></a>
                </li>
                <li class="{{ (request()->is('sessions')) ? 'active' : '' }}">
                    <a href="{{route('sessions.index')}}"><i class="la la-cogs"></i> <span>{{ __('Sessions') }}</span></a>
                </li>
                <li class="{{ (request()->is('email-templates')) ? 'active' : '' }}">
                    <a href="{{route('email-templates.index')}}"><i class="la la-cogs"></i> <span>{{ __('Email Templates') }}</span></a>
                </li>

                <li class="{{ (request()->is('roles')) ? 'active' : '' }}">
                    <a href="{{route('roles.index')}}"><i class="la la-cogs"></i> <span>{{ __('Roles') }}</span></a>
                </li>

                <li class="{{ (request()->is('leavetypes')) ? 'active' : '' }}">
                    <a href="{{route('leavetypes.index')}}"><i class="la la-cogs"></i> <span>{{ __('Leave Type') }}</span></a>
                </li>
                <li class="{{ (request()->is('assettypes')) ? 'active' : '' }}">
                    <a href="{{route('assettypes.index')}}"><i class="la  la-cogs"></i> <span>{{ __('Asset Type') }}</span></a>
                </li>
{{--                <li class="{{ (request()->is('departments')) ? 'active' : '' }}">--}}
{{--                    <a href="{{route('departments.index')}}"><i class="la la-cogs"></i> <span>{{ __('Departments') }}</span></a>--}}
{{--                </li>--}}
                <li class="{{ (request()->is('contracttypes')) ? 'active' : '' }}">
                    <a href="{{route('contracttypes.index')}}"><i class="la la-cogs"></i> <span>{{ __('Contract Type') }}</span></a>
                </li>
                <li class="{{ (request()->is('ourpositiontypes')) ? 'active' : '' }}">
                    <a href="{{route('ourpositiontypes.index')}}"><i class="la la-cogs"></i> <span>{{ __('Our position Type') }}</span></a>
                </li>
                <li class="{{ (request()->is('attendancetimetypes')) ? 'active' : '' }}">
                    <a href="{{route('attendancetimetypes.index')}}"><i class="la la-cogs"></i> <span>{{ __('Attendance Time Type') }}</span></a>
                </li>
                <li class="{{ (request()->is('overtimetypes')) ? 'active' : '' }}">
                    <a href="{{route('overtimetypes.index')}}"><i class="la la-cogs"></i> <span>{{ __('Over Time Type') }}</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
