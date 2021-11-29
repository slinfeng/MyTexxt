<div class="modal-header">
    <h5 class="modal-title">{{ __('Add EmailTemplate') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" id='add_edit_form' class="form-horizontal" autocomplete="off" >
        @csrf
        <div class="box-body">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul class="mb-0"></ul>
            </div>

            <div class="form-group">
                <label for="email_template_id">{{ __('Email Template ID') }}</label>
                <input class="form-control" value="{{ old('email_id') }}" id="email_template_id"
                       type="text" name="email_id" placeholder="{{__('Please Enter Email Template ID')}}"  >
            </div>
            <div class="form-group">
                <label for="subject">{{ __('Email Template Subject') }}</label>
                <input class="form-control" value="{{ old('subject') }}" name="subject" id="subject"
                       type="text" placeholder="{{__('Please Enter Email Template Subject')}}" >
            </div>
            <div class="form-group">
                <label for="email_template_body">{{ __('Email Template Body') }}</label>
                <textarea class="textarea"  id="email_template_body" class="form-control" name="body" rows="3" placeholder="{{__('Place some text here')}}"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                    {{ old('body') }}
                </textarea>
            </div>
{{--            <div class="form-group">--}}
{{--                <label >{{ __('Email Template Variables Used') }}</label><br/>--}}
{{--                <span class="" ></span>--}}
{{--            </div>--}}

            <div class="submit-section">
                <button class="btn btn-primary submit-btn" type="submit"  onclick="addEditUser();return false" >{{ __('Add') }}</button>
            </div>
        </div>
    </form>
</div>

