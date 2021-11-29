@section('permission_modify','employee_modify')
@if(sizeof($builder)==0)
    <div class="col-xl-12 text-center cardBody">
        <span class="p-3 d-lg-inline-block profile-widget w-100 color-grey">社員情報は存在していません</span>
    </div>
@endif
@foreach($builder as $employee)
    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 cardBody">
        <div class="profile-widget" style="min-height: 300px">
            <div onclick="window.location.href='{{route('employees.show',$employee->id)}}'" class="profile-img text-center" style="height: 160px;width: 160px;margin-top: 10px">
                <img style="max-width: 100%;max-height: 100%;display: block;align-items: center;margin:0 auto" src="{{ $employee->icon!=''?str_replace('/getImage/icon','/getFileSource?path=/thumbnail/employee',str_replace('?','&',$employee->icon)):url('assets/img/id_photo.png')}}" alt="">
            </div>
            <div class="dropdown profile-action">
                @can($__env->yieldContent('permission_modify'))
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_user"  onclick="deleteAlert('{{$employee->id}}',this)">
                    <i class="fa fa-trash-o m-r-5"></i></a>
                @endcan
            </div>
            <div style="position: absolute;left: 5px;top: 10px;">{{('番号：')}}<span @if($employee->modified_type==1) style="color: red" @endif>{{$employee->employee_code}}</span></div>
            <h4 class="user-name m-t-10 mb-0 text-ellipsis">
                {{$employee->user->name}}@if($employee->retire_type_id=='2'){{__('（退職）')}}@endif
            </h4>
            <div class="text-center">
                <table class="small">
                    <tr>
                        <td class="text-justify" style="text-align-last: justify; min-width: 6em">{{__('メール')}}</td>
                        <td>：</td>
                        <td class="text-left text-break">{{$employee->user->email}}</td>
                    </tr>
                    <tr class="text-muted">
                        <td class="text-right" style="text-align-last: justify; min-width: 6em">{{__('携帯番号')}}</td>
                        <td>：</td>
                        <td class="text-left  text-break">{{$employee->employeeContacts->phone?:"なし"}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endforeach

<script type="text/javascript">

</script>
