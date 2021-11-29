<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<form id="email-setting-form" autocomplete="off">
    <div class="card" id="email-settings-card">
        <div class="card-header">
            <h4>{{__('Email Setting')}}</h4>
        </div>

        <div class="card-body">
{{--            <p class="text-muted">{{__('Email SMTP settings, notifications and others related to email.')}}</p>--}}

            <div class="form-group row align-items-center">
                <label for="mail_driver" class="form-control-label col-sm-3 text-md-right">{{__('Mail Driver')}}</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="mail_driver" class="form-control" id="mail_driver" value="{{$master['mail_driver'] ?? ''}}" required readonly>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('Mail driver: smtp')}}</div>
                </div>

            </div>
{{--            <div class="">--}}
{{--            <div class="form-text text-muted">{{__('Mail driver: smtp')}}</div>--}}
{{--            </div>--}}

                <div class="form-group align-items-center row">
                    <label for="mail_host" class="form-control-label col-sm-3 text-md-right">{{__('Mail Host')}}</label>
                    <div class="col-sm-6 col-md-6">
                        <input type="text" name="mail_host" class="form-control" id="mail_host" onchange="value=value.replace(/[^\x00-\xff]/g,'')" value="{{$master['mail_host'] ?? ''}}" required >
                    </div>
                    <label for="mail_port" class="form-control-label col-sm-1 text-md-right">{{__('Mail Port')}}</label>
                    <div class="col-sm-3 col-md-2">
                        <input type="text" name="mail_port" onchange="value=value.replace(/[^0-9]/g,'')" class="form-control" id="mail_port" value="{{$master['mail_port'] ?? ''}}" required>
                    </div>
                    <label class="form-control-label col-sm-3 text-md-right"></label>
                    <div class="col-sm-6 col-md-9">
                        <div class="form-text text-muted">{{__('This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.')}}</div>
                        <div class="form-text text-muted">{{__('SMTP port this will provide your service provider.')}}</div>
                    </div>
                </div>
{{--                <div class="form-group align-items-center col-3">--}}
{{--                    <div class="form-group row align-items-center">--}}
{{--                        <label for="mailPort" class="form-control-label col-sm-3 text-md-right">{{__('Mail Port')}}</label>--}}
{{--                        <div class="col-sm-6 col-md-9">--}}
{{--                            <input type="text" name="mailPort" class="form-control" id="mailPort" value="{{$master['mail_port'] ?? ''}}" required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}


            <div class="form-group row align-items-center">
                <label for="mail_username" class="form-control-label col-sm-3 text-md-right">{{__('Mail Username')}}</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="mail_username" class="form-control" id="mail_username" value="{{$master['mail_username'] ?? ''}}" required>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('Smtp username')}}</div>
                </div>

            </div>
            <div class="form-group row align-items-center">
                <label for="mail_password" class="form-control-label col-sm-3 text-md-right">{{__('Mail Password')}}</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="mail_password" class="form-control" id="mail_password" value="{{$master['mail_password'] ?? ''}}" required>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('Smtp password of above given username.')}}</div>
                </div>

            </div>

            <div class="form-group row">
                <label class="form-control-label col-sm-3 mt-3 text-md-right">{{__('Mail Encryption')}}</label>
                <div class="col-sm-6 col-md-9">
                    <select class="form-control" name="mail_encryption" id="mail_encryption">
                        <option @if($master['mail_encryption'] == 'ssl') selected @endif value="ssl" selected="">SSL</option>
                        <option @if($master['mail_encryption'] == 'tls') selected @endif value="tls">TLS</option>
                    </select>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('If your e-mail service provider supported secure connections, you can choose security method on list.')}}</div>
                </div>

            </div>

            <div class="form-group row align-items-center">
                <label for="mail_from_address" class="form-control-label col-sm-3 text-md-right">{{__('Mail From Address')}}</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="mail_from_address" class="form-control" id="mail_from_address" value="{{$master['mail_from_address'] ?? ''}}" required>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('Mail From Address')}}</div>
                </div>

            </div>
            <div class="form-group row align-items-center">
                <label for="mail_from_name" class="form-control-label col-sm-3 text-md-right">{{__('Mail From Name')}}</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="mail_from_name" class="form-control" id="mail_from_name" value="{{$master['mail_from_name'] ?? ''}}" required>
                </div>

                <label class="form-control-label col-sm-3 text-md-right"></label>
                <div class="col-sm-6 col-md-9">
                    <div class="form-text text-muted">{{__('Mail From Name')}}</div>
                </div>

            </div>

{{--            <div class="form-group row">--}}
{{--                <label class="form-control-label col-sm-3 mt-3 text-md-right">{{__('Email Content Type')}}</label>--}}
{{--                <div class="col-sm-6 col-md-9">--}}

{{--                    <select class="form-control" name="smtp_encryption" id="mail_encryption">--}}
{{--                        <option value="off">off</option>--}}
{{--                        <option @if($master->mail_encryption == 'text') selected @endif value="ssl" selected="">Text</option>--}}
{{--                        <option @if($master->mail_encryption == 'html') selected @endif value="tls">HTML</option>--}}
{{--                    </select>--}}
{{--                    <div class="form-text text-muted">{{__('Text-plain or HTML content chooser.')}}</div>--}}
{{--                </div>--}}
{{--            </div>--}}
            @csrf
            @method('PUT')
            <input type="hidden" name="setting" value="email">
            <div class="alert alert-primary print-success-msg" style="display:none">
                <ul class="mb-0"></ul>
            </div>
        </div>
        @can($__env->yieldContent('permission_modify'))
        <div class="card-footer bg-whitesmoke text-md-right">
            <button type="button" class="btn btn-primary" type="submit" onclick="EditEmail({{$master['id']}});return false">{{__('Submit')}}</button>
        </div>
        @endcan
    </div>
</form>
    </div>
</div>
