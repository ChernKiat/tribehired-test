<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

        <title>Laravel</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
        </style>

        <!-- Math Genius -->
        <link href="/myMathGenius/css/main.css" rel="stylesheet">
        <script src="/myMathGenius/js/library/jquery-1.4.3.min.js"></script>
        {{-- <script src="/js/jquery.event.drag-2.0.js"></script> --}}
        {{-- <script src="/myMathGenius/js/library/jquery.animate-enhanced.min.js"></script> --}}
        <script src="/myMathGenius/js/library/jquery.blockUI.js"></script>
        <script src="/myMathGenius/js/library/jquery-css-transform.js"></script>
        <script src="/myMathGenius/js/library/jquery-animate-css-rotate-scale.js"></script>
        <script src="/myMathGenius/js/library/rotate3Di.js"></script>
        <script src="/myMathGenius/js/library/jquery-ui-1.8.7.custom.min.js"></script>
        {{-- <script src="/js/dictionary_English.js"></script> --}}
        {{-- <script src="/myMathGenius/js/myCommentOut.js"></script> --}}
        <script src="/myMathGenius/js/BinFileReader.js"></script>
        <script>var _sf_startpt=(new Date()).getTime()</script>

        <script src="/myMathGenius/js/myCombineBaseGame.js"></script>

        <script src="/myMathGenius/js/myExtra.js"></script>
        {{-- <script src="/js/dictionary.js"></script> --}}
        <script src="/myMathGenius/js/myButtonFunction.js"></script>

        

        <!--[if IE]> 
        <style type="text/css" media="all">
        div.surroundShade table,
        div.bottomShade table
        {
        zoom: 1;
        filter: progid:DXImageTransform.Microsoft.Shadow(color='#333333', Direction=135, Strength=3);
        }
        </style>
        <![endif]--> 

        <!--[if lt IE 7]>
        <style type="text/css" media="all">
        .cie654 {display: block}
        </style>
        <![endif]--> 

        <!--[if !lt IE 7]>
        <style type="text/css" media="all">
        .cie7 {display: block}
        </style>        
        <![endif]--> 

        <!--[if IE 8]>
        <style type="text/css" media="all">
        .cie8 {display: block}
        </style>        
        <![endif]--> 

        <!--[if !IE]>--> <style type="text/css" media="all">.cnotie {display: block}</style> <!--<![endif]-->
    </head>
    <body id="body">
        <audio id="audio0" src="/myMathGenius/audio/20258__Koops__Page_Turn_24.wav" preload="auto" type="audio/wav"></audio>
        <audio id="audio1" src="/myMathGenius/audio/1257__Anton__hit_on_wood.wav" preload="auto" type="audio/wav"></audio>
        <audio id="audio2" src="/myMathGenius/audio/1258__Anton__hit_on_wood2.wav" preload="auto" type="audio/wav"></audio>
        <audio id="audio3" src="/myMathGenius/audio/37161__volivieri__soccer_stomp_01.wav" preload="auto" type="audio/wav"></audio>
        <audio id="audio4" src="/myMathGenius/audio/54405__KorgMS2000B__Button_Click.wav" preload="auto" type="audio/wav"></audio>
        <audio id="audio5" src="/myMathGenius/audio/32435__HardPCM__Wood012.wav" preload="auto" type="audio/wav"></audio>
        
        <div id="title">
            <span>Math Genius</span>
        </div>

        <div style="background-color: black; padding: 1em;">
            <p>
            Language:&nbsp;
            <span id="language"></span>&nbsp;
                <input type="submit" onclick="setLanguage('French');" value="French"></input>
                <input type="submit" onclick="setLanguage('English');" value="English"></input>
            </p>

            <p>
                <input id="text_field" type="text" onmousedown="resetTextField();" value="[type word here]" size="15" style="width: 25em"></input>
                <input type="submit" onclick="checkWord();" value="Check dictionary"></input>
                <input type="submit" onclick="findAnagrams();" value="Find anagrams"></input>
            </p>

            <div id="anagrams" class="tableDivWrap"></div>
        </div>

        <p>
            <input type="submit" onclick="emptyBoardAndRack();" value="Empty board and rack"></input>
        </p>
        <p>
            <input type="submit" onclick="replenishRack();" value="Replenish rack"></input>
            <input type="submit" onclick="placeRandomTiles_Rack();" value="Randomize rack"></input>
        </p>
        <p>
            <input type="submit" onclick="placeRandomTiles_Board();" value="Randomize board"></input>
            <input type="submit" onclick="placeTileSet();" value="Display entire tile set"></input>
        </p>

        <div id="rack" class="tableDivWrap bottomShade"></div>

        <!-- p>
            <input type="submit" onclick="playAction_resetTilesOnRack();" value="Take tiles back"></input>
            <input type="submit" onclick="playAction_submitWords();" value="Submit word(s)"></input>
            <input type="submit" onclick="playAction_drawNewTiles();" value="Draw new tiles (pass turn)"></input>
            <input type="submit" onclick="playAction_passTurn();" value="Pass turn"></input>
        </p -->
        <p>
            <input type="submit" onclick="verifyBoard();" value="Verify board"></input>
        </p>
        <div id="board" class="tableDivWrap surroundShade"></div>

        <div id="letters" class="tableDivWrap" style="display:none;"></div>
    </body>
</html>
