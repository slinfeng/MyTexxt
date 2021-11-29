<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">{{ $title }}</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">{{__('Dashboard')}}</a></li>
                @if (isset($breadcrumb))
                    @foreach ($breadcrumb as $item)
                        <li class="breadcrumb-item {{$loop->last ? 'active' : ''}}"><a href="#">{{$item['text']}}</a></li>
                        {{-- <li class="breadcrumb-item active" aria-current="page">List</li> --}}
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->
