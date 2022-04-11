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
    <div></div>
    <script type="text/javascript">
        async function file_get_contents(uri, callback) {
            let res = await fetch(uri),
                ret = await res.text();
            return callback ? callback(ret) : ret; // a Promise() actually.
        }

        file_get_contents("{{ $project ?? 'https://ms.wikipedia.org/wiki/Happy_Days' }}", console.log);
        // or
        file_get_contents("{{ $project ?? 'https://ms.wikipedia.org/wiki/Happy_Days' }}").then(ret => console.log(ret));
    </script>
</body>
</html>
