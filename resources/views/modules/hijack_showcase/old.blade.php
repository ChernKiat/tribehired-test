<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>
<frameset rows="100%,*">
    <frame src="{{ $project ?? 'https://ms.wikipedia.org/wiki/Happy_Days' }}">
    <noframes>
        <body>

        </body>
    </noframes>
</frameset>
</html>
