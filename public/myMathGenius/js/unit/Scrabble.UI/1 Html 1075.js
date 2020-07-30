//=================================================
// BEGIN Scrabble.UI.Html ------------------
if (typeof _OBJECT_ROOT_.Scrabble.UI.Html == "undefined" || !_OBJECT_ROOT_.Scrabble.UI["Html"])
_OBJECT_ROOT_.Scrabble.UI.Html = (function(){
//var _Html = {}; // 'this' object (to be returned at the bottom of the containing auto-evaluated function)

//console.log("inside Scrabble.UI.Html code scope");

with (Scrabble.Core)
{
var _Html = function()
{
	function UpdateHtmlTableCellState(html, board, square, state)
	{
		var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
		var td = document.getElementById(id).parentNode;
		$(td).removeClass("Invalid");
		$(td).removeClass("Valid");
		$(td).removeClass("ValidButWrongPlacement");
		
		if (state == 0)
		{
			$(td).addClass("Valid");
		}
		else if (state == 1)
		{
			$(td).addClass("Invalid");
		}
		else if (state == 2)
		{
			$(td).addClass("ValidButWrongPlacement");
		}
	}
	
	function UpdateHtmlTableCell_Board(html, board, square)
	{
		var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
		var td = document.getElementById(id).parentNode;
		if (td.hasChildNodes())
		{
			while (td.childNodes.length >= 1)
			{
				td.removeChild(td.firstChild);
		    }
		}
		
		var div = document.createElement('div');
		td.appendChild(div);
		div.setAttribute('id', id);
		
		var a = document.createElement('a');
		div.appendChild(a);

		if (square.Tile != 0)
		{
			//td.setAttribute('class', td.getAttribute('class') + ' Tile');
			div.setAttribute('class', (square.TileLocked ? 'Tile Locked' : 'Tile Temp')
									+ (square.Tile.IsBlank ? " BlankLetter" : ""));
			
			if (!square.TileLocked)
			{
			$(div).click(
				function () {
					var id1 = $(this).attr("id");
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var sourceInRack = html.CurrentlySelectedSquare.Y == -1;
						
						var idSelected = (sourceInRack ? IDPrefix_Rack_SquareOrTile : IDPrefix_Board_SquareOrTile) + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;

						var divz = document.getElementById(idSelected);

						$(divz).removeClass("Selected");
						
						if (x1 == html.CurrentlySelectedSquare.X && y1 == html.CurrentlySelectedSquare.Y)
						{
							PlayAudio("audio1");
						
							html.SetCurrentlySelectedSquareUpdateTargets(0);
							//html.CurrentlySelectedSquare = 0;
							return;
						}
					}
					
					PlayAudio("audio3");
					
					html.SetCurrentlySelectedSquareUpdateTargets(board.SquaresList[x1 + board.Dimension * y1]);
					//html.CurrentlySelectedSquare = ;
					
					$(this).addClass("Selected");
					
					if (square.Tile.IsBlank) //Letter == Tile.prototype.BlankLetter
					{
						board.Game.SquareBlankLetterInWaitingRack = 0;
						board.Game.SquareBlankLetterInWaitingBoard = square;
						
						$.blockUI({
							message: $('#letters'),
							focusInput: true,
							bindEvents: true,
							constrainTabKey: true,
							fadeIn: 0,
							fadeOut: 0,
							showOverlay: true,
							centerY: true,
							css: { position: "absolute", backgroundColor: "transparent", width: "100%", left: 0, top: $(this).offset().top, border: "none", textAlign: "center" },
							overlayCSS: { backgroundColor: '#333333', opacity: 0.7 },
							onBlock: function()
							{
								//console.log("modal activated");
							}
						}); 
						
						$('.blockOverlay').attr('title','Click to cancel');
						$('.blockOverlay').click(
							function()
							{
							$.unblockUI(
							{
								onUnblock: function()
								{
									//console.log("modal dismissed");
								}
							});
							}
						);
						//$.unblockUI();
				        //setTimeout($.unblockUI, 4000);
						/* setTimeout(function() { 
						            $.unblockUI({ 
						                onUnblock: function(){ ; } 
						            }); 
						        }, 4000);
						*/
					}
				}
			);
			$(div).mousedown( // hack needed to make the clone drag'n'drop work correctly. Damn, it breaks CSS hover !! :(
				function () {
					//$(this).css({'border' : '0.35em outset #FFF8C6'});
				}
			);
			
			var doneOnce = false;
			
			$(div).draggable({ //"#board .Tile"
				revert: "invalid",
				//cursor: "move",
				opacity: 1,
				helper: "clone",
				//snap: ".Empty",
				start: function(event, ui)
				{
					PlayAudio("audio3");
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var sourceInRack = html.CurrentlySelectedSquare.Y == -1;
						
						var idSelected = (sourceInRack ? IDPrefix_Rack_SquareOrTile : IDPrefix_Board_SquareOrTile) + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;

						var divz = document.getElementById(idSelected);
						$(divz).removeClass("Selected");
					}
					html.SetCurrentlySelectedSquareUpdateTargets(0);
					
					$(this).css({ opacity: 0.5 });
					
					$(ui.helper).animate({'font-size' : '120%'}, 300); //height : '+=10px', width : '+=10px', 
						
					$(ui.helper).addClass("dragBorder");
				},
				
				drag: function(event, ui)
				{
					if (!doneOnce)
					{
						$(ui.helper).addClass("dragBorder");
						
						doneOnce = true;
					}
					
					//$(ui.helper).css({"color": "#333333 !important"});
				},
				stop: function(event, ui)
				{
					$(this).css({ opacity: 1 });

					PlayAudio('audio5');
				}
				});
			}
				
			var txt1 = document.createTextNode(square.Tile.Letter);
			var span1 = document.createElement('span');
			span1.setAttribute('class', 'Letter');
			span1.appendChild(txt1);
			a.appendChild(span1);

			var txt2 = document.createTextNode(square.Tile.Score);
			var span2 = document.createElement('span');
			span2.setAttribute('class', 'Score');
			span2.appendChild(txt2);
			a.appendChild(span2);
		}
		else
		{
			var middle = Math.floor(board.Dimension / 2);
			if (square.X == middle && square.Y == middle)
			{
				div.setAttribute('class', "CenterStart");
			}
			else
			{
				div.setAttribute('class', 'Empty');
			}
			
			$(div).click(
				function () {
					var id1 = $(this).attr("id");
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var sourceInRack = html.CurrentlySelectedSquare.Y == -1;
						
						var idSelected = (sourceInRack ? IDPrefix_Rack_SquareOrTile : IDPrefix_Board_SquareOrTile) + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;
					
						var divz = document.getElementById(idSelected);

						$(divz).removeClass("Selected");
						
						var XX = html.CurrentlySelectedSquare.X;
						var YY = html.CurrentlySelectedSquare.Y;
						
						html.SetCurrentlySelectedSquareUpdateTargets(0);
						//html.CurrentlySelectedSquare = 0;
						
						board.MoveTile({'x':XX, 'y':YY}, {'x':x1, 'y':y1});
					}
				}
			);

			$(div).droppable({ //"#board .Empty"
				//accept: ".Tile",
				//activeClass: "dropActive",
				hoverClass: "dropActive",
				drop: function( event, ui )
				{
					//$( this ).addClass( "dropActive" );
				
					var id1 = $(ui.draggable).attr("id");
					var id2 = $(this).attr("id");
				
					//alert(id1 + "-->" + id2);
				
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);
				
					//alert(x1 + "x" + y1);

					var underscore2 = id2.indexOf("_");
					var cross2 = id2.indexOf("x");
					var x2 = parseInt(id2.substring(underscore2 + 1, cross2), 10);
					var y2 = parseInt(id2.substring(cross2 + 1), 10);
				
					//alert(x1 + "x" + y1);
				
					board.MoveTile({'x':x1, 'y':y1}, {'x':x2, 'y':y2});
				}
			});
			
			switch (square.Type)
			{
				case SquareType.Normal:
					var span1 = document.createElement('span');
					var txt1 = document.createTextNode(" ");
					span1.appendChild(txt1);
					a.appendChild(span1);
		
					break;
				case SquareType.DoubleWord:
				
					var middle = Math.floor(board.Dimension / 2);
					if (square.X == middle && square.Y == middle)
					{
						var txt1 = document.createTextNode('\u2605');
						var span1 = document.createElement('span');
						span1.appendChild(txt1);
						a.appendChild(span1);
					}
					else
					{
						var txt1 = document.createTextNode("DOUBLE");
						var txt2 = document.createTextNode("WORD");
						var txt3 = document.createTextNode("SCORE");
					
					
						var span1 = document.createElement('span');
						span1.appendChild(txt1);
					
						var span2 = document.createElement('span');
						span2.appendChild(txt2);
					
						var span3 = document.createElement('span');
						span3.appendChild(txt3);

						a.appendChild(span1);
						a.appendChild(document.createElement('br'));
						a.appendChild(span2);
						a.appendChild(document.createElement('br'));
						a.appendChild(span3);
					}
					break;
				case SquareType.TripleWord:
					var span = document.createElement('span');
					var txt1 = document.createTextNode("TRIPLE WORD SCORE");
					span.appendChild(txt1);

					a.appendChild(span);
					break;
				case SquareType.DoubleLetter:
					var span = document.createElement('span');
					var txt1 = document.createTextNode("DOUBLE LETTER SCORE");
					span.appendChild(txt1);

					a.appendChild(span);
					break;
				case SquareType.TripleLetter:
					var span = document.createElement('span');
					var txt1 = document.createTextNode("TRIPLE LETTER SCORE");
					span.appendChild(txt1);

					a.appendChild(span);
					break;
				default:
					break;
			}
		}
	}
	
	function DrawHtmlTable_Board(html, board)
	{
		var rootDiv = document.getElementById('board');
		var table = document.createElement('table');
		rootDiv.appendChild(table);
	
		for (var y = 0; y < board.Dimension; y++)
		{
			var tr = document.createElement('tr');
			table.appendChild(tr);
		
			for (var x = 0; x < board.Dimension; x++)
			{
				var square = board.SquaresList[x + board.Dimension * y];

				var centerStart = false;
			
				var td = document.createElement('td');
				tr.appendChild(td);
				
				var middle = Math.floor(board.Dimension / 2);
				var halfMiddle = Math.ceil(middle / 2);
			
				if (square.Type == SquareType.TripleWord)
				{
					td.setAttribute('class', 'TripleWord');
				}
				else if (square.Type == SquareType.DoubleWord)
				{
					if (x == middle && y == middle)
					{
						centerStart = true;
					}
					
					td.setAttribute('class', 'DoubleWord');
				}
				else if (square.Type == SquareType.DoubleLetter)
				{
					td.setAttribute('class', 'DoubleLetter');
				}
				else if (square.Type == SquareType.TripleLetter)
				{
					td.setAttribute('class', 'TripleLetter');
				}
				else
				{
					td.setAttribute('class', 'Normal');
				}
			
				var div = document.createElement('div');
				td.appendChild(div);
				
				var id = IDPrefix_Board_SquareOrTile + x + "x" + y;
				div.setAttribute('id', id);
				
				var a = document.createElement('a');
				div.appendChild(a);
				
				UpdateHtmlTableCell_Board(html, board, square);
			}
		}
	}

	function UpdateHtmlTableCell_Rack(html, rack, square)
	{
		var id = IDPrefix_Rack_SquareOrTile + square.X + "x" + square.Y;
		var td = document.getElementById(id).parentNode;
		if (td.hasChildNodes())
		{
			while (td.childNodes.length >= 1)
			{
				td.removeChild(td.firstChild);
		    }
		}
		
		var div = document.createElement('div');
		td.appendChild(div);
		div.setAttribute('id', id);
		
		var a = document.createElement('a');
		div.appendChild(a);

		if (square.Tile != 0)
		{
			//td.setAttribute('class', td.getAttribute('class') + ' Tile');
			div.setAttribute('class', 'Tile Temp' + (square.Tile.IsBlank ? " BlankLetter" : ""));
			
			$(div).click(
				function () {
					var id1 = $(this).attr("id");
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var sourceInRack = html.CurrentlySelectedSquare.Y == -1;
						var idSelected = (sourceInRack ? IDPrefix_Rack_SquareOrTile : IDPrefix_Board_SquareOrTile) + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;
						var divz = document.getElementById(idSelected);

						$(divz).removeClass("Selected");
						
						if (sourceInRack
							&& x1 == html.CurrentlySelectedSquare.X)
						{
							PlayAudio("audio1");
						
							html.SetCurrentlySelectedSquareUpdateTargets(0);
							//html.CurrentlySelectedSquare = 0;
							return;
						}
					}
					
					PlayAudio("audio3");
					
					html.SetCurrentlySelectedSquareUpdateTargets(rack.SquaresList[x1]);
					//html.CurrentlySelectedSquare = ;
					
					$(this).addClass("Selected");
					
					
					if (square.Tile.IsBlank) //Letter == Tile.prototype.BlankLetter
					{
						board.Game.SquareBlankLetterInWaitingBoard = 0;
						board.Game.SquareBlankLetterInWaitingRack = square;
						
						$.blockUI({
							message: $('#letters'),
							focusInput: true,
							bindEvents: true,
							constrainTabKey: true,
							fadeIn: 0,
							fadeOut: 0,
							showOverlay: true,
							centerY: true,
							css: { position: "absolute", backgroundColor: "transparent", width: "100%", left: 0, top: $(this).offset().top, border: "none", textAlign: "center" },
							overlayCSS: { backgroundColor: '#333333', opacity: 0.7 },
							onBlock: function()
							{
								//console.log("modal activated");
							}
						}); 
						
						$('.blockOverlay').attr('title','Click to cancel');
						$('.blockOverlay').click(
							function(){
							$.unblockUI(
							{
								onUnblock: function()
								{
									//console.log("modal dismissed");
								}
							});
							}
						);
						//$.unblockUI();
				        //setTimeout($.unblockUI, 4000);
						/* setTimeout(function() { 
						            $.unblockUI({ 
						                onUnblock: function(){ ; } 
						            }); 
						        }, 4000);
						*/
					}
				}
			);
			$(div).mousedown( // hack needed to make the clone drag'n'drop work correctly. Damn, it breaks CSS hover !! :(
				function () {
					//$(this).css({'border' : '0.35em outset #FFF8C6'});
				}
			);
			
			var doneOnce = false;
			
			$(div).draggable({ //"#rack .Tile"
				revert: "invalid",
				//cursor: "move",
				opacity: 1,
				helper: "clone",
				//snap: ".Empty",
				start: function(event, ui)
				{
					PlayAudio("audio3");
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var idSelected = IDPrefix_Rack_SquareOrTile + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;
						var divz = document.getElementById(idSelected);
						$(divz).removeClass("Selected");
					}
					html.SetCurrentlySelectedSquareUpdateTargets(0);
					
					$(this).css({ opacity: 0.5 });
					
					$(ui.helper).animate({'font-size' : '120%'}, 300); //height : '+=10px', width : '+=10px', 
						
						$(ui.helper).addClass("dragBorder");
					
				},
				
				drag: function(event, ui)
				{
					if (!doneOnce)
					{
						$(ui.helper).addClass("dragBorder");
						
						doneOnce = true;
					}
					
					//$(ui.helper).css({"color": "#333333 !important"});
				},
				stop: function(event, ui)
				{
					$(this).css({ opacity: 1 });

					PlayAudio('audio5');
				}
			});
			
			var txt1 = document.createTextNode(square.Tile.Letter);
			var span1 = document.createElement('span');
			span1.setAttribute('class', 'Letter');
			span1.appendChild(txt1);
			a.appendChild(span1);

			var txt2 = document.createTextNode(square.Tile.Score);
			var span2 = document.createElement('span');
			span2.setAttribute('class', 'Score');
			span2.appendChild(txt2);
			a.appendChild(span2);
		}
		else
		{
			div.setAttribute('class', 'Empty');
			
			$(div).click(
				function () {
					var id1 = $(this).attr("id");
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);

					if (html.CurrentlySelectedSquare != 0)
					{
						var idSelected = IDPrefix_Rack_SquareOrTile + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;
						var divz = document.getElementById(idSelected);

						$(divz).removeClass("Selected");
						
						var XX = html.CurrentlySelectedSquare.X;
						var YY = html.CurrentlySelectedSquare.Y;
						
						html.SetCurrentlySelectedSquareUpdateTargets(0);
						//html.CurrentlySelectedSquare = 0;
						
						rack.MoveTile({'x':XX, 'y':YY}, {'x':x1, 'y':y1});
					}
				}
			);

			$(div).droppable({ //"#rack .Empty"
				//accept: ".Tile",
				//activeClass: "dropActive",
				hoverClass: "dropActive",
				drop: function( event, ui )
				{
					//$( this ).addClass( "dropActive" );
				
					var id1 = $(ui.draggable).attr("id");
					var id2 = $(this).attr("id");
				
					//alert(id1 + "-->" + id2);
				
					var underscore1 = id1.indexOf("_");
					var cross1 = id1.indexOf("x");
					var x1 = parseInt(id1.substring(underscore1 + 1, cross1), 10);
					var y1 = parseInt(id1.substring(cross1 + 1), 10);
					
					//alert(x1 + "x" + y1);

					var underscore2 = id2.indexOf("_");
					var cross2 = id2.indexOf("x");
					var x2 = parseInt(id2.substring(underscore2 + 1, cross2), 10);
					var y2 = parseInt(id2.substring(cross2 + 1), 10);
					
					//alert(x1 + "x" + y1);
				
					rack.MoveTile({'x':x1, 'y':y1}, {'x':x2, 'y':y2});
				}
			});
			
			switch (square.Type)
			{
				case SquareType.Normal:
					var span1 = document.createElement('span');
					var txt1 = document.createTextNode(" ");
					span1.appendChild(txt1);
					a.appendChild(span1);
					break;
				default:
					break;
			}
		}
	}

	function DrawHtmlTable_Rack(html, rack)
	{
		var rootDiv = document.getElementById('rack');
		var table = document.createElement('table');
		rootDiv.appendChild(table);
		
		var tr = document.createElement('tr');
		table.appendChild(tr);

		for (var x = 0; x < rack.Dimension; x++)
		{
			var square = rack.SquaresList[x];

			var td = document.createElement('td');
			tr.appendChild(td);

			td.setAttribute('class', 'Normal');
		
			var div = document.createElement('div');
			td.appendChild(div);
			
			var id = IDPrefix_Rack_SquareOrTile + square.X + "x" + square.Y;
			div.setAttribute('id', id);
			
			var a = document.createElement('a');
			div.appendChild(a);
			
			UpdateHtmlTableCell_Rack(html, rack, square);
		}
	}


	function DrawHtmlTable_LetterTiles(html, game)
	{
		var letterDistribution = 0;
		for (var i = 0; i < game.LetterDistributions.length; ++i)
		{
			var ld = game.LetterDistributions[i];
			if (ld.Language == game.Language) // TODO user should select language
			{
				letterDistribution = ld;
			}
		}
		
		var rootDiv = document.getElementById('letters');
		
		if (rootDiv.hasChildNodes())
		{
			while (rootDiv.childNodes.length >= 1)
			{
				rootDiv.removeChild(rootDiv.firstChild);
		    }
		}
		
		var table = document.createElement('table');
		rootDiv.appendChild(table);
		
		var tr = 0

		var counter = 9;
		for (var i = 0; i < letterDistribution.Letters.length; i++)
		{
			var tile = letterDistribution.Letters[i];
			if (tile.IsBlank) continue; //Letter == Tile.prototype.BlankLetter) continue;

			counter++;
			if (counter > 9)
			{
				tr = document.createElement('tr');
				table.appendChild(tr);

				counter = 0;
			}
			
			var td = document.createElement('td');
			td.setAttribute('class', 'Normal');
			tr.appendChild(td);
		
			var div = document.createElement('div');
			td.appendChild(div);

			var id = IDPrefix_Letters_SquareOrTile + i;
			div.setAttribute('id', id);
			
			var a = document.createElement('a');
			div.appendChild(a);

			//td.setAttribute('class', td.getAttribute('class') + ' Tile');
			div.setAttribute('class', 'Tile Temp' + (tile.IsBlank ? " BlankLetter" : ""));
			
			$(div).click(
				function () {

					$.unblockUI();
					
					var id1 = $(this).attr("id");
					var underscore1 = id1.indexOf("_");
					var index = parseInt(id1.substring(underscore1 + 1), 10);

					var letterDistribution = 0;
					for (var i = 0; i < game.LetterDistributions.length; ++i)
					{
						var ld = game.LetterDistributions[i];
						if (ld.Language == game.Language) // TODO user should select language
						{
							letterDistribution = ld;
						}
					}
					
					if (game.SquareBlankLetterInWaitingBoard != 0)
					{
						if (html.CurrentlySelectedSquare != game.SquareBlankLetterInWaitingBoard)
						{
							alert("CurrentlySelectedSquare != SquareBlankLetterInWaitingBoard");
						}
						
						game.SquareBlankLetterInWaitingBoard.Tile.Letter = letterDistribution.Letters[index].Letter;

						var square = game.SquareBlankLetterInWaitingBoard;
						game.SquareBlankLetterInWaitingBoard = 0;

						EventsManager.DispatchEvent(Board.prototype.Event_ScrabbleBoardSquareTileChanged, { 'Board': game.Board, 'Square': square });
					}
					
					else if (game.SquareBlankLetterInWaitingRack != 0)
					{
						if (html.CurrentlySelectedSquare != game.SquareBlankLetterInWaitingRack)
						{
							alert("CurrentlySelectedSquare != SquareBlankLetterInWaitingRack");
						}
						
						game.SquareBlankLetterInWaitingRack.Tile.Letter = letterDistribution.Letters[index].Letter;

						var square = game.SquareBlankLetterInWaitingRack;
						game.SquareBlankLetterInWaiting = 0;
					
						EventsManager.DispatchEvent(Rack.prototype.Event_ScrabbleRackSquareTileChanged, { 'Rack': game.Rack, 'Square': square });
					}
					
					
					if (html.CurrentlySelectedSquare != 0)
					{
						var sourceInRack = html.CurrentlySelectedSquare.Y == -1;
						
						var idSelected = (sourceInRack ? IDPrefix_Rack_SquareOrTile : IDPrefix_Board_SquareOrTile) + html.CurrentlySelectedSquare.X + "x" + html.CurrentlySelectedSquare.Y;

						var divz = document.getElementById(idSelected);

						//$(divz).removeClass("Selected");
						$(divz).addClass("Selected");
					}
				}
			);

			var txt1 = document.createTextNode(tile.Letter);
			var span1 = document.createElement('span');
			span1.setAttribute('class', 'Letter');
			span1.appendChild(txt1);
			a.appendChild(span1);

			var txt2 = document.createTextNode(tile.Score);
			var span2 = document.createElement('span');
			span2.setAttribute('class', 'Score');
			span2.appendChild(txt2);
			a.appendChild(span2);
		}
		
		var input = document.createElement('input');
		//input.setAttribute('id', 'cancelBlockUi');
		input.setAttribute('type', 'submit');
		input.setAttribute('value', 'Cancel');
		input.setAttribute('onclick', '$.unblockUI();');
		
		var buttonDiv = document.createElement('div');
		buttonDiv.setAttribute('style', 'background-color: #333333; width: auto; padding: 1em; padding-left: 2em; padding-right: 2em;');
		buttonDiv.appendChild(input);
		rootDiv.appendChild(buttonDiv);
	}
	
	
	_Html.prototype.OnUnblockUIFunction = function(){;};
	
	_Html.prototype.UnblockUIFunction =
		function()
		{
		$.unblockUI(
		{
			onUnblock: function()
			{
				//console.log("modal dismissed");
				_Html.prototype.OnUnblockUIFunction();
				_Html.prototype.OnUnblockUIFunction = function(){;};
			}
		});
		};
	
	_Html.prototype.CleanupErrorLayer = function()
	{
		for (var y = 0; y < this.Board.Dimension; y++)
		{
			for (var x = 0; x < this.Board.Dimension; x++)
			{
				var square = this.Board.SquaresList[x + this.Board.Dimension * y];
				var id = IDPrefix_Board_SquareOrTile + square.X + "x" + square.Y;
				var td = document.getElementById(id).parentNode;
				$(td).removeClass("Invalid");
				$(td).removeClass("Valid");
				$(td).removeClass("ValidButWrongPlacement");
			}
		}
	}
	
	function handleKeyup(event)
	{
		// NN4 passes the event as a parameter.  For MSIE4 (and others)
		// we need to get the event from the window.
		if (document.all)
		{
			event = window.event;
		}

		var key = event.which;
		if (!key)
		{
			key = event.keyCode;
		}

		if (key == 27) // ESC key
		{
			//document.getElementById('cancelBlockUi').click();
			_Html.prototype.UnblockUIFunction();
			//TODO: move all temp tiles from board back to rack ?
		}

		return true;
	}
	
	function handleKeypress(event)
	{
		// NN4 passes the event as a parameter.  For MSIE4 (and others)
		// we need to get the event from the window.
		if (document.all)
		{
			event = window.event;
		}
		
		if (event.ctrlKey || event.altKey)
		{
			return true;
		}

		var key = event.which;
		if (!key)
		{
			key = event.keyCode;
		}
		
		if(event.charCode == null || event.charCode == 0)
		{
			if (nKeyCode >= 112 && nKeyCode <= 123)
			{
				return true;
			}
		}
		
		if (key > 96)
		{
			key -= 32;
		}

		if (key != 13 && key != 32 && (key < 65 || key > 65 + 26))
		{
			return true;
		}

		if (key == 13) // ENTER/RETURN key
		{
			//TODO submit player turn
		}
		else
		{
			var keyChar = String.fromCharCode(key);
			
			//TODO
		}
		if (document.all)
		{
			event.cancelBubble = true;
			event.returnValue = false;
		}
		else
		{
			event.stopPropagation();
			event.preventDefault();
		}

		return false;
	}
	
	if (document.all)
	{
		//document.attachEvent("onkeypress", handleKeypress);
		document.attachEvent("onkeyup", handleKeyup);
	}
	else
	{
		//document.onkeypress = handleKeypress;
		document.onkeyup = handleKeyup;
	}
	
	var thiz = this;

	var callback_ScrabbleBoardReady = function(eventPayload)
		{
			thiz.Board = eventPayload.Board;
			DrawHtmlTable_Board(thiz, eventPayload.Board);
		};

	EventsManager.AddEventListener(Board.prototype.Event_ScrabbleBoardReady, callback_ScrabbleBoardReady);
	
	var callback_ScrabbleBoardSquareTileChanged = function(eventPayload)
		{
			UpdateHtmlTableCell_Board(thiz, eventPayload.Board, eventPayload.Square);
		};

	EventsManager.AddEventListener(Board.prototype.Event_ScrabbleBoardSquareTileChanged, callback_ScrabbleBoardSquareTileChanged);
	
	var callback_ScrabbleBoardSquareStateChanged = function(eventPayload)
		{
			UpdateHtmlTableCellState(thiz, eventPayload.Board, eventPayload.Square, eventPayload.State);
		};

	EventsManager.AddEventListener(Board.prototype.Event_ScrabbleBoardSquareStateChanged, callback_ScrabbleBoardSquareStateChanged);
	
	var callback_ScrabbleRackReady = function(eventPayload)
		{
			thiz.Rack = eventPayload.Rack;
			DrawHtmlTable_Rack(thiz, eventPayload.Rack);
		};

	EventsManager.AddEventListener(Rack.prototype.Event_ScrabbleRackReady, callback_ScrabbleRackReady);
	
	var callback_ScrabbleRackSquareTileChanged = function(eventPayload)
		{
			UpdateHtmlTableCell_Rack(thiz, eventPayload.Rack, eventPayload.Square);
		};

	EventsManager.AddEventListener(Rack.prototype.Event_ScrabbleRackSquareTileChanged, callback_ScrabbleRackSquareTileChanged);
	
	var callback_ScrabbleLetterTilesReady = function(eventPayload)
		{
			thiz.Game = eventPayload.Game;
			DrawHtmlTable_LetterTiles(thiz, eventPayload.Game);
			
			$('#language').html(thiz.Game.Language.toUpperCase());
		};

	EventsManager.AddEventListener(Game.prototype.Event_ScrabbleLetterTilesReady, callback_ScrabbleLetterTilesReady);
}

