<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><i class="la la-home"></i> <span>{{ __('Dashboard') }}</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
