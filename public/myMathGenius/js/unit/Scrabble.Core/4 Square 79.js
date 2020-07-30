//=================================================
// BEGIN Scrabble.Core.Square ------------------
if (typeof _OBJECT_ROOT_.Scrabble.Core.Square == "undefined" || !_OBJECT_ROOT_.Scrabble.Core["Square"])
_OBJECT_ROOT_.Scrabble.Core.Square = (function(){

//console.log("inside Scrabble.Core.Square code scope");

with (Scrabble.Core)
{

//function _Square()
var _Square = function()
{
	switch (arguments[0])
	{
		case 0:
			//console.log("Scrabble.Core.Square constructor: SquareType.Normal");
			this.Type = SquareType.Normal;
			break;
		case 1:
			//console.log("Scrabble.Core.Square constructor: SquareType.DoubleLetter");
			this.Type = SquareType.DoubleLetter;
			break;
		case 2:
			//console.log("Scrabble.Core.Square constructor: SquareType.DoubleWord");
			this.Type = SquareType.DoubleWord;
			break;
		case 3:
			//console.log("Scrabble.Core.Square constructor: SquareType.TripleLetter");
			this.Type = SquareType.TripleLetter;
			break;
		case 4:
			//console.log("Scrabble.Core.Square constructor: SquareType.TripleWord");
			this.Type = SquareType.TripleWord;
			break;
		default:
			throw new Error("Illegal argument, first parameter of Scrabble.Core.Square constructor should be one of { SquareType.Normal, SquareType.DoubleLetter, SquareType.DoubleWord, SquareType.TripleLetter, SquareType.TripleWord }");
			break;
	}
}

_Square.prototype.X = 0;
_Square.prototype.Y = 0;

_Square.prototype.Tile = 0;
_Square.prototype.TileLocked = false;

_Square.prototype.PlaceTile = function(tile, locked)
{
	if (this.Tile != 0)
	{
		this.Tile.Placed = false;
	}

	this.Tile = tile;
	this.TileLocked = locked;

	if (tile != 0)
	{
		if (tile.Placed)
		{
			alert("Tile shouldn't already be placed on board or rack !! => " + tile);
		}
		tile.Placed = true;
	}
}

_Square.prototype.toString = function()
{
	return "Scrabble.Core.Square toString(): " + this.Type;
}

} // END - with (Scrabble.Core)

return _Square;
})();
// END Scrabble.Core.Square ------------------
//=================================================
