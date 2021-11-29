@section('file_append')
    <input type="hidden" name="file_id" value="{{$data->file->id}}"/>
    <input type="hidden" name="file_name" value="{{$data->file->basename}}"/>
    <a href="javascript:void(0)" onclick="openFile({{$data->file->id}},'{{$data->file->type}}')">{{$data->file->basename}}</a>
@endsection
