//=================================================
// BEGIN Scrabble.Core.Rack ------------------
if (typeof _OBJECT_ROOT_.Scrabble.Core.Rack == "undefined" || !_OBJECT_ROOT_.Scrabble.Core["Rack"])
_OBJECT_ROOT_.Scrabble.Core.Rack = (function(){
//var _Rack = {}; // 'this' object (to be returned at the bottom of the containing auto-evaluated function)

//console.log("inside Scrabble.Core.Rack code scope");

with (Scrabble.Core)
{

//function _Rack()
var _Rack = function()
{
	function CreateGrid()
	{
		for (var x = 0; x < this.Dimension; x++)
		{
			//SquareType.Normal, SquareType.DoubleLetter, SquareType.DoubleWord, SquareType.TripleLetter, SquareType.TripleWord
			var square = new Square(SquareType.Normal);
			square.X = x;
			square.Y = -1;
			this.SquaresList.push(square);
		}
		
		EventsManager.DispatchEvent(this.Event_ScrabbleRackReady, { 'Rack': this });
	}

	//_Rack.prototype.SetDimension = function()
	function SetDimension()
	{
		if (this instanceof _Rack)
		{
			if (arguments.length > 0)
			{
				//console.log("typeof ARGS: " + typeof arguments[0]);
				//console.log("typeof ARGS: " + type_of(arguments[0]));
			
				switch (type_of(arguments[0]))
				{
					case 'number':
						var val = arguments[0];

						if (val < 7)
							throw new Error("Illegal argument Scrabble.Core.Rack.SetDimension(), number smaller than 7: " + val);

						this.Dimension = val;
						//console.log("Scrabble.Core.Rack 'number' constructor: " + this.toString());
						return;
					case 'object':
					case 'array':
					default:
						var argumentsString = "";
						for (var i = 0; i < arguments.length; i++)
						{
							argumentsString += arguments[0] + ", ";
						}
						throw new Error("Illegal arguments Scrabble.Core.Rack.SetDimension(): " + argumentsString);
						break;
				}
			}
			else
			{
				this.Dimension = 8;
				//console.log("Scrabble.Core.Rack constructor with empty parameters (default "+this.Dimension+")");
			}
		}
		else
		{
			throw new Error('Illegal method call Scrabble.Core.Rack.SetDimensions() on :' + typeof this);
		}
	}
	
	//console.log("Scrabble.Core.Rack constructor before applying parameters");
	SetDimension.apply(this, arguments);
	//SetDimensions(arguments);
	
	CreateGrid.apply(this);
}

_Rack.prototype.Event_ScrabbleRackReady = "ScrabbleRackReady";
_Rack.prototype.Event_ScrabbleRackSquareTileChanged = "ScrabbleRackSquareTileChanged";

_Rack.prototype.Dimension = NaN;

_Rack.prototype.SquaresList = [];

_Rack.prototype.Game = 0;

_Rack.prototype.TakeTilesBack = function()
{
	var freeTilesCount = 0;
	for (var x = 0; x < this.Dimension; x++)
	{
		var square = this.SquaresList[x];
		if (square.Tile == 0)
		{
			freeTilesCount++;
		}
	}
	
	freeTilesCount--;
	if (freeTilesCount <= 0) return;
	
	var tiles = this.Game.Board.RemoveFreeTiles();
	var count = tiles.length;

	if (count > freeTilesCount)
	{
		count = freeTilesCount;
	}
	
	for (var i = 0; i < count; i++)
	{
		for (var x = 0; x < this.Dimension; x++)
		{
			var square = this.SquaresList[x];
			if (square.Tile == 0)
			{
				square.PlaceTile(tiles[i], false);

				EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
				
				break;
			}
		}
	}
}

_Rack.prototype.EmptyTiles = function()
{
	for (var x = 0; x < this.Dimension; x++)
	{
		var square = this.SquaresList[x];
		
		square.PlaceTile(0, false);

		EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
	}
}

_Rack.prototype.MoveTile = function(tileXY, squareXY)
{
	if (tileXY.y != -1 || squareXY.y != -1)
	{
		if (this.Game != 0)
		{
			this.Game.MoveTile(tileXY, squareXY);
		}
		
		return;
	}
	
	var square1 = this.SquaresList[tileXY.x];
	var square2 = this.SquaresList[squareXY.x];

	var tile = square1.Tile;
	square1.PlaceTile(0, false);
	square2.PlaceTile(tile, false);

	//var random = Math.floor(Math.random()*3);
	//var audio = document.getElementById(square2.Type == SquareType.Normal ? 'audio2' : 'audio3');
	//var audio = document.getElementById('audio' + (random+1));
	PlayAudio('audio4');
	
	EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square1 });
	EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square2 });
}

_Rack.prototype.GetRandomFreeTile = function()
{
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
		alert("No free tiles !"); // TODO: end of game ! :)
		return 0;
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
		return 0;
	}
	
	return tile;
}

_Rack.prototype.ReplenishRandomTiles = function()
{
	var existingTiles = [];
	for (var x = 0; x < this.Dimension; x++)
	{
		var square = this.SquaresList[x];
		if (square.Tile != 0)
		{
			existingTiles.push(square.Tile);
		}
	}

	this.EmptyTiles();
	
	for (var x = 0; x < existingTiles.length; x++)
	{
		var square = this.SquaresList[x];
		square.PlaceTile(existingTiles[x], false);
		EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
	}
	
	for (var x = existingTiles.length; x < (this.Dimension-1); x++)
	{
		var square = this.SquaresList[x];
		
		var tile = this.GetRandomFreeTile();
		if (tile == 0) return;
		
		square.PlaceTile(tile, false);
	
		EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
	}
}

_Rack.prototype.GenerateRandomTiles = function()
{
	rack.EmptyTiles();

	for (var x = 0; x < (this.Dimension - 1); x++)
	{
		var square = this.SquaresList[x];
		
		
		/*
		var letters = Tile.prototype.BlankLetter + "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		var letter_index = Math.floor(Math.random() * letters.length);
		var letter = letters.substring(letter_index, letter_index+1);
	
		var score = Math.floor(Math.random()*10) + 1;
		if (letter == Tile.prototype.BlankLetter) score = 0;
	
		if (x == 0)
		{
			letter = Tile.prototype.BlankLetter;
			score = 0;
		}
	
		var tile = new Tile(letter, score);
		*/
		
		var tile = this.GetRandomFreeTile();
		if (tile == 0) return;
		
		square.PlaceTile(tile, false);
	
		EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
	}
	
	var square = this.SquaresList[this.Dimension - 1];
	if (square.Tile != 0)
	{
		square.PlaceTile(0, false);
		
		EventsManager.DispatchEvent(this.Event_ScrabbleRackSquareTileChanged, { 'Rack': this, 'Square': square });
	}
}


_Rack.prototype.toString = function()
{
	return "Scrabble.Core.Rack toString(): " + this.Dimension;
}

} // END - with (Scrabble.Core)

return _Rack;
})();
// END Scrabble.Core.Rack ------------------
//=================================================
