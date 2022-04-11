<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="https://demonisblack.com/code/2022/easterday/app" property="og:url">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script>
    $(document).ready(function() {
        var scriptTag = '<script>' +

        'function extractDomain(url) {'+
            'var domain;'+
            'if (url.indexOf("://") > -1) {'+
                "domain = url.split('/')[2];"+
            '}'+
            'else {'+
                "domain = url.split('/')[0];"+
            '}'+
            "domain = domain.split(':')[0];"+
            'return domain;'+
        '}'+
                            // "$(\"meta[property='og:url']\").attr('content', 'http://seal.localhost.com/my/1/showcase/easterday');" +
                            // "console.log(document.location.href, extractDomain(document.location.href));" +
                            // "console.log($(\"meta[property='og:url']\").attr('content'));" +


                            "setTimeout(function() { $('#overlay, #overlayLink').remove(); console.log($('#overlay, #overlayLink').length); }, 2000);" +


                            // '$(document).ready(function() {' +
                            // "console.log('lol');" +
                            // "setInterval(function() { $('body > :nth-child(2)').hide(); console.log($('body > :nth-child(2)')); }, 1000);" +
                            // "setInterval(function() { var urlRequest = ['seal.localhost.com', 'localhost']; }, 1000);" +
                            // '});' +
                        '<\/script>';
        // $("#myHijackFrame").contents().find("body").append(scriptTag);
        setInterval(function() { $("#myHijackFrame").contents().find("#overlay").empty(); console.log('lol'); }, 100);
        $("#myHijackFrame").contents().find('#overlay, #overlayLink').each(function (index, element) {
            $(this).remove()
            console.log("removed one linkUp");
        });
    });
    </script>
</head>
<body>
    <iframe id="myHijackFrame" src="{{ $project ?? 'https://ms.wikipedia.org/wiki/Happy_Days' }}" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
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
