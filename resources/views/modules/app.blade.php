<!doctype html>
<html lang="en">
<head>
    <title>@yield('page-title') - {{ config('app.name') }}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" href="/assets/img/icons/apple-touch-icon.png" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-config" content="/browserconfig.xml">

    <link rel="manifest" href="/manifest.json">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ mix('assets/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ mix('assets/css/app.css') }}">
    <style type="text/css">
        .required-icon {
            color: red;
        }
        .tooltip {
            pointer-events: none;
        }
    </style>

    @yield('styles')
</head>
<body>
    @include('modules.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('modules.sidebar')

            <div class="content-page">
                <main role="main" class="px-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="{{ mix('assets/js/vendor.js') }}"></script>
    <script src="/assets/js/as/app.js"></script>
    @yield('scripts')
</body>
</html>
