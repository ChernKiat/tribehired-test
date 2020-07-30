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
        this.Top = arguments[0];
        this.Right = arguments[1];
        this.Bottom = arguments[2];
        this.Left = arguments[3];
        
        // if (this.Letter == this.BlankLetter) {
        //     this.IsBlank = true;
        // }
    }

    _Tile.prototype.BlankLetter = "-";

    _Tile.prototype.IsBlank = false;
    _Tile.prototype.Top = 0;
    _Tile.prototype.Right = 0;
    _Tile.prototype.Bottom = 0;
    _Tile.prototype.Left = 0;
    _Tile.prototype.Placed = false;

    _Tile.prototype.toString = function()
    {
        return "Scrabble.Core.Tile toString(): " +
                this.Top + ", " + this.Right + ", " + this.Bottom + ", " + this.Left;
    }

    } // END - with (Scrabble.Core)

    return _Tile;
    })();
    // END Scrabble.Core.Tile ------------------
    //=================================================
