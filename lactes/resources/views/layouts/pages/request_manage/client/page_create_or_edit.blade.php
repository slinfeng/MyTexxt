@section('permission_modify','client_modify')
<div class="modal-header">
    @can($__env->yieldContent('permission_modify'))
        <h5 class="modal-title">取引先@yield('action_name')画面</h5>
    @else
        <h5 class="modal-title">取引先詳細情報</h5>
    @endcan
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="position-absolute" style="right: 30px;top: 0px">
        取引先番号：<span>@yield('id')</span>
    </div>
    <form method="POST" id='add_edit_form' data-route="@yield('route_save')">
        @csrf
        @yield('method')
        <div class="form-row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="client_name">{{ __('Client Name') }}<span class="text-danger">*</span></label>
                    <input class="form-control"
                           value="@yield('client_name')"
                           type="text" name="client_name" autofocus required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="client_abbreviation">{{ __('Client Abbreviation') }}</label>
                    <input class="form-control"
                           value="@yield('client_abbreviation')"
                           type="text" name="client_abbreviation" autofocus required maxlength="10">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cooperation_start">{{ __('Cooperation Start') }}<span
                            class="text-danger">*</span></label>
                    <input class="form-control datepicker" autocomplete="off"
                           value="@yield('cooperation_start')"
                           type="text" name="cooperation_start" autofocus required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Our Position') }}<span class="text-danger">*</span></label>
                    <select class="select form-control" name="our_role" autofocus required>
                        @foreach ($ourpositiontypes as $ourpositiontype)
                            <option value="{{$ourpositiontype['id']}}"
                                @if($__env->yieldContent('our_role') == $ourpositiontype->id)selected @endif>{{$ourpositiontype['our_position_type_abbr_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_type">{{ __('Client Url') }}</label>
                    <input class="form-control"
                           value="@yield('url')"
                           onchange="value=value.replace(/[^0-9a-zA-Z@./:-]/g,'')" type="text" name="url" autofocus>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Client Mail') }}</label>
                    <input class="form-control"
                           value="@yield('mail')"
                           onchange="value=value.replace(/[^0-9a-zA-Z@.-]/g,'')" type="text" name="mail" autofocus>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_type">{{ __('Client Tel') }}</label>
                    <input class="form-control number" value="@yield('tel')" type="text" name="tel" autofocus maxlength="15">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Client Fax') }}</label>
                    <input class="form-control number" value="@yield('fax')" type="text" name="fax" autofocus maxlength="15">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="contract_type">{{ __('Post Code') }}</label>
                    <input class="form-control number"
                           value="@yield('post_code')" maxlength="7"
                           onfocus="onPostFocus(this)"
                           onblur="addPostMark(this)" type="text" name="post_code" autofocus>
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label for="client_address">{{ __('Client Address') }}</label>
                    <textarea name="client_address" style="height: 44px"
                              class="form-control" autofocus>@yield('client_address')</textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="memo">{{ __('Memo') }}</label>
            <textarea name="memo" cols="30" rows="5"
                      class="form-control" autofocus>@yield('memo')</textarea>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <div class="form-group">
                    税金計算方式：
                    <label style="margin-right: 5em"><input type="radio" name="calc_type" @if($__env->yieldContent('calc_type') == 0)checked @endif value="0"> 四捨五入</label>
                    <label><input type="radio" name="calc_type" @if($__env->yieldContent('calc_type') == 1)checked @endif value="1"> 切り捨て</label>
                </div>
            </div>
        </div>
        @if($boo>0)
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        書類提出の仕方：
                        <label style="margin-right: 5em"><input type="checkbox" name="document_format[]" @if($client->document_format != 1)checked @endif value="0"> 画面入力</label>
                        <label><input type="checkbox" name="document_format[]" @if($client->document_format != 0)checked @endif value="1"> アップロード</label>
                    </div>
                </div>
            </div>
        @endif
        @can($__env->yieldContent('permission_modify'))
        <div class="submit-section">
            <button class="btn btn-primary" style="font-size:18px;width: 22%;height:50px;background-color:#FF9B44;display: inline-block;"
                    type="button" onclick="saveAddOrEdit()">{{ __('Save') }}</button>
        </div>
        @endcan
    </form>
</div>
