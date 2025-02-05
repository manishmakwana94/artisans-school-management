<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.includes.head')
    @stack('styles')
</head>

<body>
    <div id="app">

        @include('layouts.includes.header')
        <main class="py-4">
            @yield('content')
        </main>
        <div class="footer">
            @include('layouts.includes.footer')
        </div>
    </div>
    @include('layouts.includes.js')
    @stack('scripts')

</body>

</html>
