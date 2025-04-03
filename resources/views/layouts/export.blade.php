<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style>
        /* CSS for print */
@media print {
    table thead {
        /* Ensure that the table header appears only once */
        display: table-header-group;
    }

    table tfoot {
        /* Ensure that the table footer appears only once */
        display: table-footer-group;
    }
}
        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ECNL | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">

    <!-- select-2 css -->
    <link href="{{ asset('/custom-css/select-2.css') }}" rel="stylesheet" />

    <!-- datepicker css -->
    <link href="{{ asset('/custom-css/datepicker.css') }}" rel="stylesheet" />

    <!-- datepicker css -->
    <!-- <link href="{{ asset('/custom-css/flatpickr.css') }}" rel="stylesheet" /> -->
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">

    <!-- country code css -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.min.css"
        rel="stylesheet">
    <link href="{{ asset('/custom-css/intl-tel-input.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

 <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body style="block">
    {{-- @if (isset($guest) && $guest == true)
        <div id="app">
            <main class="">
                @yield('content')
            </main>
        </div>
    @else --}}
        <div id="app" class="container" style="max-width:1000px">
            <div class="content-wrapper active" id="main-content-area">
                @yield('content')
            </div>
        </div>
    {{-- @endif --}}
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="{{ asset('/js/frontend.js') }}"></script>
    <script src="{{ asset('/js/validation.js') }}"></script>
    <script src="{{ asset('/js/file-upload.js') }}"></script>
    <script src="{{ asset('/custom-js/common.js') }}"></script>
    <script src="{{ asset('/custom-js/function.js') }}"></script>
    <script src="{{ asset('/custom-js/select-2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/1.14.11/jquery.inputmask.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    <script src="{{ asset('/js/comment.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{ asset('/js/charts.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireScripts
</body>

</html>

