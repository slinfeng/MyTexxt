<!-- Payroll Additions Table -->
<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
            <div class="card">
                <form action="{{route('scheduleSetting.update',3)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row m-0">
                        <div class="card-header w-100">
                            <h4 class="m-0">{{ __('パレット') }}</h4>
                        </div>
                        <div class="row">
                            <div class="card-body">
                                <table class="w-100 table-color">
                                    <tr>
                                        <td>
                                            <div class="">
                                                <label>
                                                    <input type="radio" class="" name="palette_type" value="0" {{$scheduleSetting->palette_type==0?'checked':''}}> 標準パレットから選択
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row" style="padding-bottom: 20px">
                                                <div class="col-5">
                                                    <label>
                                                        <input type="radio" class="" name="palette_type" value="1" {{$scheduleSetting->palette_type==1?'checked':''}}>  すべて同じ色で表示：
                                                    </label>
                                                </div>
                                                @if(empty($scheduleSetting->color_id) || $scheduleSetting->color_id==0)
                                                    <div><button class="btn color-show" onclick="return false" style="background-color: #f13b09;"></button></div> <div class="col-3 color-name" style="padding-left: 5px;">#f13b09</div>
                                                @else
                                                    @foreach ($scheduleColorTypes as $scheduleColorType)
                                                        @if($scheduleSetting->color_id==$scheduleColorType->id)
                                                            <div><button class="btn color-show" onclick="return false" style="background-color: {{$scheduleColorType->css_name}};"></button></div> <div class="col-3 color-name" style="padding-left: 5px;">{{$scheduleColorType->css_name}}</div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            @foreach ($scheduleColorTypes as $scheduleColorType)
                                            <button class="btn" onclick="changeColor(this);return false" data-id="{{$scheduleColorType->id}}" data-color="{{$scheduleColorType->css_name}}" style="background-color:{{$scheduleColorType->css_name}};"></button>
                                            @endforeach
                                            <input type="hidden" value="{{$scheduleSetting->color_id}}" name="color_id">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="">
                                                <label>
                                                    <input type="radio" class="" name="palette_type" value="2"{{$scheduleSetting->palette_type==2?'checked':''}}>  なし「種別」以外から色を選択させたくない場合に選択
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="button" onclick="scheduleSubmit(this)">保存</button>
                    </div>
                </form>
            </div>

        <div class="card">
            <div class="row m-0">
                <div class="row card-header w-100 m-0">
                    <h4 class="m-0 col-6 p-0" style="line-height: 35px">{{ __('種別') }}</h4>
                    <div  class="col-auto float-right ml-auto row">
                        <div id="color_options" class="pr-5">
                        <input class="btn btn-primary btn-apply-request" type="button" value="{{__('保存')}}" onclick="orderNumChange();">
                        <button class="btn-option btn btn-success color-edit" data-toggle="modal"
                                data-target="#color_update_modal" onclick="editColor();"> {{ __('編集') }}</button>
                        <button class="btn-option btn btn-danger" data-toggle="modal"
                                data-target="#color_delete_modal" onclick="deleteMethod('color');" > {{ __('削除') }}</button>
                        </div>
                        <a href="javascript:void(0)" data-href="" class="btn add-btn"
                           onclick="" data-toggle="modal" data-target="#color_add_modal">
                            <i class="fa fa-plus"></i> {{ __('新規作成') }}
                        </a>

                    </div>

                </div>
                <div class="card-body">
                    <div >「会議は青」「外出は緑」などのように、色を内容で分けたい場合は、ここで「種別」を登録しておきます。凡例から色を選択できるようになります。</div>
                    <table id="color-table" class="table table-striped table-fixed">
                        <thead>
                            <th class="select-checkbox" onclick="selectAll(this)"></th>
                            <th>表示順</th>
                            <th>名称</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Asset Type Modal -->
<div id="color_add_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('色と種別を追加') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="color_add_form" action="{{route('scheduleColor.store')}}" method="POST">
                    <div class="form-group">
                        <label for="order_num">{{ __('表示順') }}<span class="text-danger">*</span></label>
                        <input class="form-control number" type="text"
                               name="order_num" value="10" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('名称') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="name" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="color_id">{{ __('色') }}<span class="text-danger">*</span></label>
                        <input name="color_id" type="hidden" value="1">
                        <div class="row ml-0 mb-3"><button class="btn color-show" onclick="return false" style="background-color: #f13b09;"></button>
                        <div class="col-3 color-name" style="padding-left: 5px;">#f13b09</div></div>
                        @foreach ($scheduleColorTypes as $scheduleColorType)
                            <button class="btn" onclick="changeColor(this);return false" data-id="{{$scheduleColorType->id}}" data-color="{{$scheduleColorType->css_name}}" style="background-color:{{$scheduleColorType->css_name}};"></button>
                        @endforeach
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateSelector('#color_add_form','#color_add_modal','#color-table')">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Asset Type Modal -->

<!-- Edit Asset Type Modal -->
<div id="color_update_modal" class="modal custom-modal fade" role="dialog" data-update-url="{{route("scheduleColor.update", ':id')}}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('色と種別を編集') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="color_update_form">
                    <div class="form-group">
                        <label for="order_num">{{ __('表示順') }}<span class="text-danger">*</span></label>
                        <input class="form-control number" type="text"
                               name="order_num" value="10" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('名称') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="name" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="color_id">{{ __('色') }}<span class="text-danger">*</span></label>
                        <input name="color_id" type="hidden" value="1">
                        <div class="row ml-0 mb-3">
                            <button class="btn color-show" onclick="return false" style="background-color: #f13b09;"></button>
                            <div class="col-3 color-name" style="padding-left: 5px;">#f13b09</div>
                        </div>
                        @foreach ($scheduleColorTypes as $scheduleColorType)
                            <button class="btn" onclick="changeColor(this);return false" data-id="{{$scheduleColorType->id}}" data-color="{{$scheduleColorType->css_name}}" style="background-color:{{$scheduleColorType->css_name}};"></button>
                        @endforeach
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateSelector('#color_update_form','#color_update_modal','#color-table')">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Asset Type Modal -->

<!-- Delete Asset Type Modal -->
<div class="modal custom-modal fade" id="color_delete_modal" role="dialog" data-route="{{ route('scheduleColor.deleteColors') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('色と種別を削除') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <form id="color_delete_form">@csrf</form>
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="color_delete_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Asset Type Modal -->
