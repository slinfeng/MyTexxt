<div class="row">
    <div class="col-md-12">
        <div class="col-12">
            <table id="positionTypeTable" class="table table-striped custom-table mb-0 datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 30%;">{{__('役職名')}}</th>
                    <th class="text-left">{{__('表示名称')}}</th>
                    @can($__env->yieldContent('permission_modify'))
                    <th class="text-right" style="width: 100px;" >{{__('Action')}}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach ($positionTypes as $positionType)
                    <tr>
                        <td class="numberId">{{$loop->iteration}}</td>
                        <td class="field1" data-id="{{$positionType->id}}">{{ $positionType->position_type}}</td>
                        <td class="field2">{{ $positionType->position_type_name}}</td>
                        @can($__env->yieldContent('permission_modify'))
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit_icon" href="#" data-toggle="modal" data-target="#edit_modal"
                                       data-id="{{$positionType->id}}" data-type="position"
                                       data-update="{{ route("positionType.update", [$positionType->id]) }}" >
                                        <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('positionType.destroy', $positionType) }}" method="post" >
                                        @csrf
                                        @method('delete')
                                        <a class="dropdown-item delete_icon" data-type="position" href="#" data-toggle="modal" data-target="#delete_modal">
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
                <a href="javascript:void(0)" class="add-button btn btn-primary btn-block" data-url="{{route('positionType.store')}}" data-type="position"><i class="fa fa-plus"></i> {{ __('役職を追加') }}</a>
            </div>
        </div>
        @endcan
    </div>
</div>

@section('footer_position_type')
    <script type="text/javascript">
        function addPositionTypeAfter(response){
            const position = response.newData;
            let tr = $('#positionTypeTable tr:last').clone();
            $('#positionTypeTable').append(tr);
            $('#positionTypeTable tr:last').find('td').each(function (index) {
                switch (index) {
                    case 0:
                        $(this).html(Number($(this).html())+1);
                        break;
                    case 1:
                        $(this).html(position.position_type);
                        break;
                    case 2:
                        $(this).html(position.position_type_name).data("id",position.id);
                        break;
                    case 3:
                        let updateUrl = '{{route('positionType.update', ':id')}}';
                        updateUrl = updateUrl.replace(':id',[position.id]);
                        $(this).find('.edit_icon').data({'id':position.id,'update':updateUrl});
                        let action = "{{ route('positionType.destroy',':id') }}";
                        action = action.replace(':id',position.id);
                        $(this).find('form').attr('action',action);
                        break;
                }
            })
        }
    </script>

@endsection
