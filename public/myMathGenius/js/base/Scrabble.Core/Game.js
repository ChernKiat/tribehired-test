    //=================================================
    // BEGIN Scrabble.Core.Game ------------------
    if (typeof _OBJECT_ROOT_.Scrabble.Core.Game == "undefined" || !_OBJECT_ROOT_.Scrabble.Core["Game"])
    _OBJECT_ROOT_.Scrabble.Core.Game = (function(){
    //var _Game = {}; // 'this' object (to be returned at the bottom of the containing auto-evaluated function)

    //console.log("inside Scrabble.Core.Game code scope");

    with (Scrabble.Core)
    {
        
    //function _Game()
    var _Game = function(board, rack)
    {
        //console.log("Scrabble.Core.Game constructor");
        
        this.Board = board;
        board.Game = this;
        
        this.Rack = rack;
        rack.Game = this;
        
        function initLetterDistributions_()
        {
            var data = arguments[0];
            var language = arguments[1];
            
            var tiles = [];
            var letters = [];
            
            for (var i = 0; i < data.length; ++i)
            {
                var item = data[i];
                
                var tile = new Tile(item.Top, item.Right, item.Bottom, item.Left);
                letters.push(tile);
                
                for (var n = 0; n < item.Count; ++n)
                {
                var tile = new Tile(item.Top, item.Right, item.Bottom, item.Left);
                    tiles.push(tile);
                }
            }
            
            letters.sort(function(a,b){ 
                var a = a.Top;
                var b = b.Top;

                if (a < b) return -1;
                if (a > b) return 1;
                return 0;
            });
            
            console.log(this.LetterDistributions, tiles)
            this.LetterDistributions.push({Language: language, Tiles: tiles, Letters: letters});
        }
        
        function initLetterDistributions_English()
        {
            var data = [
                {Top: Tile.prototype.BlankLetter, Right: 0, Bottom: 2, Left: 6, Count: 2},

                {Top: 20, Right: 1, Bottom: 12, Left: 5, Count: 2},
                {Top: 19, Right: 2, Bottom: 13, Left: 7, Count: 2},
                {Top: 18, Right: 1, Bottom: 14, Left: 9, Count: 2},
                {Top: 17, Right: 2, Bottom: 15, Left: 7, Count: 2},
                {Top: 16, Right: 1, Bottom: 16, Left: 5, Count: 2},
                {Top: 15, Right: 2, Bottom: 17, Left: 7, Count: 2},
                {Top: 14, Right: 1, Bottom: 18, Left: 9, Count: 2},
                {Top: 13, Right: 2, Bottom: 19, Left: 7, Count: 2},
                {Top: 12, Right: 1, Bottom: 20, Left: 5, Count: 2},
                {Top: 20, Right: 10, Bottom: 1, Left: 4, Count: 2}
            ];
            
            initLetterDistributions_.apply(this, [data, "English"]);
        }
        
        function initLetterDistributions_French()
        {
            var data = [
                {Top: Tile.prototype.BlankLetter, Right: 0, Bottom: 2, Left: 6},

                {Top: 20, Right: 1, Bottom: 12, Left: 5, Count: 2},
                {Top: 19, Right: 2, Bottom: 13, Left: 7, Count: 2},
                {Top: 18, Right: 1, Bottom: 14, Left: 9, Count: 2},
                {Top: 17, Right: 2, Bottom: 15, Left: 7, Count: 2},
                {Top: 16, Right: 1, Bottom: 16, Left: 5, Count: 2},
                {Top: 15, Right: 2, Bottom: 17, Left: 7, Count: 2},
                {Top: 14, Right: 1, Bottom: 18, Left: 9, Count: 2},
                {Top: 13, Right: 2, Bottom: 19, Left: 7, Count: 2},
                {Top: 12, Right: 1, Bottom: 20, Left: 5, Count: 2},
                {Top: 20, Right: 10, Bottom: 1, Left: 4, Count: 2}
            ];
            
            initLetterDistributions_.apply(this, [data, "French"]);
        }
        
        // TODO: parse data from JSON ?
        initLetterDistributions_French.apply(this);
        initLetterDistributions_English.apply(this);
        
        this.SetLanguage("French");
    }

    _Game.prototype.Dictionary = 0;

    _Game.prototype.Event_ScrabbleLetterTilesReady = "ScrabbleLetterTilesReady";
    //_Game.prototype.SquaresList = [];

    _Game.prototype.Board = 0;
    _Game.prototype.Rack = 0;

    _Game.prototype.LetterDistributions = [];

    _Game.prototype.SquareBlankLetterInWaitingBoard = 0;
    _Game.prototype.SquareBlankLetterInWaitingRack = 0;

    _Game.prototype.Language = "";


    _Game.prototype.SetLanguage = function(language)
    {
        if (language == "French")
        {
            this.Language = language;
            EventsManager.DispatchEvent(this.Event_ScrabbleLetterTilesReady, { 'Game': this });
            
            this.Dictionary = new Dictionary("/myMathGenius/DAWG_ODS5_French.dat", this.Board.Dimension, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        }
        else if (language == "English")
        {
            this.Language = language;
            EventsManager.DispatchEvent(this.Event_ScrabbleLetterTilesReady, { 'Game': this });
            
            this.Dictionary = new Dictionary("/myMathGenius/DAWG_SOWPODS_English.dat", this.Board.Dimension, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        }
        else
        {
            throw new Error("Unsupported language: " + language);
        }
    }

    _Game.prototype.MoveTile = function(tileXY, squareXY)
    {
        if (tileXY.y == -1 && squareXY.y == -1)
        {
            this.Rack.MoveTile(tileXY, squareXY);
            return;
        }
        
        if (tileXY.y != -1 && squareXY.y != -1)
        {
            this.Board.MoveTile(tileXY, squareXY);
            return;
        }
        
        if (tileXY.y == -1) // RACK to BOARD
        {
            var square1 = this.Rack.SquaresList[tileXY.x];
            var square2 = this.Board.SquaresList[squareXY.x + this.Board.Dimension * squareXY.y];
            
            var tile = square1.Tile;
            square1.PlaceTile(0, false);
            square2.PlaceTile(tile, false);

            //var random = Math.floor(Math.random()*3);
            //var audio = document.getElementById(square2.Type == SquareType.Normal ? 'audio2' : 'audio3');
            //var audio = document.getElementById('audio' + (random+1));
            PlayAudio('audio4');
            
            EventsManager.DispatchEvent(this.Rack.Event_ScrabbleRackSquareTileChanged, { 'Rack': this.Rack, 'Square': square1 });
            EventsManager.DispatchEvent(this.Board.Event_ScrabbleBoardSquareTileChanged, { 'Board': this.Board, 'Square': square2 });
            
            return;
        }
        
        if (squareXY.y == -1) // BOARD to RACK
        {
            var square1 = this.Board.SquaresList[tileXY.x + this.Board.Dimension * tileXY.y];
            var square2 = this.Rack.SquaresList[squareXY.x];
            
            var tile = square1.Tile;
            square1.PlaceTile(0, false);
            square2.PlaceTile(tile, false);

            //var random = Math.floor(Math.random()*3);
            //var audio = document.getElementById(square2.Type == SquareType.Normal ? 'audio2' : 'audio3');
            //var audio = document.getElementById('audio' + (random+1));
            PlayAudio('audio4');
            
            EventsManager.DispatchEvent(this.Board.Event_ScrabbleBoardSquareTileChanged, { 'Board': this.Board, 'Square': square1 });
            EventsManager.DispatchEvent(this.Rack.Event_ScrabbleRackSquareTileChanged, { 'Rack': this.Rack, 'Square': square2 });
            
            return;
        }
    }

    _Game.prototype.toString = function()
    {
        return "Scrabble.Core.Game toString(): TODO ... ";
    }

    } // END - with (Scrabble.Core)

    return _Game;
    })();
    // END Scrabble.Core.Game ------------------
    //=================================================
