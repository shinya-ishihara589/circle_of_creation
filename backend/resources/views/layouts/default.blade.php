<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @yield('commons.head')
</head>

<body class="container">
    <div class="row">
        <div class="col-md-2">
            @yield('commons.nav')
        </div>
        <div class="col-md-10">
            @yield('contents')
        </div>
        <!-- <div style="width: 20%;">
            @yield('commons.aside')
        </div> -->
    </div>
    @yield('commons.foot')
</body>

</html>