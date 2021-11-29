<div class="mobile-footer">
    <a href="{{route('audit.home')}}"><div class="menu-a"><embed class="svg-footer" src="{{ asset('assets/svg/home'.(request()->is('*home*')?'_active':'').'.svg') }}" type="image/svg+xml" />ホーム</div></a>
    <a href="{{route('audit.employees')}}"><div class="menu-b"><embed class="svg-footer" src="{{ asset('assets/svg/id_card'.(request()->is('*employees*')?'_active':'').'.svg') }}" type="image/svg+xml" />社員管理</div></a>
    <a href="{{route('audit.attendance')}}"><div class="menu-b"><embed class="svg-footer" src="{{ asset('assets/svg/working_time'.(request()->is('*attendance*')?'_active':'').'.svg') }}" type="image/svg+xml" />勤務表管理</div></a>
    <a href="{{route('audit.user')}}"><div class="menu-e"><embed class="svg-footer" src="{{ asset('assets/svg/account'.(request()->is('*user*')?'_active':'').'.svg') }}" type="image/svg+xml" />アカウント</div></a>
    <a href="{{route('audit.leaves')}}"><div class="menu-d"><embed class="svg-footer" src="{{ asset('assets/svg/leave'.(request()->is('*leave*')?'_active':'').'.svg') }}" type="image/svg+xml" />休暇管理</div></a>
</div>
