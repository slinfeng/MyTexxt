<div class="mobile-footer">
    <a href="{{route('home')}}"><div class="menu-a"><embed class="svg-footer" src="{{ asset('assets/svg/home'.(request()->is('*home*')?'_active':'').'.svg') }}" type="image/svg+xml" />ホーム</div></a>
    <a href="{{route('getEmployeeInfo',['type'=>'idCard'])}}"><div class="menu-b"><embed class="svg-footer" src="{{ asset('assets/svg/id_card'.(request()->is('*idCard*')?'_active':'').'.svg') }}" type="image/svg+xml" />社員証</div></a>
    <a href="{{route('attendances.index')}}"><div class="menu-b"><embed class="svg-footer" src="{{ asset('assets/svg/working_time'.(request()->is('*attendance*')?'_active':'').'.svg') }}" type="image/svg+xml" />勤務表提出</div></a>
    <a href="{{route('getEmployeeInfo',['type'=>'user'])}}"><div class="menu-e"><embed class="svg-footer" src="{{ asset('assets/svg/account'.(request()->is('*user*')?'_active':'').'.svg') }}" type="image/svg+xml" />マイページ</div></a>
    <a href="{{route('leaves.index')}}"><div class="menu-d"><embed class="svg-footer" src="{{ asset('assets/svg/leave'.(request()->is('*leave*')?'_active':'').'.svg') }}" type="image/svg+xml" />休暇管理</div></a>
</div>