_Html.prototype.CurrentlySelectedSquare = 0;
_Html.prototype.Board = 0;
_Html.prototype.Rack = 0;
_Html.prototype.Game = 0;

//TODO: make class method !! (currently some sort of static function)
_Html.prototype.SetCurrentlySelectedSquareUpdateTargets = function(square)
{
	this.CurrentlySelectedSquare = square;
	
	for (var y = 0; y < this.Board.Dimension; y++)
	{
		for (var x = 0; x < this.Board.Dimension; x++)
		{
			var squareTarget = this.Board.SquaresList[x + this.Board.Dimension * y];
			if (squareTarget.Tile == 0)
			{
				var idSelected = IDPrefix_Board_SquareOrTile + squareTarget.X + "x" + squareTarget.Y;
				var divz = document.getElementById(idSelected);
				if (this.CurrentlySelectedSquare == 0)
				{
					$(divz).removeClass("Targeted");
				}
				else
				{
					$(divz).addClass("Targeted");
				}
			}
		}
	}
	
	for (var x = 0; x < this.Rack.Dimension; x++)
	{
		var squareTarget = this.Rack.SquaresList[x];
		if (squareTarget.Tile == 0)
		{
			var idSelected = IDPrefix_Rack_SquareOrTile + squareTarget.X + "x" + squareTarget.Y;
			var divz = document.getElementById(idSelected);
			if (this.CurrentlySelectedSquare == 0)
			{
				$(divz).removeClass("Targeted");
			}
			else
			{
				$(divz).addClass("Targeted");
			}
		}
	}
}

} // END - with (Scrabble.Core)

return _Html;
})();
// END Scrabble.UI.Html ------------------
//=================================================
