@if(count($breadcrumbs))
<div class="breadcrumb d-flex align-items-center mb-3 flex-warp flex-md-nowrap gap-2 gap-md-0">
    <img src="{{ asset('images/icons/breadcrumb-home.svg')}}" alt="home-icon">
    @foreach($breadcrumbs as $breadcrumb)
    @if ($breadcrumb['url'])
    <img src="{{ asset('images/icons/chevron-right.svg')}}" alt=" auth-logo" class="custom-mx-2px">
    <a href="{{$breadcrumb['url'] }}"><span
            class="d-inline-block text-sm font-semibold px-2 py-1 text-gray-600">{{$breadcrumb['title'] }}</span></a>
    @else
    <img src="{{ asset('images/icons/chevron-right.svg')}}" alt="auth-logo" class="custom-mx-2px">
    <span class="d-inline-block text-sm font-semibold px-2 py-1 text-gray-600 active-page">{{ $breadcrumb['title']
        }}</span>
    @endif
    @endforeach
</div>
@endif