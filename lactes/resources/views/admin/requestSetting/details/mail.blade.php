<div class="tab-content">
    <div id="emp_mail" class="pro-overview tab-pane fade show active ">
        <div class="card flex-fill">
            <div class="card-header col-md-12 position-relative">
                <h4 class="m-0">{{ __('受信担当者') }}</h4>
            </div>
            <div class="card-body">
                <select class="select-receiver" name="user_selected" multiple="multiple"
                        data-route="{{route('adminSetting.receiveMailAddressChange')}}" required>
                    @foreach ($users as $user)
                        <option value="{{$user['id']}}">{{$user['name']}}
                            ({{$user['email']}})
                        </option>
                    @endforeach
                </select>
            </div>
            @can($__env->yieldContent('permission_modify'))
                <div class="card-footer bg-whitesmoke text-md-right">
                    <button class="btn btn-primary" type="button" onclick="receiveMailAddressChange();">
                        保存
                    </button>
                </div>
            @endcan
        </div>

        <div class="row">
            <div
                class="explanatory-text">{{__('メール本文中の「##COMPANYNAME##」と「##CLIENTNAME##」は使用される変数です、「##COMPANYNAME##」は我社の名前で、「##CLIENTNAME##」は客様の名前です、本文を編集の時は必ず保存してください。')}}</div>
            <div class="col-md-6">
                <div class="card profile-box flex-fill">
                    <div class="card-header col-md-12 position-relative">
                        <h4 class="m-0">{{ __('メール①(承認要請)') }}</h4>
                    </div>
                    <form id="mail0" action="{{route('mail.requestMailUpdate')}}" method="post">
                        <input name="id" value="4" type="hidden">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        <label for="subject">{{ __('Email Subject') }}</label>
                                    </td>
                                    <td>
                                        <input class="form-control" value="{{ $mails[0]->subject }}" name="subject"
                                               type="text" placeholder="{{__('Please Enter Email Subject')}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>{{ __('Email Body') }}</label>
                                    </td>
                                    <td>
                                        <textarea class="textarea email-textarea" class="form-control" name="body"
                                                  rows="3" placeholder="{{__('Place some text here')}}"
                                                  style="">{{ $mails[0]->body }}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                        <div class="card-footer bg-whitesmoke text-md-right">
                            <button class="btn btn-primary" type="button" onclick="mailSubmit('#mail0');return false">
                                保存
                            </button>
                        </div>
                        @endcan
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card profile-box flex-fill">
                    <div class="card-header col-md-12 position-relative">
                        <h4 class="m-0">{{ __('メール②(却下)') }}</h4>
                    </div>
                    <form id="mail1" action="{{route('mail.requestMailUpdate')}}" method="post">
                        <input name="id" value="5" type="hidden">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        <label for="subject">{{ __('Email Subject') }}</label>
                                    </td>
                                    <td>
                                        <input class="form-control" value="{{ $mails[1]->subject }}" name="subject"
                                               type="text" placeholder="{{__('Please Enter Email Subject')}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>{{ __('Email Body') }}</label>
                                    </td>
                                    <td>
                                        <textarea class="textarea email-textarea" class="form-control" name="body"
                                                  rows="3" placeholder="{{__('Place some text here')}}"
                                                  style="">{{ $mails[1]->body }}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                        <div class="card-footer bg-whitesmoke text-md-right">
                            <button class="btn btn-primary" type="button" onclick="mailSubmit('#mail1');return false">
                                保存
                            </button>
                        </div>
                        @endcan
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card profile-box flex-fill">
                    <div class="card-header col-md-12 position-relative">
                        <h4 class="m-0">{{ __('メール③(承認済)') }}</h4>
                    </div>
                    <form id="mail2" action="{{route('mail.requestMailUpdate')}}" method="post">
                        <input name="id" value="6" type="hidden">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        <label for="subject">{{ __('Email Subject') }}</label>
                                    </td>
                                    <td>
                                        <input class="form-control" value="{{ $mails[2]->subject  }}" name="subject"
                                               type="text" placeholder="{{__('Please Enter Email Subject')}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>{{ __('Email Body') }}</label>
                                    </td>
                                    <td>
                                        <textarea class="textarea email-textarea" class="form-control" name="body"
                                                  rows="3" placeholder="{{__('Place some text here')}}"
                                                  style="">{{ $mails[2]->body }}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                        <div class="card-footer bg-whitesmoke text-md-right">
                            <button class="btn btn-primary" type="button" onclick="mailSubmit('#mail2');return false">
                                保存
                            </button>
                        </div>
                        @endcan
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card profile-box flex-fill">
                    <div class="card-header col-md-12 position-relative">
                        <h4 class="m-0">{{ __('メール④(削除)') }}</h4>
                    </div>
                    <form id="mail3" action="{{route('mail.requestMailUpdate')}}" method="post">
                        <input name="id" value="7" type="hidden">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        <label for="subject">{{ __('Email Subject') }}</label>
                                    </td>
                                    <td>
                                        <input class="form-control" value="{{ $mails[3]->subject  }}" name="subject"
                                               type="text" placeholder="{{__('Please Enter Email Subject')}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>{{ __('Email Body') }}</label>
                                    </td>
                                    <td>
                                        <textarea class="textarea email-textarea" class="form-control" name="body"
                                                  rows="3" placeholder="{{__('Place some text here')}}"
                                                  style="">{{ $mails[3]->body }}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                        <div class="card-footer bg-whitesmoke text-md-right">
                            <button class="btn btn-primary" type="button" onclick="mailSubmit('#mail3');return false">
                                保存
                            </button>
                        </div>
                        @endcan
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card profile-box flex-fill">
                    <div class="card-header col-md-12 position-relative">
                        <h4 class="m-0">{{ __('メール⑤(発注)') }}</h4>
                    </div>
                    <form id="mail4" action="{{route('mail.requestMailUpdate')}}" method="post">
                        <input name="id" value="8" type="hidden">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        <label for="subject">{{ __('Email Subject') }}</label>
                                    </td>
                                    <td>
                                        <input class="form-control" value="{{ $mails[4]->subject  }}" name="subject"
                                               type="text" placeholder="{{__('Please Enter Email Subject')}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>{{ __('Email Body') }}</label>
                                    </td>
                                    <td>
                                        <textarea class="textarea email-textarea" class="form-control" name="body"
                                                  rows="3" placeholder="{{__('Place some text here')}}"
                                                  style="">{{ $mails[4]->body }}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary" type="button" onclick="mailSubmit('#mail4');return false">
                                    保存
                                </button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function initReceiveSelector(){
        const idArr = [];
        @if(isset($receiver_arr))
        @foreach($receiver_arr as $receiver)
            idArr.push('{{$receiver}}');
        @endforeach
        @endif
        $('.select-receiver').select2({
            maximumSelectionLength:25,
            placeholder:'受信担当者を選択してください。',
        });
        if(idArr.length===0){
            $('.select2-search__field').width('20em');
        }else $('.select-receiver').val(idArr).trigger('change');
    }
</script>
