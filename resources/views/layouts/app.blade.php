<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PungliCare | @yield('namefitur')</title>

    <!-- Styles -->
    @include('layouts.partials.styles')
    @stack('styles')


</head>

<body>
    <div id="app">
        @include('layouts.partials.sidebar')

        <div id="main" class='layout-navbar'>
            @include('layouts.partials.header')
            <div id="main-content">

                <div class="page-heading">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @include('layouts.partials.scripts')
    @stack('scripts')

    <!-- Font Awesome Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"
        integrity="sha512-v10dCyQ3yODmLEyUusYtY1Wl2YmoRm6MN8aTznnT2G7BhLFhCpFFyV3wN0s1Zl4EKF2ws4Jk5dji4Km36gXcxQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>