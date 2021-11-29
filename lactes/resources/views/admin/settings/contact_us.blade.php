<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<form id="contact-setting-form" autocomplete="off">
    <div class="card" id="email-settings-card">
        <div class="card-header">
            <h4>{{__('Company Info')}}</h4>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card-body">
                    <div class="form-group row align-items-center">
                        <label for="company_name" class="form-control-label col-sm-3 text-md-right">{{__('Company Name')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="company_name" class="form-control" id="company_name" value="{{$master['company_name'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Company Short Name')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="post_code" class="form-control-label col-sm-3 text-md-right">{{__('POSTCODE')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="post_code" onfocus="onPostFocus(this)" onchange="value=value.replace(/[^0-9]/g,'')"
                                   onblur="addPostMark(this)" class="form-control" id="post_code" value="{{$master['post_code'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Company Short Name')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="address" class="form-control-label col-sm-3 text-md-right">{{__('Address')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <textarea rows="2" name="address" class="form-control" id="address" placeholder="{{__('Enter text here')}}">{{$master['address'] ?? ''}}</textarea>
                            {{--                    <div class="form-text text-muted">{{__('Address')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="representative_name" class="form-control-label col-sm-3 text-md-right">{{__('Representative name')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="representative_name" class="form-control" id="representative_name" value="{{$master['representative_name'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Company Short Name')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="phone_no" class="form-control-label col-sm-3 text-md-right">{{__('Phone No.')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="phone_no" class="form-control" id="phone_no" onchange="value=value.replace(/[^0-9]/g,'')" value="{{$master['phone_no'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Phone No.')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="fax" class="form-control-label col-sm-3 text-md-right">{{__('FAX')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="fax" class="form-control" id="fax" onchange="value=value.replace(/[^0-9]/g,'')" value="{{$master['fax'] ?? ''}}" required>
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="fax" class="form-control-label col-sm-3 text-md-right">{{__('決算月')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <div class="input-group">
                                <input type="text" name="closing_month" class="form-control text-right" value="{{$master['closing_month'] ?? ''}}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">月</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-6">
                <div class="card-body">

                    <div class="form-group row align-items-center">
                        <label for="name" class="form-control-label col-sm-3 text-md-right">{{__('Email')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="email" onchange="value=value.replace(/[^0-9a-zA-Z@.-]/g,'')"  class="form-control" id="email" value="{{$master['email'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Email')}}</div>--}}
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="url" class="form-control-label col-sm-3 text-md-right">{{__('URL')}}</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="url" onchange="value=value.replace(/[^0-9a-zA-Z@./:-]/g,'')" class="form-control" id="url" value="{{$master['url'] ?? ''}}" required>
                            {{--                    <div class="form-text text-muted">{{__('Email')}}</div>--}}
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row align-items-center">
                        <label class="form-control-label text-md-right">{{__('携帯側社員証の使用:')}}</label>
                    </div>
                            <div class="form-group row align-items-center">
                                <label for="logo" class="form-control-label col-sm-3 text-md-right">{{__('Logo')}}</label>
                                <div class="col-sm-6 col-md-9">
                                    <div class="fileinput fileinput-new" >
                                        <div class="fileinput-new thumbnail img-thumbnail" name="logo">
                                            <img class="logo" src="{{empty($master['logo'])?asset('assets/img/employee-logo.png'):$master['logo']}}" style="width: 50px; height: 50px;">
                                        </div>
                                        <div>
                                            <span class="btn btn-file file-btns">
                                                <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                <input type="hidden"><input type="file" name="logo" onchange="showImg(this)">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row align-items-center">
                                <label for="company_short_name" class="form-control-label col-sm-3 text-md-right">{{__('Company Short Name')}}</label>
                                <div class="col-sm-6 col-md-9">
                                    <input type="text" name="company_short_name" class="form-control" id="company_short_name" value="{{$master['company_short_name'] ?? ''}}" required>
                                    {{--                    <div class="form-text text-muted">{{__('Company Short Name')}}</div>--}}
                                </div>
                            </div>
                        </div>

            </div>
        </div>
        @csrf
        @method('PUT')
        <input type="hidden" name="setting" value="contact">
        @can($__env->yieldContent('permission_modify'))
            <div class="card-footer bg-whitesmoke text-md-right">
                <button type="button" class="btn btn-primary" type="submit" onclick="EditContact({{$master['id']}});return false">{{__('Submit')}}</button>
            </div>
        @endcan
    </div>
</form>
    </div></div>
