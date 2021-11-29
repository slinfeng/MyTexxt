@section('base_selector')

<span name="our_position_type" class="@can($__env->yieldContent('self_modify')) hide @endcan"><label><input name="our_position_type" type="radio" value="1" class="btn-group-vertical"
                                             @if($position==1) checked
                                             @endif
                                             {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                                             onchange="disableFormat(this)"> @yield('position_a_html')</label></span>
<span name="our_position_type" class="@can($__env->yieldContent('self_modify')) hide @endcan"><label><input name="our_position_type" type="radio" value="2" class="btn-group-vertical"
                                             @if($position==2) checked
                                             @endif
                                             {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                                             onchange="disableFormat(this)"> @yield('position_b_html')</label></span>

<input type="hidden" name="last_format" value="0">
<input type="hidden" name="last_client_a" value="0">
<input type="hidden" name="last_client_b" value="0">
@can($__env->yieldContent('self_modify'))
    @if($__env->yieldContent('page_status')=='expenseEdit')
    @else
        @if($__env->yieldContent('document_format')==1)
            <div>
            <span name="file_format_type"><label><input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                                                        {{$__env->yieldContent('file_format_type')==1?'checked':''}}
                                                        {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                                                        type="radio" value="1"> {{ __('アップロード') }}</label></span>
            </div>
        @elseif($__env->yieldContent('document_format')==0)
            <div>
        <span name="file_format_type"><label style="padding-right: 30px">
                        <input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                               {{$__env->yieldContent('file_format_type')==0?'checked':''}}
                               {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                               type="radio" value="0"> {{ __('画面入力') }}</label></span>
            </div>
        @else
            <div>
    <span name="file_format_type"><label style="padding-right: 30px">
                        <input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                               {{$__env->yieldContent('file_format_type')==0?'checked':''}}
                               {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                               type="radio" value="0"> {{ __('画面入力') }}</label></span>
                <span name="file_format_type"><label><input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                                                            {{$__env->yieldContent('file_format_type')==1?'checked':''}}
                                                            {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                                                            type="radio" value="1"> {{ __('アップロード') }}</label></span>
            </div>
        @endif
    @endif
@else
    <div>
    <span name="file_format_type"><label style="padding-right: 30px">
                        <input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                               {{$__env->yieldContent('file_format_type')==0?'checked':''}}
                               {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                               type="radio" value="0"> {{ __('社内フォーマット') }}</label></span>
        <span name="file_format_type"><label><input onchange="changeFormat(this)" name="file_format_type" class="btn-group-vertical"
                                                    {{$__env->yieldContent('file_format_type')==1?'checked':''}}
                                                    {{$__env->yieldContent('page_type')=='edit'?'disabled':''}}
                                                    type="radio" value="1"> {{ __('社外フォーマット') }}</label></span>
    </div>
@endcan

    @can($__env->yieldContent('self_modify'))
    @else
        @if($__env->yieldContent('page_type')=='edit')
{{--    <div>取引先：{{$__env->yieldContent('client_name')}}</div>--}}
            <div class="client-name">取引先：{{$data->cname}}</div>
        @else
            @include('layouts.pages.request_manage.client_select')
        @endif
    @endcan
@endsection
