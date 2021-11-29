<div class="company-info">
    <ul class="w-100">
        <li class="row hide_label" style="padding-right: 25px;margin: 0">
            <div class="row col-auto ml-auto"
                 style="padding: 0;margin-left:0!important;margin-right: auto">
                <span>初期値使用　</span>
                <div class="status-toggle">
                    <input type="checkbox" id="switch_annual1" name="use_init_val" class="check"
                           {{$__env->yieldContent('page_type')=='edit'?'':($__env->yieldContent('use_init_val')=='1'?'checked':'')}} onclick="initCompanyInfoAndRemark()">
                    <label for="switch_annual1" class="checktoggle">checkbox</label>
                </div>
            </div>
                @if($__env->yieldContent('isLetter'))
                    <div class="row col-auto ml-auto"
                         style="padding: 0;margin-left:0!important;margin-right: auto">
                        <span>枠付け　</span>
                        <div class="status-toggle">
                            <input type="checkbox" id="switch_annual3" name="use_seal" class="check"
                                   onclick="framedChecked(this)" {{$__env->yieldContent('framed_mark')==1?'checked':''}}>
                            <label for="switch_annual3" class="checktoggle">checkbox</label>
                        </div>
                    </div>
                @else
                <div class="row col-auto ml-auto p-0">
                    <span>電子印鑑使用　</span>
                    <div class="status-toggle">
                        <input type="checkbox" id="switch_annual2" name="use_seal" class="check"
                               {{$__env->yieldContent('use_seal')==1?'checked':''}} onclick="showSeal(this)">
                        <label for="switch_annual2" class="checktoggle">checkbox</label>
                    </div>
                </div>
            @endif
        </li>
        <li class="position-relative">
            <textarea oninput="changeLength(this)" name="company_info" class="w-100" rows="5"
                      {{$__env->yieldContent('page_type')=='edit'?'':($__env->yieldContent('use_init_val')=='1'?'readonly':'')}} data-initial="{{$requestSettingGlobal['company_info']}}">@yield('company_info')</textarea>
            <img class="electronicSeal" src="{{$requestSettingGlobal['seal_file']}}">
        </li>
    </ul>
</div>
