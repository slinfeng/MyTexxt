@extends('layouts.backend')
@section('title', __('社員詳細情報').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('社員詳細情報'))
@section('permission_modify','employee_modify')
@section('route_delete','')
@section('title_delete','扶養家族')
@section('function_delete','relationCardDelete()')
@section('css_append')

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css')}}">
    <!-- Tagsinput CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/employee.show.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/cropper.css') }}">
    <style>
        div[name="button"]{
            text-align: center;
        }
        div[name="button"] input{
            text-align: center!important;
            width: 150px;
            border-color: white;
        }
        #sideButton{
            background-color: white;
            width: 190px;
            height: 50px;
            box-shadow: 0 0 2px #888888;
            border-radius: 20px 0 0 20px;
            z-index: 100;
            padding: 0;
            transition: All 0.4s ease-in-out;
            -webkit-transition: All 0.4s ease-in-out;
            -moz-transition: All 0.4s ease-in-out;
            -o-transition: All 0.4s ease-in-out;
        }
        #sideButton:hover {
            transform: translate(-135px, 0);
            -webkit-transform: translate(-135px, 0);
            -moz-transform: translate(-135px, 0);
            -o-transform: translate(-135px, 0);
            -ms-transform: translate(-135px, 0);
        }
        #sideButton .ovalButton{
            border-radius:15px;
            height: 40px;
            padding: 0;
            width: 80px;
            line-height: 40px;
        }
        #sideButton .roundButton{
            border-radius:50%;
            height: 40px;
            padding: 0;
            width: 40px;
            color: white;
        }
        #sideButton a{
            border-radius:50%;
            height: 40px;
            padding: 6px 0;
            width: 40px;
            vertical-align: middle;
        }
        #sideButton>span{
            border-radius: 20px 0 0 20px;
            border: none;
            height: 50px;
            line-height: 50px;
            font-size: 20px;
            color: white;
            margin-right: 5px;
            text-align: center;
            background-color: #FF851A;
            width: 30px;
        }
        .buttonHover{
            position: fixed;
            right: 0;
        }
        .buttonHide{
            position: fixed;
            right: -145px;
        }
        .submitButton{
            color: white;
            background-color:#55CE63;
            border-color:#55CE63;
        }
        .cancelButton{
            color: white;
            background-color:#6C757D;
            border-color:#6C757D;
            margin-left: 5px;
        }
        .history{
            margin-right: 20px;
        }
        .modify{
            margin-right: 20px;
            color: red;
        }
        .enter{
            display: none;
        }
        .dependentTable th,td{
            border: 1px black solid;
        }
        .redBorder{
            border-color:red ;
        }
        label{
            margin: 0;
        }
        .confirmButton{
            background-color: green;
        }
        .denyButton{
            background-color: red;
            color: white;
        }
        .display-none{
            display: none;
        }
    </style>
@endsection
@section('content')
        <div class="buttonHide row" id="sideButton">
            <span style=""><i class="fa fa-angle-left"></i></span>
            <div style="padding: 5px 0;width: 135px;height: 50px;margin: 0" class="row">
                @can($__env->yieldContent('permission_modify'))
                <div id="adminConfirm">
                    <button type="button" class="adminButton adminConfirm btn submitButton roundButton" onclick="adminConfirm(true)">
                        <span style="font-size: 15px">承認</span>
                    </button>
                    <button type="button" class="adminButton adminDeny btn btn-danger roundButton" onclick="adminConfirm(false)">
                        <span style="font-size: 15px">拒否</span>
                    </button>
                </div>
                <div id="adminModify">
                    <a href="javascript:void(0);" class="adminButton adminModify btn btn-primary ovalButton" onclick="adminModify()" data-over="編集" data-out="<i class='fa fa-pencil'></i>">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <button type="button" class="adminButton adminSave btn submitButton ovalButton" onclick="adminSubmitBefore()" style="display: none" data-over="保存" data-out="<span style='font-size: 20px'>√</span>">
                        <span style="font-size: 20px">√</span>
                    </button>
                </div>
                @else
                    <div>
                        <button type="button" class="denyButton border-0 ovalButton">
                            <span style="font-size: 12px">編集できない</span>
                        </button>
                    </div>
                @endcan
                <button type="button" class="adminButton btn cancelButton roundButton" onclick="adminCancel()" data-over="戻る" data-out="<span style='font-size: 25px'>×</span>">
                    <span style="font-size: 25px">×</span>
                </button>
            </div>
        </div>
        <input hidden="" name="init_val" data-currency-symbol="{{$currency}}">
<form method="POST" id="employee_all_form" autocomplete="off">
{{--    @csrf--}}
{{--    @method('PUT')--}}
    <div class="up-big-card">
        <div class="card mb-0">
            <div class="row employee-update">
                @include('admin.employees.details.base')
            </div>
        </div>
        <div class="tab-content card">
            <!-- Profile Info Tab -->
            <div id="emp_profile" class="pro-overview tab-pane fade show active">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="profile-box flex-fill">
                            <div class="card-body employee-update" id="employee-contacts"  >
                                @include('admin.employees.details.contacts')
                            </div>
                            <div class="card-body employee-update" id="employee-bank"  >
                                @include('admin.employees.details.bank')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="profile-box flex-fill">
                            <div class="card-body employee-update" id="employee-stay"  >
                                @include('admin.employees.details.stay')
                            </div>
                            <div class="card-body" id="employee-insurance">
                                @include('admin.employees.details.insurance')
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="flex-fill">
                            <div class="card-body employee-update">
                                <h3 class="card-title"><strong>{{ __('扶養情報') }}</strong></h3>
                                <h5 class="section-title"><strong>{{ __('') }}</strong></h5>
                                <table class="w-100 employee-table">
                                    <tr>
                                        <td class="title">{{ __('扶養家族') }}</td>
                                        <td class="history">{{$employeeBase->data_history['family_num']}}人</td>
                                        <td class="modify">{{$employeeBase->family_num}}人</td>
                                        <td class="enter">
                                            <div class="input-group">
                                                <input type="text" class="form-control text-right familyNum" name="family_num" maxlength='2' value="{{$employeeBase->family_num}}" oninput="value=value.replace(/[^0-9]/g,'')" onchange="relationCardAdd()">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">人</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="relationInfo">
        @include('admin.employees.details.relation_card')
    </div>
