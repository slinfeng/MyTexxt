<div class="row">
    <div class="col-md-12">
        <div class="col-12">
            <table id="retireTypeTable" class="table table-striped custom-table mb-0 datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('在職区分名')}}</th>
                    @can($__env->yieldContent('permission_modify'))
                    <th class="text-right" style="width: 100px;" >{{__('Action')}}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach ($retireTypes as $retireType)
                    <tr>
                        <td class="numberId">{{$loop->iteration}}</td>
                        <td class="field1" data-id="{{$retireType->id}}">{{ $retireType->retire_type}}</td>
                        @can($__env->yieldContent('permission_modify'))
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                @if($retireType->id!=1 && $retireType->id!=2)

                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit_icon" href="#" data-toggle="modal" data-target="#edit_modal"
                                       data-id="{{$retireType->id}}" data-type="retire"
                                       data-update="{{ route("retireType.update", [$retireType->id]) }}" >
                                        <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('retireType.destroy', $retireType) }}" method="post" >
                                        @csrf
                                        @method('delete')
                                        <a class="dropdown-item delete_icon" data-type="retire" href="#" data-toggle="modal" data-target="#delete_modal">
                                            <i class="fa fa-trash-o m-r-5"></i> {{ __('Delete') }}
                                        </a>
                                    </form>
                                </div>
                                @endif
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
                <a href="javascript:void(0)" class="add-button btn btn-primary btn-block" data-url="{{route('retireType.store')}}" data-type="retire"><i class="fa fa-plus"></i> {{ __('在職区分を追加') }}</a>
            </div>
        </div>
        @endcan
    </div>
</div>

@section('footer_retire_type')
    <script type="text/javascript">
        function addRetireTypeAfter(response){
            const retire = response.newData;
            let tr = $('#retireTypeTable tr:last').clone();
            $('#retireTypeTable').append(tr);
            $('#retireTypeTable tr:last').find('td').each(function (index) {
                switch (index) {
                    case 0:
                        $(this).html(Number($(this).html())+1);
                        break;
                    case 1:
                        $(this).html(retire.retire_type).data("id",retire.id);
                        break;
                    case 2:
                        let updateUrl = '{{route('retireType.update', ':id')}}';
                        updateUrl = updateUrl.replace(':id',[retire.id]);
                        $(this).find('.edit_icon').data({'id':retire.id,'update':updateUrl});
                        let action = "{{ route('retireType.destroy',':id') }}";
                        action = action.replace(':id',retire.id);
                        $(this).find('form').attr('action',action);
                        break;
                }
            })
        }
    </script>

@endsection
