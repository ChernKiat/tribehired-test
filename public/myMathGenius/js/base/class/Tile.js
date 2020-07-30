class Tile {
    constructor() {
        this.Letter = arguments[0];
        this.Score = arguments[1];
        
        console.log(this);
        if (this.Letter == this.BlankLetter) {
            this.IsBlank = true;
        }
    }
}

Tile.prototype.BlankLetter = "-";

Tile.prototype.Letter = '';
Tile.prototype.IsBlank = false;
Tile.prototype.Score = 0;
Tile.prototype.Placed = false;

Tile.prototype.toString = function() {
    return "Scrabble.Core.Tile toString(): [" + this.Letter + "] --> " + this.Score;
}
