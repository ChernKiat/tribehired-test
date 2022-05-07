<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]--><head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CalcuDoku</title>

        <meta name="Title" content="CalcuDoku" />
        <meta name="description" content="CalcuDoku is a HTML5 game where you have to fill in the grid so that every row and every column contains the digits. Each box must also be calculated according to number and operator, there is only 1 solution for each puzzle.">
		<meta name="keywords" content="calculate, sudoku, grid, cell, math, logic, puzzle, rule, addition, subtraction, multiplication, division">

        <!-- for Facebook -->
        <meta property="og:title" content="CalcuDoku"/>
        <meta property="og:site_name" content="CalcuDoku"/>
        <meta property="og:image" content="/myStartupDemo/calcudoku/share.jpg" />
        <meta property="og:url" content="{{ route('startup-demo.calcudoku') }}" />
        <meta property="og:description" content="CalcuDoku is a HTML5 game where you have to fill in the grid so that every row and every column contains the digits. Each box must also be calculated according to number and operator, there is only 1 solution for each puzzle.">

        <!-- for Twitter -->
        <meta name="twitter:title" content="CalcuDoku" />
        <meta name="twitter:description" content="CalcuDoku is a HTML5 game where you have to fill in the grid so that every row and every column contains the digits. Each box must also be calculated according to number and operator, there is only 1 solution for each puzzle." />
        <meta name="twitter:image" content="/myStartupDemo/calcudoku/share.jpg" />

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

        <link rel="shortcut icon" href="/myStartupDemo/calcudoku/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/myStartupDemo/calcudoku/css/normalize.css">
        <link rel="stylesheet" href="/myStartupDemo/calcudoku/css/main.css">
        <script src="/myStartupDemo/calcudoku/js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!-- PERCENT LOADER START-->
    	<div id="mainLoader"><span>0</span></div>
        <!-- PERCENT LOADER END-->

        <!-- CONTENT START-->
        <div id="mainHolder" class="fontGeneral">
        	<div id="option" class="fitImg">
            	<div id="buttonOption" class="buttonClick buttonIcon buttonOptionOff"><img class="optionOff" alt="Close" src="/myStartupDemo/calcudoku/assets/button_option_close.svg" /><img class="optionOn" alt="Option" src="/myStartupDemo/calcudoku/assets/button_option.svg" /></div>
                <div id="optionList" style="display:none;">
                	<div id="buttonFullscreen" class="buttonClick buttonIcon buttonFullscreen"><img alt="Fullscreen" src="/myStartupDemo/calcudoku/assets/button_fullscreen.svg" /></div>
                    <div id="buttonSound" class="buttonClick buttonIcon buttonSound buttonSoundOn"><img class="soundOff" alt="Sound Off" src="/myStartupDemo/calcudoku/assets/button_sound_off.svg" /><img class="soundOn" alt="Sound On" src="/myStartupDemo/calcudoku/assets/button_sound_on.svg" /></div>
                    <div id="buttonTutorial" class="buttonClick buttonIcon buttonExit"><img alt="Exit" src="/myStartupDemo/calcudoku/assets/button_tutorial.svg" /></div>
                	<div id="buttonExit" class="buttonClick buttonIcon buttonExit"><img alt="Exit" src="/myStartupDemo/calcudoku/assets/button_exit.svg" /></div>

                </div>
            </div>

            <div id="logoHolder">
            	<div class="mainContent fitImg">
                	<div class="gameLogo"><img alt="Begin" src="/myStartupDemo/calcudoku/assets/item_logo.svg"/></div>
                    <div class="action">
                        <div id="buttonStart" class="button buttonClick"><img alt="Start" src="/myStartupDemo/calcudoku/assets/button_start.svg"/></div>
                        <div id="buttonHowToPlay" class="button buttonClick"><img alt="How To Play" src="/myStartupDemo/calcudoku/assets/button_howtoplay.svg"/></div>
                        <div id="buttonGameEasy" class="button buttonClick"><img alt="Easy" src="/myStartupDemo/calcudoku/assets/button_easy.svg"/></div>
                        <div id="buttonGameMedium" class="button buttonClick"><img alt="Medium" src="/myStartupDemo/calcudoku/assets/button_medium.svg"/></div>
                        <div id="buttonGameHard" class="button buttonClick"><img alt="Hard" src="/myStartupDemo/calcudoku/assets/button_hard.svg"/></div>
                        <div id="buttonGameExpert" class="button buttonClick"><img alt="Hard" src="/myStartupDemo/calcudoku/assets/button_expert.svg"/></div>
                    </div>
                </div>
            </div>

            <div id="confirmHolder" class="overlayBg">
            	<div class="confirmContent">
                	<div class="message fontMessage resizeFont ignorePadding" data-fontSize="50" data-lineHeight="50">Are you sure you want to quit the game?</div>
            		<div id="buttonOk" class="okImg buttonClick fitImg"><img src="/myStartupDemo/calcudoku/assets/button_ok.svg"/></div>
                	<div id="buttonCancel" class="cancelImg buttonClick fitImg"><img src="/myStartupDemo/calcudoku/assets/button_cancel.svg"/></div>
                </div>
            </div>

            <div id="tutorialHolder" class="overlayBg">
            	<div class="tutorialContent">
                	<div class="message fontMessage resizeFont ignorePadding" data-fontSize="40" data-lineHeight="40">
                        <div class="title resizeFont" data-fontSize="50" data-lineHeight="50">How to play:</div>
                        <ol>
                            <li>The objective is to fill the grid in with the digits 1 to 3 (depends on the grid size)</li>
                            <li>Each row contains exactly one of each digit</li>
                            <li>Each column contains exactly one of each digit</li>
                            <li>Each bold-outlined group of cells is a cage containing digits which achieve the specified result using the specified mathematical operation: addition (+), subtraction (−), multiplication (×), and division (÷).</li>
                        </ol>
                    </div>
            		<div id="buttonHowToPlayOk" class="okImg buttonClick fitImg"><img src="/myStartupDemo/calcudoku/assets/button_ok.svg"/></div>
                </div>
            </div>

            <div id="gameHolder">
            	<div id="gameStatus" class="fitImg">
                    <div class="gameTimerHolder fontStatus">
                        <div class="gameTimerWrapper fontStatus">
                    	   <div class="gameTimerStatus resizeFont" data-fontSize="60" data-lineHeight="120">00:00</div>
                           <img src="/myStartupDemo/calcudoku/assets/item_timer.svg" />
                        </div>
                    </div>

                    <div class="gameChoiceHolder ignorePadding">
                        <div class="gameChoiceWrapper fontStatus">
                            <div class="gameGuideStatus resizeFont" data-fontSize="30" data-lineHeight="30"></div>
                            <ul>

                            </ul>
                            <div class="gameButtonGuideStatus resizeFont" data-fontSize="30" data-lineHeight="30"></div>
                        </div>
                    </div>

                    <div class="gameResultHolder ignorePadding">
                        <div class="gameResultWrapper fontStatus">
                        	<div class="gameResultStatus resizeFont" data-fontSize="50" data-lineHeight="50"></div>
                            <ul class="resultOption">
                                <div class="gameShareStatus resizeFont" data-fontSize="30" data-lineHeight="30"></div>
                                <li id="buttonFacebook" class="buttonClick">
                                    <img src="/myStartupDemo/calcudoku/assets/button_facebook.svg" />
                                </li>
                                <li id="buttonTwitter" class="buttonClick">
                                    <img src="/myStartupDemo/calcudoku/assets/button_twitter.svg" />
                                </li>
                                <li id="buttonWhatsapp" class="buttonClick">
                                    <img src="/myStartupDemo/calcudoku/assets/button_whatsapp.svg" />
                                </li>
                            </ul>

                            <div id="buttonContinue" class="button buttonClick"><img alt="Cotinue" src="/myStartupDemo/calcudoku/assets/button_continue.svg"/></div>
                        </div>
                    </div>
                </div>

                <div id="puzzleHolder">
					<table id='puzzleTable'>

                    </table>
                </div>
            </div>
        </div>
        <!-- CONTENT END-->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/myStartupDemo/calcudoku/js/vendor/jquery.min.js"><\/script>')</script>

        <script src="/myStartupDemo/calcudoku/js/vendor/detectmobilebrowser.js"></script>
        <script src="/myStartupDemo/calcudoku/js/vendor/createjs.min.js"></script>
		<script src="/myStartupDemo/calcudoku/js/vendor/TweenMax.min.js"></script>

        <script src="/myStartupDemo/calcudoku/js/plugins.js"></script>
        <script src="/myStartupDemo/calcudoku/js/sound.js"></script>
        <script src="/myStartupDemo/calcudoku/js/game.js"></script>
        <script src="/myStartupDemo/calcudoku/js/main.js"></script>
        <script src="/myStartupDemo/calcudoku/js/loader.js"></script>
        <script src="/myStartupDemo/calcudoku/js/init.js"></script>

    </body>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-86567323-37"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-86567323-37');
    </script>


</html>
