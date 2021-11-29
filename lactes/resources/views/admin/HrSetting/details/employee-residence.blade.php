<div class="row">
    <div class="col-md-12">
        <div class="col-12">
            <table id="residenceTypeTable" class="table table-striped custom-table mb-0 datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('在留資格種類')}}</th>
                    @can($__env->yieldContent('permission_modify'))
                        <th class="text-right" style="width: 100px;" >{{__('Action')}}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach ($residenceTypes as $residenceType)
                    <tr>
                        <td class="numberId">{{$loop->iteration}}</td>
                        <td class="field1" data-id="{{$residenceType->id}}">{{ $residenceType->residence_type}}</td>
                        @can($__env->yieldContent('permission_modify'))
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit_icon" href="#" data-toggle="modal" data-target="#edit_modal"
                                           data-id="{{$residenceType->id}}" data-type="residence"
                                           data-update="{{ route("residenceType.update", [$residenceType->id]) }}" >
                                            <i class="fa fa-pencil m-r-5"></i> {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('residenceType.destroy', $residenceType) }}" method="post" >
                                            @csrf
                                            @method('delete')
                                            <a class="dropdown-item delete_icon" data-type="residence" href="#" data-toggle="modal" data-target="#delete_modal">
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
                    <a href="javascript:void(0)" class="add-button btn btn-primary btn-block" data-url="{{route('residenceType.store')}}" data-type="residence"><i class="fa fa-plus"></i> {{ __('在留資格種類を追加') }}</a>
                </div>
            </div>
        @endcan
    </div>
</div>

@section('footer_residence_type')
    <script type="text/javascript">
        function addResidenceTypeAfter(response){
            const residence = response.newData;
            let tr = $('#residenceTypeTable tr:last').clone();
            $('#residenceTypeTable').append(tr);
            $('#residenceTypeTable tr:last').find('td').each(function (index) {
                switch (index) {
                    case 0:
                        $(this).html(Number($(this).html())+1);
                        break;
                    case 1:
                        $(this).html(residence.residence_type).data("id",residence.id);
                        break;
                    case 2:
                        let updateUrl = '{{route('residenceType.update', ':id')}}';
                        updateUrl = updateUrl.replace(':id',[residence.id]);
                        $(this).find('.edit_icon').data({'id':residence.id,'update':updateUrl});
                        let action = "{{ route('residenceType.destroy',':id') }}";
                        action = action.replace(':id',residence.id);
                        $(this).find('form').attr('action',action);
                        break;
                }
            })
        }
    </script>

@endsection
