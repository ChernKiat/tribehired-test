<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>
<body>
    <iframe id="myHijackFrame" src="{{ '/myHijackShowcase/loader.php?https://demonisblack.com/code/2022/easterday/app' ?? base_path('resources\views\modules\hijack_showcase\echo.php') }}" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
        Your browser doesn't support iframes
    </iframe>
</body>
{{-- <frameset rows="100%,*">
    <frame src=""></frame>
    <noframes>
        <body>

        </body>
    </noframes>
</frameset> --}}
</html>
