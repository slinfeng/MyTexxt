<body>
<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Loader -->
    <div id="loader-wrapper">
        <div id="loader">
            <div class="loader-ellips">
                <span class="loader-ellips__dot"></span>
                <span class="loader-ellips__dot"></span>
                <span class="loader-ellips__dot"></span>
                <span class="loader-ellips__dot"></span>
            </div>
        </div>
    </div>
    <!-- /Loader -->
    <!-- Header -->
    <div class="header">

        <!-- Logo -->
        <div class="header-left">
            @if(!Agent::isMobile())
            <a href="{{ url('/home') }}" class="logo">
                <img src="{{ asset('assets/img/logoa.png') }}" data-src-a="{{ asset('assets/img/logoa.png') }}"
                     data-src-b="{{ asset('assets/img/logob.png') }}" alt="{{ config('app.name', 'SmartHr') }}">
            </a>
            @else
                @if(!request()->is('*home*') && !request()->is('*id_card*') && !request()->is('*attendance*') && !request()->is('*account*') && !request()->is('*leaves') && !request()->is('*employees') && !request()->is('*mobileIndex'))
                    <a href="javascript:history.go(-1);" style="position: absolute;left: 5px;"><img class="w-25p float-left" style="margin-top: 9px" src="{{ asset('assets/img/chevron-left-grey.png') }}"></a>
                @endif
                <span class="logo" style="padding-top: 4px;">@yield('page_title')</span>
            @endif
        </div>
        <!-- /Logo -->
        <a id="toggle_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
        <!-- Header Title -->
        <div class="page-title-box">
            <h3>@yield('page_title')</h3>
        </div>
        <!-- /Header Title -->
        @if(!Agent::isMobile())
        <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>

        <!-- Header Menu -->
        <ul class="nav user-menu">

            <!-- Search -->
            <li class="nav-item">
                <div style="line-height: 60px">
                    {{$gcon->company_name}}
                </div>
            </li>
            <!-- /Search -->

{{--            <!-- Flag -->--}}
{{--            <li class="nav-item dropdown has-arrow flag-nav">--}}
{{--                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">--}}
{{--                    <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="20"> <span>{{ __('English') }}</span>--}}
{{--                </a>--}}

{{--                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">--}}
{{--                    <img src="{{ asset('assets/img/flags/'.trans('menus.language-picker.flags.'.App::getLocale()).'.png') }}" alt="" height="20"> <span>{{ trans('menus.language-picker.language') }}</span>--}}
{{--                </a>--}}


{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    @foreach (array_keys(config('locale.languages')) as $lang)--}}
{{--                        @if ($lang != App::getLocale())--}}
{{--                            <a href="/lang/{{$lang}}" class="dropdown-item">--}}
{{--                                <img src="{{ asset('assets/img/flags/'.trans('menus.language-picker.flags.'.$lang).'.png') }}" alt="{{$lang}}" height="16"> {{trans('menus.language-picker.langs.'.$lang)}}--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}

{{--                    <a href="javascript:void(0);" class="dropdown-item">--}}
{{--                        <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="16"> English--}}
{{--                    </a>--}}
{{--                    <a href="javascript:void(0);" class="dropdown-item">--}}
{{--                        <img src="{{ asset('assets/img/flags/fr.png') }}" alt="" height="16"> French--}}
{{--                    </a>--}}
{{--                    <a href="javascript:void(0);" class="dropdown-item">--}}
{{--                        <img src="{{ asset('assets/img/flags/es.png') }}" alt="" height="16"> Spanish--}}
{{--                    </a>--}}
{{--                    <a href="javascript:void(0);" class="dropdown-item">--}}
{{--                        <img src="{{ asset('assets/img/flags/de.png') }}" alt="" height="16"> German--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <!-- /Flag -->--}}

            <!-- Notifications -->
{{--            <li class="nav-item dropdown">--}}
{{--                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">--}}
{{--                    <i class="fa fa-bell-o"></i> <span class="badge badge-pill">3</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu notifications">--}}
{{--                    <div class="topnav-dropdown-header">--}}
{{--                        <span class="notification-title">{{ __('Notifications') }}</span>--}}
{{--                        <a href="javascript:void(0)" class="clear-noti"> {{ __('Clear All') }} </a>--}}
{{--                    </div>--}}
{{--                    <div class="noti-content">--}}
{{--                        <ul class="notification-list">--}}
{{--                            <li class="notification-message">--}}
{{--                                <a href="activities.html">--}}
{{--                                    <div class="media">--}}
{{--												<span class="avatar">--}}
{{--													<img alt="" src="assets/img/profiles/avatar-02.jpg">--}}
{{--												</span>--}}
{{--                                        <div class="media-body">--}}
{{--                                            <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>--}}
{{--                                            <p class="noti-time"><span class="notification-time">4 mins ago</span></p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </a>--}}
{{--                            </li>--}}


{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <div class="topnav-dropdown-footer">--}}
{{--                        <a href="activities.html">{{ __('View all Notifications') }}</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
            <!-- /Notifications -->

            <!-- Message Notifications -->
{{--            <li class="nav-item dropdown">--}}
{{--                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">--}}
{{--                    <i class="fa fa-comment-o"></i> <span class="badge badge-pill">8</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu notifications">--}}
{{--                    <div class="topnav-dropdown-header">--}}
{{--                        <span class="notification-title">{{ __('Messages') }}</span>--}}
{{--                        <a href="javascript:void(0)" class="clear-noti"> {{ __('Clear All') }} </a>--}}
{{--                    </div>--}}
{{--                    <div class="noti-content">--}}
{{--                        <ul class="notification-list">--}}


{{--                            <li class="notification-message">--}}
{{--                                <a href="chat.html">--}}
{{--                                    <div class="list-item">--}}
{{--                                        <div class="list-left">--}}
{{--													<span class="avatar">--}}
{{--														<img alt="" src="assets/img/profiles/avatar-05.jpg">--}}
{{--													</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="list-body">--}}
{{--                                            <span class="message-author">Mike Litorus</span>--}}
{{--                                            <span class="message-time">3 Mar</span>--}}
{{--                                            <div class="clearfix"></div>--}}
{{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <div class="topnav-dropdown-footer">--}}
{{--                        <a href="chat.html">{{ __('View all Messages') }}</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
            <!-- /Message Notifications -->

            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img src="{{ asset('assets') }}/img/profiles/avatar-21.jpg" alt="">
							<span class="status online"></span></span>
                    <span>{{ isset(Auth::user()->name)?Auth::user()->name:"" }}</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('user.resetPassword') }}">パスワードを再設定</a>
{{--                    <a class="dropdown-item" href="{{route('settings.index')}}">{{ __('Settings') }}</a>--}}
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        <!-- /Header Menu -->
        <!-- Mobile Menu -->
{{--        <div class="mobile-user-menu">--}}
{{--            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>--}}
{{--            <div class="dropdown-menu dropdown-menu-right">--}}
{{--                <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('My Profile') }}</a>--}}
{{--                <a class="dropdown-item" href="{{route('settings.index')}}">{{ __('Settings') }}</a>--}}
{{--                <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                   onclick="event.preventDefault();--}}
{{--                                                     document.getElementById('logout-form').submit();">--}}
{{--                    {{ __('Logout') }}--}}
{{--                </a>--}}

{{--                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                    @csrf--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <embed class="svg-footer" src="{{ asset('assets/svg/help.svg') }}" type="image/svg+xml" />--}}
{{--        </div>--}}
        <!-- /Mobile Menu -->
        @endif
    </div>
    <!-- /Header -->

