@if(isset($l_sidebar_setting) && $l_sidebar_setting)
    @include('layouts.sidebars.settings_sidebar')
@else
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    @cannot('invoice_self_modify')
                    <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
                        <a href="{{ route('home') }}"><i class="la la-home"></i> <span>{{ __('Dashboard') }}</span></a>
                    </li>
                    @endcan
                    @canany(['client_view','client_modify','estimate_view','estimate_modify','expense_view','expense_modify','orderconfirm_view','orderconfirm_modify','invoice_view','invoice_modify','invoice_self_modify','transmittal_view','transmittal_modify','transmittal_self_modify','requestsetting_view','requestsetting_modify'])
                    <li class="submenu">
                        <a href="#" class="{{$info['requestTotal']!=0?'noti-dot':''}} subdrop"> <i class="la la-files-o"></i> <span> {{ __('Accounts') }} </span> <span class="menu-arrow"></span></a>
                        <ul style="display: block;">
                                @canany(['client_view','client_modify'])
                            <li class="{{ (request()->is('client*')) ? 'active' : '' }}"><a href="{{ route('clients.index') }}"><span>{{__('取')}}</span><span>{{ __('Clients') }}</span></a></li>
                                @endcanany
                                @canany(['estimate_view','estimate_modify'])
                            <li class="{{ (request()->is('estimate*')) ? 'active' : '' }}"><a href="{{ route('estimates.index') }}"><span>{{__('見')}}</span><span>{{ __('Estimates') }}</span></a></li>
                                @endcanany
                                @canany(['expense_view','expense_modify','invoice_self_modify'])
                            <li class="{{ (request()->is('expense*')) ? 'active' : '' }}"><a href="{{ route('expense.index') }}"><span>{{__('注')}}</span><span>{{ __('Payments') }}</span><span class="badge badge-pill bg-primary float-right">{{$info['order']?:''}}</span></a></li>
                                @endcanany
                                @canany(['orderconfirm_view','orderconfirm_modify'])
                            <li class="{{ (request()->is('confirmation*')) ? 'active' : '' }}"><a href="{{ route('confirmations.index') }}"><span>{{__('注')}}</span><span>{{ __('Expenses') }}</span></a></li>
                                @endcanany
                                @canany(['invoice_view','invoice_modify','invoice_self_modify'])
                            <li class="{{ (request()->is('invoice*')) ? 'active' : '' }}"><a href="{{ route('invoice.index') }}"><span>{{__('請')}}</span><span>{{ __('Invoices') }}</span><span class="badge badge-pill bg-primary float-right">{{$info['invoice']?:''}}</span></a></li>
                                @endcanany
                                @canany(['transmittal_view','transmittal_modify','transmittal_self_modify'])
                            <li class="{{ (request()->is('letteroftransmittal*')) ? 'active' : '' }}"><a href="{{ route('letteroftransmittal.index') }}"><span>{{__('送')}}</span><span>{{ __('LetterOfTransmittal') }}</span></a></li>
                                @endcanany
                                @canany(['requestsetting_view','requestsetting_modify','invoice_self_modify'])
                            <li class="{{ (request()->is('requestSetting*')) ? 'active' : '' }}"><a href="{{ route('requestSetting.index') }}"><span>{{__('設')}}</span><span>{{ __('初期設定') }}</span></a></li>
                                @endcanany
                        </ul>
                    </li>
                    @endcanany
                    @canany(['employee_view','employee_modify','attendance_view','attendance_modify','leave_view','leave_modify','hrsetting_view','hrsetting_modify'])
                    <li class="submenu">
                        <a href="#" class="{{$info['HrTotal']!=0?'noti-dot':''}} subdrop"><i class="la la-users"></i> <span> {{ __('HR') }}</span> <span class="menu-arrow"></span></a>
                        <ul style="display: block;">
                            @canany(['employee_view','employee_modify'])
                            <li class="{{ (request()->is('employees*')) ? 'active' : '' }}"><a href="{{ route('employees.index') }}"><span>{{__('社')}}</span><span>{{ __('社員管理') }}</span> <span class="badge badge-pill bg-primary float-right">{{$info['employee']?:''}}</span></a></li>
                            @endcanany
                            @canany(['attendance_view','attendance_modify'])
                            <li class="{{ (request()->is('attendance*')) ? 'active' : '' }}"><a href="{{ route('attendances.index') }}"><span>{{__('勤')}}</span><span>{{ __('勤務管理') }}</span> <span class="badge badge-pill bg-primary float-right">{{$info['attendance']?:''}}</span></a></li>
                            @endcanany
                            @canany(['leave_view','leave_modify'])
                            <li class="{{ (request()->is('leave*')) ? 'active' : '' }}"><a href="{{ route('leaves.index') }}"><span>{{__('休')}}</span><span>{{ __('休暇管理') }}</span> <span class="badge badge-pill bg-primary float-right">{{$info['leave']?:''}}</span></a></li>
                            @endcanany
                            @canany(['hrsetting_view','hrsetting_modify'])
                            <li class="{{ (request()->is('HrSetting*')) ? 'active' : '' }}"><a href="{{ route('HrSetting.index') }}"><span>{{__('設')}}</span><span>{{ __('初期設定') }}</span></a></li>
{{--                            <li class="{{ (request()->is('overtimerequests')) ? 'active' : '' }}"><a href="{{ route('overtimerequests.index') }}"><span>{{__('残')}}</span><span>{{ __('OverTime Requests') }}</span></a></li>--}}
                            @endcanany
                        </ul>
                    </li>
                    @endcanany
                    @canany(['asset_view','asset_modify','receipt_view','receipt_modify','receipt_self_modify','assetsetting_view','assetsetting_modify'])
                    <li class="submenu">
                        <a href="#" class=" subdrop"><i class="la la-money"></i> <span>{{ __('Company Assets') }}</span> <span class="menu-arrow"></span></a>
                        <ul style="display: block;">
                            @canany(['asset_view','asset_modify'])
                            <li class="{{ (request()->is('*companyassets*')) ? 'active' : '' }}"><a href="{{ route('companyassets.index') }}"><span>{{__('設')}}</span><span>{{ __('設備管理') }}</span></a></li>
                            @endcanany
                            @canany(['receipt_view','receipt_modify','receipt_self_modify'])
                            <li class="{{ (request()->is('*receipt*')) ? 'active' : '' }}"><a href="{{ route('receipt.index') }}"><span>{{__('領')}}</span><span>{{ __('領収書') }}</span></a></li>
                            @endcanany
                            @canany(['assetsetting_view','assetsetting_modify'])
                            <li class="{{ (request()->is('assetSetting*')) ? 'active' : '' }}"><a href="{{ route('assetSetting.index') }}"><span>{{__('設')}}</span><span>{{ __('初期設定') }}</span></a></li>
                            @endcanany
                        </ul>
                    </li>
                    @endcanany
                    @cannot('invoice_self_modify')
                    <li class="submenu">
                        <a href="#" class=" subdrop"><i class="la la-calendar"></i> <span>{{ __('スケジュール') }}</span> <span class="menu-arrow"></span></a>
{{--                        <a target="_blank" href="https://www.r326.com/"><i class="la la-calendar"></i> <span>{{ __('スケジュール') }}</span></a>--}}
                        <ul style="display: block;">
{{--                            <li class="{{ (request()->is('*schedules*')) ? 'active' : '' }}"><a href="{{ route('schedules.index') }}"><span>{{__('ス')}}</span><span>{{ __('スケジュール管理') }}</span></a></li>--}}
                            @if(isset($info['groups']))
                                @foreach($info['groups'] as $group)
                                    <li class="{{ (request()->is('*schedules*'.$group->id.'*')) ? 'active' : '' }} schedule-group" id="schedules{{$group->id}}"><a href="{{ route('schedules.group',['id'=>$group->id]) }}"><span>{{mb_substr($group->name,0,1,'utf-8')}}</span><span>{{ $group->name }}</span></a></li>
                                @endforeach
                            @endif()

                            <li class="{{ (request()->is('scheduleSetting*')) ? 'active' : '' }}"><a href="{{ route('scheduleSetting.index') }}"><span>{{__('設')}}</span><span>{{ __('初期設定') }}</span></a></li>
                        </ul>
                    </li>
                    @endcannot
                    @canany(['user_view','user_modify'])
                    <li class="{{ (request()->is('users')) ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}"><i class="la la-user-plus"></i> <span>{{ __('Users') }}</span></a>
                    </li>
                    @endcanany
                    @canany(['setting_view','setting_modify'])
                    <li class="{{ (request()->is('settings')) ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}"><i class="la la-cog"></i> <span>{{ __('Settings') }}</span></a>
                    </li>
                    @endcanany
{{--                    <li class="submenu">--}}
{{--                        <a href="#" class="noti-dot subdrop"><i class="la la-money"></i> <span>{{ __('営業管理') }}</span> <span class="menu-arrow"></span></a>--}}
{{--                        <ul style="display: block;">--}}
{{--                            <li class="{{ (request()->is('*asset*')) ? 'active' : '' }}"><a href="{{ route('companyassets.index') }}"><span>{{__('設')}}</span><span>{{ __('設備管理') }}</span></a></li>--}}
{{--                            <li class="{{ (request()->is('*asset*')) ? 'active' : '' }}"><a href="{{ route('companyassets.index') }}"><span>{{__('設')}}</span><span>{{ __('設備管理') }}</span></a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}

                </ul>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->
@endif




