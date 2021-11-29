@section('content')
    @include('layouts.pages.request_manage.page_create_or_edit')
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.modify_a')
    <script src="{{ asset('assets/js/expense.create.edit.js') }}"></script>
    @include('layouts.footers.requestmanage.exclude')
@endsection
