<div class="row">
    <div class="col-md-12">
        <div class="col-12">
            <table id="departmentTable" class="table table-striped custom-table mb-0 datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Department Name')}}</th>
                    @can($__env->yieldContent('permission_modify'))
                    <th class="text-right" style="width: 100px;" >{{__('Action')}}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td class="numberId">{{$loop->iteration}}</td>
                        <td class="field1" data-id="{{$department->id}}">{{ $department->department_name}}</td>
                        @can($__env->yieldContent('permission_modify'))
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit_icon" href="#" data-toggle="modal" data-target="#edit_modal"
                                       data-id="{{$department->id}}" data-type="department"
                                       data-update="{{ route("departments.update", [$department->id]) }}" >
                                        <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="post" >
                                        @csrf
                                        @method('delete')
                                        <a class="dropdown-item delete_icon" data-type="department" href="#" data-toggle="modal" data-target="#delete_modal">
                                            <i class="fa fa-trash-o m-r-5"></i> {{ __('Delete') }}
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @can($__env->yieldContent('permission_modify'))
        <div class="w-100 p-2">
            <div class="float-right p-0">
                <a href="javascript:void(0)" class="add-button btn btn-primary btn-block" data-url="{{route('departments.store')}}" data-type="department"><i class="fa fa-plus"></i> {{ __('Add department') }}</a>
            </div>
        </div>
        @endcan
    </div>
</div>


@section('footer_department')
    <script type="text/javascript" defer>
        function addDepartmentAfter(response){
            const department = response.newData;
            let tr = $('#departmentTable tr:last').clone();
            $('#departmentTable').append(tr);
            $('#departmentTable tr:last').find('td').each(function (index) {
                switch (index) {
                    case 0:
                        $(this).html(Number($(this).html())+1);
                        break;
                    case 1:
                        $(this).html(department.department_name).data("id",department.id);
                        break;
                    case 2:
                        let updateUrl = '{{route('departments.update', ':id')}}';
                        updateUrl = updateUrl.replace(':id',[department.id]);
                        $(this).find('.edit_icon').data({'id':department.id,'update':updateUrl});
                        let action = "{{ route('departments.destroy','object') }}";
                        action = action.replace('object',department.id);
                        $(this).find('form').attr('action',action);
                        break;
                }
            })
        }
    </script>

@endsection
