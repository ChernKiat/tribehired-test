    EnsureNamespace("Scrabble.Core");
    EnsureNamespace("Scrabble.UI");

    var IDPrefix_Board_SquareOrTile = "BoardSquareOrTile_";
    var IDPrefix_Rack_SquareOrTile = "RackSquareOrTile_";
    var IDPrefix_Letters_SquareOrTile = "LettersSquareOrTile_";

    function PlayAudio(id)
    {
        var audio = document.getElementById(id);
        
        /*
        audio.load()
        audio.addEventListener("load", function() {

        }, true);
        */

        if (audio.playing)
        {
            audio.pause();
        }
        
        audio.defaultPlaybackRate = 1;
        audio.volume = 1;
        
        try
        {
            audio.currentTime = 0;
            audio.play();
        }
        catch(e)
        {
            function currentTime()
            {
                audio.currentTime = 0;
                audio.removeEventListener("canplay", currentTime, true);
                audio.play();
            }
            audio.addEventListener("canplay", currentTime, true);
            //alert("DEBUG 2" + e.message);
        }
    }
