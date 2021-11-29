<!-- Payroll Additions Table -->
<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
        <div class="card">
                    <div class="card-header w-100 row m-0">
                        <h4 class="m-0 col-6 p-0" style="line-height: 35px">{{ __('予約アイテム設定') }}</h4>

                        <div  class="col-auto float-right ml-auto row">
                            <div id="member_options" class="pr-5">
                                <input class="btn btn-primary" type="button" value="{{__('保存')}}" onclick="orderNumChange();">
                                <button class="btn btn-success member-edit" data-toggle="modal"
                                        data-target="#member_update_modal" onclick="editMember();"> {{ __('編集') }}</button>
                                <button class="btn btn-danger" data-toggle="modal"
                                        data-target="#member_delete_modal" onclick="deleteMethod('member');" > {{ __('削除') }}</button>
                            </div>
                            <a href="javascript:void(0)" data-href="" class="btn add-btn"
                               onclick="" data-toggle="modal" data-target="#member_add_modal">
                                <i class="fa fa-plus"></i> {{ __('新規作成') }}
                            </a>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="col-12 row pr-0">

                    </div>
                    <div class="w-100">
                        <table class="table table-striped custom-table mb-0 datatable col-xl-12 col-md-12"
                               id="member-table">
                            <thead>
                            <tr>
                                <th class="select-checkbox" onclick="selectAll(this)"></th>
                                <th class="text-center">{{__('表示順')}}</th>
                                <th class="text-left">{{__('アイテム登録')}}</th>
                                <th class="text-left">{{__('表示名')}}</th>
                                <th class="text-center">{{__('重複予約可')}}</th>
                                <th class="text-left">{{__('予約者名表示')}}</th>
                                <th class="text-left">{{__('制約')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>

<!-- Add Asset Type Modal -->
<div id="member_add_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('予約アイテムを追加') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="member_add_form" action="{{route('scheduleMember.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="order_num">{{ __('表示順') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="order_num" value="10" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('アイテム登録') }}<span class="text-danger">*</span></label>
                        <input type="hidden" name="user_id" value="0">
                        <input name="name" type="text" onchange="userChange(this,1);" maxlength="10" style="position: absolute;z-index: 99;top: 150px;left: 40px;width: 280px;border: none;font-size: 14px;color: #495057;">
                        <select class="w-100 select" style="min-width: 300px;" onchange="userChange(this,2);">
                            <option value="0">　</option>
                            @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ __('表示名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="display_name" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="reserve_type">{{ __('重複予約可　') }}</label>
                        <input type="checkbox" id="reserve_type" class="ml-2"
                               name="reserve_type" value="1" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="reserve_name_type">{{ __('予約者名表示') }}</label>
                        <input type="checkbox" id="reserve_name_type" class="ml-2"
                               name="reserve_name_type" value="1" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="constraint_type">{{ __('制約') }}<span class="text-danger">*</span>{{__('　　　　')}}</label>
                        <label><input type="radio" class="m-2"
                                      name="constraint_type" value="0" checked>自由予約</label>
                        <label><input type="radio" class="m-2"
                                      name="constraint_type" value="1" autofocus>管理員以外予約不可</label>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateSelector('#member_add_form','#member_add_modal','#member-table')">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Asset Type Modal -->

<!-- Edit Asset Type Modal -->
<div id="member_update_modal" class="modal custom-modal fade" role="dialog" data-update-url="{{route("scheduleMember.update", ':id')}}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('予約アイテム編集') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="member_update_form">
                    <div class="form-group">
                        <label for="order_num">{{ __('表示順') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="order_num" value="10" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('アイテム登録') }}<span class="text-danger">*</span></label>
                        <input type="hidden" name="user_id" value="0">
                        <input name="name" type="text" onchange="userChange(this,1);" maxlength="10" style="position: absolute;z-index: 99;top: 150px;left: 40px;width: 280px;border: none;font-size: 14px;color: #495057;">
                        <select class="w-100 select" style="min-width: 300px;" onchange="userChange(this,2);">
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ __('表示名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="display_name" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="reserve_type">{{ __('重複予約可　') }}</label>
                        <input type="checkbox" id="reserve_type" class="ml-2"
                               name="reserve_type" value="1" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="reserve_name_type">{{ __('予約者名表示') }}</label>
                        <input type="checkbox" id="reserve_name_type" class="ml-2"
                               name="reserve_name_type" value="1" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="constraint_type">{{ __('制約') }}<span class="text-danger">*</span>{{__('　　　　')}}</label>
                        <label><input type="radio" class="m-2"
                               name="constraint_type" value="0" autofocus>自由予約</label>
                        <label><input type="radio" class="m-2"
                               name="constraint_type" value="1" autofocus>管理員以外予約不可</label>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateSelector('#member_update_form','#member_update_modal','#member-table')">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Asset Type Modal -->

<!-- Delete Asset Type Modal -->
<div class="modal custom-modal fade" id="member_delete_modal" role="dialog" data-route="{{ route('scheduleMember.deleteMembers') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('予約アイテムを削除') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <form id="member_delete_form">@csrf</form>
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="member_delete_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
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

