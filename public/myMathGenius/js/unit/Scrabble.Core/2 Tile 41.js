//=================================================
// BEGIN Scrabble.Core.Tile ------------------
if (typeof _OBJECT_ROOT_.Scrabble.Core.Tile == "undefined" || !_OBJECT_ROOT_.Scrabble.Core["Tile"])
_OBJECT_ROOT_.Scrabble.Core.Tile = (function(){

//console.log("inside Scrabble.Core.Tile code scope");

with (Scrabble.Core)
{

//function _Tile()
var _Tile = function()
{
	this.Letter = arguments[0];
	this.Score = arguments[1];
	
	if (this.Letter == this.BlankLetter)
	{
		this.IsBlank = true;
	}
}

_Tile.prototype.BlankLetter = "-";

_Tile.prototype.Letter = '';
_Tile.prototype.IsBlank = false;
_Tile.prototype.Score = 0;
_Tile.prototype.Placed = false;

_Tile.prototype.toString = function()
{
	return "Scrabble.Core.Tile toString(): [" + this.Letter + "] --> " + this.Score;
}

} // END - with (Scrabble.Core)

return _Tile;
})();
// END Scrabble.Core.Tile ------------------
//=================================================
