<!doctype html>
<html lang="en">
<head>
    <title>{{ config('nft.title.bayc') }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
    <link rel="icon" href="0d090ca3534b3dd85dc9.ico" type="image/x-icon"/>
    <meta name="theme-color" content="#000000"/>
    <meta name="description" content="{{ config('nft.meta.description.bayc') }}"/>
    <meta name="twitter:card" content="{{ config('nft.meta.twitter.card.bayc') }}"/>
    <meta name="twitter:title" content="{{ config('nft.title.bayc') }}"/>
    <meta name="twitter:description" content="{{ config('nft.meta.description.bayc') }}"/>
    <meta name="twitter:image" content="{{ config('nft.meta.twitter.image.bayc') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.7/css/fork-awesome.min.css" integrity="sha256-gsmEoJAws/Kd3CjuOQzLie5Q3yshhvmo7YNtBG7aaEY=" crossorigin="anonymous"/>
    <script defer="defer" src="main.55395f15c2d6e52f8e8f.js" type="58db95d160810133571dd660-text/javascript"></script>

    <meta http-equiv="x-ua-compatible" content="ie=edge"/>


    <link rel="stylesheet" type="text/css" media="all" href="{{ mix('assets/css/maneki.css') }}">
    @yield('styles')
</head>
<body>
    <noscript>You need to enable JavaScript to run this app.</noscript>

    <section id="battle" class="battle">
        <div class="inner-wrap">
            <div class="column spacer"></div>
            <div class="column">
                <div class="content">
                    <div class="mobile-image">
                        <img src="/static/media/BattleMobile.94e944eb.png" alt="Battle">
                    </div>
                    <div class="heading">
                        <h2 class="outline" title="Battle">Battle</h2>
                        <h3>Fight for victory!</h3>
                    </div>
                    <p>Get ready to battle your pets, over the course of Cooltopia being revealed we will be working on hard in the background on battling. We want to get this right so it's going to take us a while, but we are super excited for it.</p>
                    <img class="category-icon" src="/static/media/Icon-Battle.ccd35a2c.png" alt="">
                    <svg class="top-left-corner" width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.29156 16.7897C2.15462 1.58794 1.19287 2.92855 19.1928 2.42855" stroke="#222" stroke-width="4" stroke-linecap="round"></path></svg>
                    <svg class="bottom-right-corner" width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.4544 2.70991C19.9097 17.9054 23.5 18.5 2.85766 17.4219" stroke="#222" stroke-width="4" stroke-linecap="round"></path></svg>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ mix('assets/js/maneki.js') }}"></script>
    @yield('scripts')
</body>
</html>


