@section('title_delete', __('口座情報'))
@section('function_delete', __('bankCardDelete()'))
@section('permission_modify','asset_modify')
<!-- Payroll Additions Table -->
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="card">
            <form action="" method="post" class="col-md-12" style="min-width: 800px">
                @csrf
                @method('put')
                <div class="row">
                    <div class="card-header w-100">
                        <h4 class="m-0">{{ __('資産種類') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table class="table table-striped custom-table mb-0 datatable col-xl-12 col-md-12" id="asset-types">
                            <thead>
                            <tr>
                                <th class="text-left">#</th>
                                <th class="text-center">{{__('Asset Type Code')}}</th>
                                <th class="text-left">{{__('Asset Type Name')}}</th>
                                @can($__env->yieldContent('permission_modify'))
                                <th style="width: 100px;" class="text-center">{{__('Action')}}</th>
                                @endcan
                            </tr>
                            <tr id="tr_template" hidden>
                                <td class="text-left"></td>
                                <td class="text-center"></td>
                                <td class="text-left"></td>

                                <td class="text-center">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit_asset_type" href="#" data-toggle="modal"
                                               data-id=":id" data-edit="{{ route('assettypes.edit', ':id') }}">
                                                <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                            </a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_asset_type" data-id=":id">
                                                <i class="fa fa-trash-o m-r-5"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($assettypes as $assettype)
                                <tr id="type_{{ $assettype->id}}">
                                    <td class="text-left">{{$loop->iteration}}</td>
                                    <td class="text-center">{{ $assettype->asset_type_code}}</td>
                                    <td>{{ $assettype->asset_type_name}}</td>
                                    @can($__env->yieldContent('permission_modify'))
                                    <td class="text-center">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item edit_asset_type" href="javascript:void(0)"
                                                   data-id="{{$assettype->id}}" data-edit="{{ route('assettypes.edit', $assettype->id) }}">
                                                    <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#delete_asset_type" data-id="{{$assettype->id}}">
                                                    <i class="fa fa-trash-o m-r-5"></i> {{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    @can($__env->yieldContent('permission_modify'))
                <div class="card-footer bg-whitesmoke text-md-right w-100">
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add_asset_type">資産種類追加</button>
                </div>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Asset Type Modal -->
<div id="add_asset_type" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Asset Type') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assettypes.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="asset_type_code">{{ __('Asset Type Code') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               name="asset_type_code" autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="asset_type_name">{{ __('Asset Type Name') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="asset_type_name" required >
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateAssetType('#add_asset_type form')">{{ __('Add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Asset Type Modal -->

<!-- Edit Asset Type Modal -->
<div id="edit_asset_type" class="modal custom-modal fade" role="dialog" data-route="{{ route("assettypes.update", ':id') }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Asset Type') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit_asset_type_form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="asset_type_code">{{ __('Asset Type Code') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               id="edit_asset_type_code" name="asset_type_code"  autofocus required >
                    </div>
                    <div class="form-group">
                        <label for="asset_type_name">{{ __('Asset Type Name') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text"
                               id="edit_asset_type_name" name="asset_type_name"  autofocus required >
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addOrUpdateAssetType('#edit_asset_type_form')">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Asset Type Modal -->

<!-- Delete Asset Type Modal -->
<div class="modal custom-modal fade" id="delete_asset_type" role="dialog" data-route="{{ route('assettypes.destroy', ':id') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('Delete Asset Type') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <form>@csrf @method('delete')</form>
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_asset_type_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
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
