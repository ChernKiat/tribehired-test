<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="/myStartupDemo/slot_machine/css/reset.css" type="text/css">
        <link rel="stylesheet" href="/myStartupDemo/slot_machine/css/main.css" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
	    <meta name="msapplication-tap-highlight" content="no"/>

        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/createjs-2013.12.12.min.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/ctl_utils.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/sprite_lib.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/settings.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CSlotSettings.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CLang.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CPreloader.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CMain.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CTextButton.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CGfxButton.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CToggle.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CBetBut.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CMenu.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CGame.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CReelColumn.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CInterface.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CPayTablePanel.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CStaticSymbolCell.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CTweenController.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CBonusPanel.js"></script>
        <script type="text/javascript" src="/myStartupDemo/slot_machine/js/CScoreText.js"></script>

    </head>
    <body ondragstart="return false;" ondrop="return false;" >
	<div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
          <script>
            $(document).ready(function(){
                     var oMain = new CMain({
                                    win_occurrence:40,        //WIN PERCENTAGE.SET A VALUE FROM 0 TO 100.
                                    slot_cash: 200,          //THIS IS THE CURRENT SLOT CASH AMOUNT. THE GAME CHECKS IF THERE IS AVAILABLE CASH FOR WINNINGS.
                                    bonus_occurrence:15,      //SET BONUS OCCURRENCE PERCENTAGE IF PLAYER GET A WIN. SET A VALUE FROM 0 TO 100. (IF 100%, PLAYER GET A BONUS EVERYTIME THERE IS A WIN).
                                    min_reel_loop:1,          //NUMBER OF REEL LOOPS BEFORE SLOT STOPS
                                    reel_delay: 0,            //NUMBER OF FRAMES TO DELAY THE REELS THAT START AFTER THE FIRST ONE
                                    time_show_win:2000,       //DURATION IN MILLISECONDS OF THE WINNING COMBO SHOWING
                                    time_show_all_wins: 2000, //DURATION IN MILLISECONDS OF ALL WINNING COMBO
                                    money:100,               //STARING CREDIT FOR THE USER
                                    min_bet:0.05,             //MINIMUM COIN FOR BET
                                    max_bet: 0.5,             //MAXIMUM COIN FOR BET
                                    max_hold:3,               //MAXIMUM NUMBER OF POSSIBLE HOLD ON REELS
                                    perc_win_prize_1: 50,       //OCCURENCE PERCENTAGE FOR PRIZE 1 IN BONUS
                                    perc_win_prize_2: 35,       //OCCURENCE PERCENTAGE FOR PRIZE 2 IN BONUS
                                    perc_win_prize_3: 15,       //OCCURENCE PERCENTAGE FOR PRIZE 3 IN BONUS
                                    num_symbol_bonus:3,        //NUMBER OF BONUS SYMBOLS (DEFAULT IS SYMBOL 9) THAT MUST BE SHOWN TO ACHIEVE THE BONUS PANEL
                                    num_spin_ads_showing:10     //NUMBER OF SPIN TO COMPLETE, BEFORE TRIGGERING AD SHOWING.
                                    //// THIS FUNCTIONALITY IS ACTIVATED ONLY WITH CTL ARCADE PLUGIN.///////////////////////////
                                    /////////////////// YOU CAN GET IT AT: /////////////////////////////////////////////////////////
                                    // http://codecanyon.net/item/ctl-arcade-wordpress-plugin/13856421 ///////////
                                });

                $(oMain).on("start_session", function (evt) {
                    if(getParamValue('ctl-arcade') === "true"){
                        parent.__ctlArcadeStartSession();
                    }
                    //...ADD YOUR CODE HERE EVENTUALLY
                });

                $(oMain).on("end_session", function (evt) {
                    if(getParamValue('ctl-arcade') === "true"){
                        parent.__ctlArcadeEndSession();
                    }
                    //...ADD YOUR CODE HERE EVENTUALLY
                });

                $(oMain).on("save_score", function (evt, iMoney) {
                    if(getParamValue('ctl-arcade') === "true"){
                        parent.__ctlArcadeSaveScore({score:iMoney});
                    }
                    //...ADD YOUR CODE HERE EVENTUALLY
                });

                $(oMain).on("show_interlevel_ad", function (evt) {
                    if(getParamValue('ctl-arcade') === "true"){
                        parent.__ctlArcadeShowInterlevelAD();
                    }
                    //...ADD YOUR CODE HERE EVENTUALLY
                });

                $(oMain).on("share_event", function (evt, oData) {
                    if(getParamValue('ctl-arcade') === "true"){
                        parent.__ctlArcadeShareEvent(oData);
                    }
                    //...ADD YOUR CODE HERE EVENTUALLY
                });

                if(isIOS()){
                    setTimeout(function(){sizeHandler();},200);
                }else{
                    sizeHandler();
                }
           });

        </script>
        <canvas id="canvas" class='ani_hack' width="1500" height="640"> </canvas>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>
    </body>
</html>
