    //=================================================
    // BEGIN Scrabble.Core.Board ------------------
    if (typeof _OBJECT_ROOT_.Scrabble.Core.Board == "undefined" || !_OBJECT_ROOT_.Scrabble.Core["Board"])
    _OBJECT_ROOT_.Scrabble.Core.Board = (function(){
    //var _Board = {}; // 'this' object (to be returned at the bottom of the containing auto-evaluated function)

    //console.log("inside Scrabble.Core.Board code scope");

    with (Scrabble.Core)
    {

    //function _Board()
    var _Board = function()
    {
        function CreateGrid()
        {
            for (var y = 0; y < this.Dimension; y++)
            {
                for (var x = 0; x < this.Dimension; x++)
                {
                    var centerStart = false;

                    //SquareType.Normal, SquareType.AddFive, SquareType.SubtractFive, SquareType.MultiplyTwo, SquareType.DivideTwo
                    var square = new Square(SquareType.Normal);
                    
                    var middle = Math.floor(this.Dimension / 2);
                    var halfMiddle = Math.floor(middle / 2);
                    
                    if (
                        (x == 0 || x == this.Dimension - 1 || x == middle)
                        &&
                        (y == 0 || y == this.Dimension - 1 || y == middle && x != middle)
                    ) {
                        square = new Square(SquareType.MultiplyTwo);
                    } else if (
                        x == middle
                        &&
                        y == middle
                    ) {
                        centerStart = true;
                    } else if (
                        (x == halfMiddle || x == this.Dimension - 1 - halfMiddle)
                        &&
                        (y == halfMiddle || y == this.Dimension - 1 - halfMiddle)
                    ) {
                        square = new Square(SquareType.DivideTwo);
                    } else if (
                        (x == 1 || x == this.Dimension - 1 - 1) && (y == middle + 1 || y == middle - 1)
                        ||
                        (y == 1 || y == this.Dimension - 1 - 1) && (x == middle + 1 || x == middle - 1)
                    ) {
                        square = new Square(SquareType.SubtractFive);
                    } else if (
                        (x == halfMiddle - 1 || x == this.Dimension - 1 - halfMiddle + 1)
                            && (y == halfMiddle + 1 || y == this.Dimension - 1 - halfMiddle - 1)
                        ||
                        (y == halfMiddle - 1 || y == this.Dimension - 1 - halfMiddle + 1)
                            && (x == halfMiddle + 1 || x == this.Dimension - 1 - halfMiddle - 1)
                    ) {
                        square = new Square(SquareType.AddFive);
                    }
                    
                    square.X = x;
                    square.Y = y;
                    this.SquaresList.push(square);
                }
            }
            
            EventsManager.DispatchEvent(this.Event_ScrabbleBoardReady, { 'Board': this });
        }

        function SetDimension(val)
        {
            if (typeof val != 'number')
                throw new Error("Illegal argument Scrabble.Core.Board.SetDimension(), not a number: " + typeof val);
            
            if (val < 13)
                throw new Error("Illegal argument Scrabble.Core.Board.SetDimension(), number smaller than 13: " + val);
            
            this.Dimension = val;
        }

        //_Board.prototype.SetDimensions = function()
        function SetDimensions()
        {
            if (this instanceof _Board)
            {
                if (arguments.length > 0)
                {
                    //console.log("typeof ARGS: " + typeof arguments[0]);
                    //console.log("typeof ARGS: " + type_of(arguments[0]));
                
                    switch (type_of(arguments[0]))
                    {
                        case 'number':
                            SetDimension.apply(this, [arguments[0]]);
                            //console.log("Scrabble.Core.Board 'number' constructor: " + this.toString());
                            return;
                        case 'object':
                            SetDimension.apply(this, [arguments[0]['Dimension']]);
                            //console.log("Scrabble.Core.Board 'object' constructor: " + this.toString());
                            return;
                        case 'array':
                        default:
                            var argumentsString = "";
                            for (var i = 0; i < arguments.length; i++)
                            {
                                argumentsString += arguments[0] + ", ";
                            }
                            throw new Error("Illegal arguments Scrabble.Core.Board.SetDimensions(): " + argumentsString);
                            break;
                    }
                }
                else
                {
                    this.Dimension = 13;
                    //console.log("Scrabble.Core.Board constructor with empty parameters (default "+this.Dimension+"x"+this.Dimension+")");
                }
            }
            else
            {
                throw new Error('Illegal method call Scrabble.Core.Board.SetDimensions() on :' + typeof this);
            }
        }
        
        //console.log("Scrabble.Core.Board constructor before applying parameters");
        SetDimensions.apply(this, arguments);
        //SetDimensions(arguments);
        
        CreateGrid.apply(this);
    }

    _Board.prototype.Event_ScrabbleBoardReady = "ScrabbleBoardReady";
    _Board.prototype.Event_ScrabbleBoardSquareTileChanged = "ScrabbleBoardSquareTileChanged";
    _Board.prototype.Event_ScrabbleBoardSquareStateChanged = "ScrabbleBoardSquareStateChanged";

    _Board.prototype.Dimension = NaN;

    _Board.prototype.SquaresList = [];

    _Board.prototype.Game = 0;

    _Board.prototype.CheckDictionary = function()
    {
        var word = "";
        var wordSquares = [];
        
        var validHorizontalWords = [];
        var validVerticalWords = [];

        var invalidSquares = [];

        var middle = Math.floor(this.Dimension / 2);
        var square = this.SquaresList[middle + this.Dimension * middle];
        if (square.Tile == 0)
        {
            EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 1 });
        
            invalidSquares.push(square);
        }

        for (var y = 0; y < this.Dimension; y++)
        {
            for (var x = 0; x < this.Dimension; x++)
            {
                var square = this.SquaresList[x + this.Dimension * y];
                if (square.Tile != 0)
                {
                    wordSquares.push(square);
                    // word += square.Tile.Letter;
                }
                else
                {
                    if (word.length <= 1 || !this.Game.Dictionary.CheckWord(word))
                    {
                        for (var i = 0; i < wordSquares.length; i++)
                        {
                            var square = wordSquares[i];
                            var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
                            var td = document.getElementById(id).parentNode;
                            
                            //$(td).addClass("Invalid");
                            EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 1 });
                            
                            invalidSquares.push(square);
                        }
                    }
                    else
                    {
                        var newArray = wordSquares.slice();
                        validHorizontalWords.push(newArray);
                    }
                    
                    word = "";
                    wordSquares = [];
                }
            }
        }
        
        for (var x = 0; x < this.Dimension; x++)
        {
            for (var y = 0; y < this.Dimension; y++)
            {
                var square = this.SquaresList[x + this.Dimension * y];
                if (square.Tile != 0)
                {
                    wordSquares.push(square);
                    // word += square.Tile.Letter;
                }
                else
                {
                    if (word.length <= 1 || !this.Game.Dictionary.CheckWord(word))
                    {
                        for (var i = 0; i < wordSquares.length; i++)
                        {
                            var square = wordSquares[i];
                            var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
                            var td = document.getElementById(id).parentNode;
                            
                            $(td).addClass("Invalid");
                            EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 1 });
                            
                            invalidSquares.push(square);
                        }
                    }
                    else
                    {
                        var newArray = wordSquares.slice();
                        validVerticalWords.push(newArray);
                    }
                    
                    word = "";
                    wordSquares = [];
                }
            }
        }

        for (var i = 0; i < validHorizontalWords.length; i++)
        {
            wordSquares = validHorizontalWords[i];
            for (var j = 0; j < wordSquares.length; j++)
            {
                var square = wordSquares[j];
                var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
                var td = document.getElementById(id).parentNode;
                //$(td).removeClass("Invalid");
                //$(td).addClass("Valid");
                EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 0 });
                
                for (var k = 0; k < invalidSquares.length; k++)
                {
                    if (invalidSquares[k] == square)
                    {
                        invalidSquares.splice(k--, 1);
                    }
                }
            }
        }
        
        for (var i = 0; i < validVerticalWords.length; i++)
        {
            wordSquares = validVerticalWords[i];
            
            for (var j = 0; j < wordSquares.length; j++)
            {
                var square = wordSquares[j];
                //TODO: check if there is a path to the center square
                //TODO: check played tiles (!Tile.Locked) are vertical XOR horizontal, and without gaps
                //EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 2 });
            }
            
            for (var j = 0; j < wordSquares.length; j++)
            {
                var square = wordSquares[j];
                var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
                var td = document.getElementById(id).parentNode;
                //$(td).removeClass("Invalid");
                //$(td).addClass("Valid");
                EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareStateChanged, { 'Board': this, 'Square': square, 'State': 0 });
                
                for (var k = 0; k < invalidSquares.length; k++)
                {
                    if (invalidSquares[k] == square)
                    {
                        invalidSquares.splice(k--, 1);
                    }
                }
            }
        }

        return invalidSquares.length == 0;
    }

    _Board.prototype.RemoveFreeTiles = function()
    {
        var tiles = [];
        
        for (var y = 0; y < this.Dimension; y++)
        {
            for (var x = 0; x < this.Dimension; x++)
            {
                var square = this.SquaresList[x + this.Dimension * y];
            
                if (square.Tile != 0 && !square.Tile.Locked)
                {
                    tiles.push(square.Tile);
                    
                    square.PlaceTile(0, false);
                
                    EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
                }
            }
        }
        
        return tiles;
    }

    _Board.prototype.EmptyTiles = function()
    {
        for (var y = 0; y < this.Dimension; y++)
        {
            for (var x = 0; x < this.Dimension; x++)
            {
                var square = this.SquaresList[x + this.Dimension * y];
            
                square.PlaceTile(0, false);
                
                EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
            }
        }
    }

    _Board.prototype.MoveTile = function(tileXY, squareXY)
    {
        if (tileXY.y == -1 || squareXY.y == -1)
        {
            if (this.Game != 0)
            {
                this.Game.MoveTile(tileXY, squareXY);
            }
            
            return;
        }
        
        var square1 = this.SquaresList[tileXY.x + this.Dimension * tileXY.y];
        var square2 = this.SquaresList[squareXY.x + this.Dimension * squareXY.y];

        var tile = square1.Tile;
        square1.PlaceTile(0, false);
        square2.PlaceTile(tile, false);

        //var random = Math.floor(Math.random()*3);
        //var audio = document.getElementById(square2.Type == SquareType.Normal ? 'audio2' : 'audio3');
        //var audio = document.getElementById('audio' + (random+1));
        PlayAudio('audio4');
        
        EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square1 });
        EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square2 });
    }

    _Board.prototype.GenerateTilesLetterDistribution = function()
    {
        PlayAudio('audio0');
        
        this.Game.Rack.EmptyTiles();
        this.EmptyTiles();
        
        var letterDistribution = 0;
        for (var i = 0; i < this.Game.LetterDistributions.length; ++i)
        {
            var ld = this.Game.LetterDistributions[i];
            if (ld.Language == this.Game.Language)
            {
                letterDistribution = ld;
            }
        }
        
        var i = -1;
        
        for (var y = 0; y < this.Dimension; y++)
        {
            for (var x = 0; x < this.Dimension; x++)
            {
                i++;
                
                var centerStart = false;
            
                var square = this.SquaresList[x + this.Dimension * y];
            
                var middle = Math.floor(this.Dimension / 2);
                var halfMiddle = Math.ceil(middle / 2);

                if (i < letterDistribution.Tiles.length)
                {
                    var locked = 1; // Math.floor(Math.random() * 2);
                    var tile = letterDistribution.Tiles[i];
                    square.PlaceTile(tile, locked == 1 ? true : false);
                
                    EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
                }
                else if (square.Tile != 0)
                {
                    square.PlaceTile(0, false);
                    
                    EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
                }
            }
        }
    }

    _Board.prototype.GenerateRandomTiles = function()
    {
        PlayAudio('audio0');
        
        board.EmptyTiles();
        
        var totalPlaced = 0;
        
        for (var y = 0; y < this.Dimension; y++)
        {
            for (var x = 0; x < this.Dimension; x++)
            {
                var centerStart = false;
            
                var square = this.SquaresList[x + this.Dimension * y];
            
                var middle = Math.floor(this.Dimension / 2);
                var halfMiddle = Math.ceil(middle / 2);
            
                var makeTile = Math.floor(Math.random()*2);
                if (makeTile) // && y <= middle)
                {
                    /*
                    var letters = Tile.prototype.BlankLetter + "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    var letter_index = Math.floor(Math.random() * letters.length);
                    var letter = letters.substring(letter_index, letter_index+1);
                
                    var score = Math.floor(Math.random()*10) + 1;
                    if (letter == Tile.prototype.BlankLetter) score = 0;
                    */
                    
                    var letterDistribution = 0;
                    for (var i = 0; i < this.Game.LetterDistributions.length; ++i)
                    {
                        var ld = this.Game.LetterDistributions[i];
                        if (ld.Language == this.Game.Language)
                        {
                            letterDistribution = ld;
                        }
                    }

                    var lastFreeTile = -1;
                    for (var i = 0; i < letterDistribution.Tiles.length; ++i)
                    {
                        var tile = letterDistribution.Tiles[i];
                        if (!tile.Placed)
                        {
                            lastFreeTile = i;
                        }
                    }

                    if (lastFreeTile == -1)
                    {
                        //alert("No free tiles ! TOTAL placed: " + totalPlaced);
                        // TODO: end of game ! :)
                        return;
                    }

                    var tile_index = 1000;
                    while (tile_index > lastFreeTile)
                    {
                        tile_index = Math.floor(Math.random() * letterDistribution.Tiles.length);
                    }

                    var tile = 0;
                    do
                    {
                        tile = letterDistribution.Tiles[tile_index++];
                    }
                    while (tile.Placed && tile_index < letterDistribution.Tiles.length);

                    if (tile == 0 || tile.Placed)
                    {
                        alert("No free tiles ! (WTF ?)");
                        return;
                    }
                    
                    totalPlaced++;
                    
                    var locked = 0; // Math.floor(Math.random() * 2);
                    square.PlaceTile(tile, locked == 1 ? true : false);
                
                    EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
                }
                else if (square.Tile != 0)
                {
                    square.PlaceTile(0, false);
                    
                    EventsManager.DispatchEvent(this.Event_ScrabbleBoardSquareTileChanged, { 'Board': this, 'Square': square });
                }
            }
        }
    }


    _Board.prototype.toString = function()
    {
        return "Scrabble.Core.Board toString(): " + this.Dimension + " x " + this.Dimension;
    }

    } // END - with (Scrabble.Core)

    return _Board;
    })();
    // END Scrabble.Core.Board ------------------
    //=================================================
