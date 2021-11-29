<div class="modal-header">
    <h5 class="modal-title">{{ __('取引先詳細一覧画面') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul class="mb-0"></ul>
    </div>
    <form method="POST" id='show_form'>
        <div class="form-row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="client_name">{{ __('Client Name') }}<span class="text-danger">*</span></label>
                    <input class="form-control" value="{{ $client->client_name }}"
                           readonly type="text" name="client_name" autofocus required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="client_abbreviation">{{ __('Client Abbreviation') }}<span
                            class="text-danger">*</span></label>
                    <input readonly class="form-control" value="{{ $client->client_abbreviation }}"
                           type="text" name="client_abbreviation" autofocus required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cooperation_start">{{ __('Cooperation Start') }}<span
                            class="text-danger">*</span></label>
                    <input disabled class="form-control flatpickr" value="{{ $client->cooperation_start }}"
                           type="text" name="cooperation_start" autofocus required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Our Position') }}<span class="text-danger">*</span></label>
                    <select disabled class="select form-control" name="our_role"
                            placeholder="{{__('Please Select Our role')}}" autofocus required>
                        @foreach ($ourpositiontypes as $ourpositiontype)
                            <option
                                value="{{$ourpositiontype['id']}}" {{ $client->our_role == $ourpositiontype->id ? 'selected' : ''}}>{{$ourpositiontype['our_position_type_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_type">{{ __('Client Url') }}</label>
                    <input class="form-control" value="{{ $client->url }}" readonly type="text" name="url" autofocus required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Client Mail') }}</label>
                    <input class="form-control" value="{{ $client->mail }}" readonly type="text" name="mail" autofocus required>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_type">{{ __('Client Tel') }}</label>
                    <input class="form-control" value="{{ $client->tel }}"
                           readonly oninput="value=value.replace(/[^0-9]/g,'')" type="text" name="tel" autofocus
                           required maxlength="15">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="our_role">{{ __('Client Fax') }}</label>
                    <input class="form-control" value="{{ $client->fax }}"
                           readonly oninput="value=value.replace(/[^0-9]/g,'')" type="text" name="fax" autofocus
                           required maxlength="15">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="contract_type">{{ __('Post Code') }}</label>
                    <input class="form-control" value="{{ $client->post_code==''?'':$client->post_code }}" maxlength="7"
                           readonly onfocus="onPostFocus(this)" oninput="value=value.replace(/[^0-9]/g,'')"
                           onblur="addPostMark(this)" type="text" name="post_code" autofocus required>
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label for="client_address">{{ __('Client Address') }}</label>
                    <textarea name="client_address" style="height: 44px" readonly class="form-control" autofocus
                    >{{ $client->client_address }}</textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="memo">{{ __('Memo') }}</label>
            <textarea name="memo" cols="30" rows="5" readonly class="form-control" autofocus
            >{{ $client->memo }}</textarea>
        </div>
    </form>
</div>
