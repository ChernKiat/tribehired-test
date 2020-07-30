class Board {
    constructor() {
        this.CreateGrid();
        this.SetDimensions(this, arguments);
    }
    CreateGrid() {
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
    SetDimension(val) {
        if (typeof val != 'number')
            throw new Error("Illegal argument Scrabble.Core.Board.SetDimension(), not a number: " + typeof val);
        
        if (val < 13)
            throw new Error("Illegal argument Scrabble.Core.Board.SetDimension(), number smaller than 13: " + val);
        
        this.Dimension = val;
    }
    SetDimensions() {
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
}
