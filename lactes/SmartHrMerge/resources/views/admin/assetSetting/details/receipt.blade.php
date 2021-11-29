@section('permission_modify','asset_modify')
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="col-12 row pr-0">
            <div class="card w-100">
                <form class="col-md-12" onsubmit="return false;" style="min-width: 800px" id="receive-setting" action="{{route('assetSetting.update',1)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row m-0">
                        <div class="card-header w-100">
                            <h4 class="m-0">{{ __('領収書初期値設定') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <table class="table-setting w-100">
                                    <tr>
                                        <td>
                                            {{ __('カレンダー検索範囲') }}
                                        </td>
                                        <td>
                                            <label class="w-30 m-0">
                                                <input type="radio" value="0" name="search_mode" {{$assetSetting->search_mode==0?"checked":""}}> 日単位
                                            </label>
                                            <label class="w-30 m-0">
                                                <input type="radio" value="1" name="search_mode" {{$assetSetting->search_mode==1?"checked":""}}> 月単位
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            {{ __('初期値使用') }}
                                        </td>
                                        <td>
                                            <input type="checkbox" name="use_init_val" value="1" id="use_init_val" class="check" {{$assetSetting->use_init_val==1?"checked":""}}>
                                            <label for="use_init_val" class="checktoggle">checkbox</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('初期値(会社宛情報)') }}
                                        </td>
                                        <td>
                                            <textarea name="company_info" cols="30" rows="5" type="text" class="form-control" required oninput="changeLength(this)">{{$assetSetting->company_info}}</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card-body">
                                <table class="table-setting w-100">
                                    <tr>
                                        <td>
                                            {{ __('但し内容：') }}
                                        </td>
                                        <td>
                                            <label class="w-100 m-0">
                                                <input class="w-100 form-control" type="text" name="document_end" value="{{$assetSetting->document_end}}">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('電子印鑑使用') }}
                                        </td>
                                        <td>
                                            <input type="checkbox" id="use_seal" value="1" name="use_seal" class="check" {{$assetSetting->use_seal==1?"checked":""}}>
                                            <label for="use_seal" class="checktoggle">checkbox</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('電子印鑑アプロード') }}
                                        </td>
                                        <td>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail img-thumbnail" style="width: 160px; height: 160px;">
                                                    <img class="electronicSeal" src="{{$assetSetting->seal_file?:url('assets/img/profiles/150-150.png')}}" style="width: 150px; height: 150px;">
                                                </div>
                                                <div>
                                                <span class="btn btn-file file-btns">
                                                    <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                    <input type="hidden"><input type="file" name="seal_file" onchange="showImg(this)">
                                                </span>
                                                </div>
                                                <div>正方形の画像をアップロードしてください。</div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @can($__env->yieldContent('permission_modify'))
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="button" onclick="saveSetting()">保存</button>
                    </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
