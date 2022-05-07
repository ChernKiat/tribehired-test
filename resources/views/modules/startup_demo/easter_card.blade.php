<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Easter Day (Pop Up Card)</title>

        <meta name="Title" content="Easter Day (Pop Up Card)" />
        <meta name="description" content="Easter Day (Pop Up Card) HTML5 Canvas is a Easter Card with pop up booklet effect, open the greetings with cool pop up animation, magic particles and background music.">
		<meta name="keywords" content="pop up, booklet, particles, greeting, cards, holiday, events, invitation, create, custom, ecards, season">

        <!-- for Facebook -->
        <meta property="og:title" content="Easter Day (Pop Up Card)"/>
        <meta property="og:site_name" content="Easter Day (Pop Up Card)"/>
        <meta property="og:image" content="/myStartupDemo/easter_card/share.jpg" />
        <meta property="og:url" content="{{ route('startup-demo.easter-card') }}" />
        <meta property="og:description" content="Easter Day (Pop Up Card) HTML5 Canvas is a Easter Card with pop up booklet effect, open the greetings with cool pop up animation, magic particles and background music.">

        <!-- for Twitter -->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="Easter Day (Pop Up Card)" />
        <meta name="twitter:description" content="Easter Day (Pop Up Card) HTML5 Canvas is a Easter Card with pop up booklet effect, open the greetings with cool pop up animation, magic particles and background music." />
        <meta name="twitter:image" content="/myStartupDemo/easter_card/share.jpg" />

        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<script>
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
            var msViewportStyle = document.createElement("style");
            msViewportStyle.appendChild(
                document.createTextNode(
                    "@-ms-viewport{width:device-width}"
                )
            );
            document.getElementsByTagName("head")[0].
                appendChild(msViewportStyle);
        }
        </script>

        <link rel="shortcut icon" href="/myStartupDemo/easter_card/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/myStartupDemo/easter_card/css/normalize.css">

        <!-- google fonts start -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
        <!-- google fonts end -->

        <link rel="stylesheet" href="/myStartupDemo/easter_card/css/main.css">
        <script src="/myStartupDemo/easter_card/js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!-- PERCENT LOADER START-->
    	<div id="mainLoader"><img id='loaderImg' src="/myStartupDemo/easter_card/assets/easteregg/loader.png" /><br/><span>0</span></div>
        <!-- PERCENT LOADER END-->

        <!-- CONTENT START-->
        <div id="mainHolder">
        	<!-- BROWSER NOT SUPPORT START-->
        	<div id="notSupportHolder">
                <div class="notSupport">YOUR BROWSER ISN'T SUPPORTED.<br/>PLEASE UPDATE YOUR BROWSER IN ORDER TO RUN THE GAME</div>
            </div>
            <!-- BROWSER NOT SUPPORT END-->

            <!-- ROTATE INSTRUCTION START-->
            <div id="rotateHolder">
                <div class="mobileRotate toLandscape">
                	<div class="rotateImg"><img src="/myStartupDemo/easter_card/assets/rotate.png" /></div>
                    <div class="rotateDesc">ROTATE YOUR DEVICE <br/>TO LANDSCAPE</div>
                </div>
                <div class="mobileRotate toPortrait">
                	<div class="rotateImg"><img src="/myStartupDemo/easter_card/assets/rotate_portrait.png" /></div>
                    <div class="rotateDesc">ROTATE YOUR DEVICE <br/>TO PORTRAIT</div>
                </div>
            </div>
            <!-- ROTATE INSTRUCTION END-->

            <!-- CANVAS START-->
            <div id="canvasHolder">
                <canvas id="appCanvas" width="1920" height="1080"></canvas>
            </div>
            <!-- CANVAS END-->

        </div>
        <!-- CONTENT END-->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/myStartupDemo/easter_card/js/vendor/jquery.min.js"><\/script>')</script>

        <script src="/myStartupDemo/easter_card/js/vendor/detectmobilebrowser.js"></script>
        <script src="/myStartupDemo/easter_card/js/vendor/createjs.min.js"></script>
		<script src="/myStartupDemo/easter_card/js/vendor/TweenMax.min.js"></script>

        <script src="/myStartupDemo/easter_card/js/plugins.js"></script>
        <script src="/myStartupDemo/easter_card/js/sound.js"></script>
        <script src="/myStartupDemo/easter_card/js/canvas.js"></script>
        <script src="/myStartupDemo/easter_card/js/mobile.js"></script>
        <script src="/myStartupDemo/easter_card/js/app.js"></script>
        <script src="/myStartupDemo/easter_card/js/loader.js"></script>
        <script src="/myStartupDemo/easter_card/js/init.js"></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-9DPC3BM7TK"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-9DPC3BM7TK');
        </script>
    </body>

</html>