</form>
        @include('layouts.pages.models.model_delete')
        <div class="modal custom-modal fade" id="adminConfirmCoverage" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>{{ __('社員から新しい情報修正が発生した') }} </h3>
                            <p>{{ __('社員から新しい情報修正が発生したため、保存しますか？') }}</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="adminSubmit()"
                                       class="btn btn-primary continue-btn">{{ __('保存') }}</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" onclick="adminSubmitCancel()"
                                       class="btn btn-primary cancel-btn">{{__('Cancel')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="cloneRelationCard" style="display: none">
            <div class="card">
                <div class="card-header col-md-12 position-relative">
                    <h4 class="m-0 float-left card-title">扶養親族（新規）</h4>
                    <button type="button" class="close adminModifyAfter" onclick="relationDeleteClick(this)" data-toggle="modal"
                            data-target="#delete">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <input type="hidden" name="relation_id[]" value="">
                <div class="card-body">
                    <table class="table-left-setting w-100">
                        <tr>
                            <td class="title">
                                {{ __('区分') }}
                            </td>
                            <td>
                                <select class="form-control" name="relationship_type[]" onchange="cardChange(this)">
                                    <option value="0" selected>{{"　"}}</option>
                                    <option value="1" >{{"A配偶者"}}</option>
                                    <option value="2">{{"B扶養親族(16歳以上)"}}</option>
                                    <option value="3">{{"D他の所得者が控除を受ける扶養親族等"}}</option>
                                    <option value="4">{{"16歳未満の扶養親族"}}</option>
                                </select>
                                <br>
                                <span class="text-info sixteenAge"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('氏名') }}
                            </td>
                            <td>
                                <input name="dname[]" value="" type="text" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('個人番号') }}
                            </td>
                            <td>
                                <input name="dependent_residence_card_num[]" value="" type="text" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('続柄') }}
                            </td>
                            <td>
                                <input name="relationship[]" value="" type="text" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td class="title">{{ __('生年月日') }}</td>
                            <td>
                                <input name="dependent_birthday[]" value=""
                                       type="text" class="dateInput form-control">
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('同居・老親') }}
                            </td>
                            <td>
                                <select class="form-control" name="live_type[]">
                                    <option value="1">{{ __('同居') }}</option>
                                    <option value="2">{{ __('老親') }}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('住所又は居所') }}
                            </td>
                            <td>
                                <textarea type="text" name="dependent_address[]" class="form-control" style="max-height: 6em;min-height: 6em"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('所得の見積額') }}
                            </td>
                            <td>
                                <input name="estimated[]" value="0" type="text" class="amount form-control">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="photo-show" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="container">
                            <div class="w-100 text-center">
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <img class="w-100" src="">
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal custom-modal fade" id="icon-model" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" style="padding-bottom: 10px">
                        <div class="modal-btn delete-action">
                            <div class="modal-title" style="font-size: 18px">
{{--                                <span class="btn btn-file file-btns">--}}
{{--                                    <span class="file-select-btn" onclick="newIcon()"> 画像を選択 </span>--}}

{{--                                </span>--}}
                                <div style="font-size:12px;margin-top: 10px;text-align: right;color: #009efb ">▼画像トリミングエリア</div>
                            </div>
                            <div class="modal-body p-0" style="width: 100%;height:440px">
                                <img src="{{$employeeBase->icon!=''?$employeeBase->icon:url('assets/img/id_photo.png')}}" id="iconImg">
                            </div>
                            <div class="p-20">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn continue-btn" onclick="iconIsClick(true)">保存</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn cancel-btn" onclick="iconIsClick(false)">キャンセル</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('footer_append')
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <!-- Tagsinput JS -->
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <!-- 日付 -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/cropper.js') }}"></script>
    <script>
    const id = '{{$employeeBase->id}}';
    const modified_type = "{{ $employeeBase->modified_type}}";
    const employeeCodeHistory = "{{ $employeeBase->employee_code}}";
    const calculateWorkYears = "{{ $hrSetting->calculate_work_years}}";
    const calculateWorkMonths = "{{ $hrSetting->calculate_work_months}}";
    let updateTime = ['{{$employeeBase->updated_at}}','{{$employeeBase->EmployeeBank->updated_at}}','{{$employeeBase->EmployeeContacts->updated_at}}','{{$employeeBase->EmployeeStay->updated_at}}'];
    let adminConfirmUrl = "{{route('employees.adminConfirm')}}";
    let employeeCodeUrl = "{{route('employees.employeeCode')}}";
    let adminSubmitBeforeUrl = "{{route('employees.adminSubmitBefore')}}";
    let adminSubmitUrl = "{{route('employees.adminSubmit',['id'=>$employeeBase->id])}}";
    let employeeIndexUrl = "{{route('employees.index')}}";
    let relationCard;

    </script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script src="{{ asset('assets/js/employee.show.js') }}"></script>
@endsection
