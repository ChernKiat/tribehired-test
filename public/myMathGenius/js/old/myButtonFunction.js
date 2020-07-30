var game = 0;
var board = 0;
var rack = 0;
var boardUI = 0;

function playAction_resetTilesOnRack()
{
    boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
    //boardUI.CurrentlySelectedSquare = 0;

    rack.TakeTilesBack();
}

function playAction_submitWords()
{}

function playAction_drawNewTiles()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;

	/*
	rack.EmptyTiles();
	rack.ReplenishRandomTiles();
	*/
	
	rack.GenerateRandomTiles();
	
	//TODO: pass turn
}

function playAction_passTurn()
{
	if (!confirm("Are you sure you want to pass your turn ?"))
	{
		return;
	}
	
	//TODO: pass
}

function resetTextField()
{
	$("#text_field").val("");
}

function findAnagrams()
{
	var text = $("#text_field").val();
	var anagrams = game.Dictionary.FindAnagrams(text);
	if (anagrams.length == 1)
	{
		alert("Error: the characters [" + anagrams[0] + "] do not return any anagrams from the \"" + game.Language + "\" dictionary.");
	}
	else
	{
		//alert("Success: the characters [" + anagrams[0] + "] return " + (anagrams.length-1) + " anagrams from the \"" + game.Language + "\" dictionary.");
		
		$('#anagrams').html('<p><input type="button" value="Clear Anagrams" onclick="$(\'#anagrams\').html(\'\');"></input></p>');
		
		/*
		for (var i = 1; i < anagrams.length; i++)
		{
			$('#anagrams').append('<p>' + anagrams[i] + '</p>');
		}
		*/
		
		var wordsByLength = [];
		for (var i = 0; i < game.Dictionary.MaxWordLength; i++)
		{
			wordsByLength.push([]);
		}
		
		for (var i = 1; i < anagrams.length; i++)
		{
			var word = anagrams[i];
			wordsByLength[word.length].push(word);
		}
		
		var charInput = anagrams[0];
		
		var table = "<table>";
		
		for (var i = game.Dictionary.MaxWordLength - 1; i >= 2 ; i--)
		{
			var words = wordsByLength[i];
			
			if (words.length <= 0) continue;
			
			table += "<tr>";
			
			if (charInput.length == i)
			{
				table += "<th>[" + i + "]</th>";
			}
			else
			{
				table += "<th>" + i + "</th>";
			}
			
			table += "<td>";
			
			for (var j = 0; j < words.length; j++)
			{
				var word = words[j];
				table += word;
				if (j < (words.length - 1))
				{
					table += " - ";
				}
			}
			
			table += "</td>";
			table += "<tr>";
		}
		
		table += "</table>";
		
		$('#anagrams').append(table);
	}
}

function checkWord()
{
	var text = $("#text_field").val();
	var ok = game.Dictionary.CheckWord(text);
	if (ok)
	{
		alert("Success: [" + text + "] is in the \"" + game.Language + "\" dictionary.");
	}
	else
	{
		alert("Error: [" + text + "] is not in the \"" + game.Language + "\" dictionary.");
	}
}

function verifyBoard()
{
	boardUI.CleanupErrorLayer();
	
	var valid = board.CheckDictionary();

	if (!valid)
	{
		Scrabble.UI.Html.prototype.OnUnblockUIFunction = function() { boardUI.CleanupErrorLayer(); };
		
		$.blockUI({
			message: "<p>Invalid words or tile placement !</p><input type=\"submit\" value=\"Return\" onclick=\"boardUI.UnblockUIFunction();\"></input>",
			focusInput: true,
			bindEvents: true,
			constrainTabKey: true,
			fadeIn: 0,
			fadeOut: 0,
			showOverlay: true,
			centerY: true,
			css: { position: "absolute", padding: "1em", backgroundColor: "black", color: "white", width: "100%", left: 0, top: $("#rack").offset().top, border: "none", textAlign: "center" },
			overlayCSS: { backgroundColor: '#FFFFFF', opacity: 0.2 },
			onBlock: function()
			{
				//console.log("modal activated");
			}
		}); 

		$('.blockOverlay').attr('title','Click to return to the game');
		$('.blockOverlay').click(
			boardUI.UnblockUIFunction
		);
	}
	else
	{
		setTimeout(function()
		{
			boardUI.CleanupErrorLayer(); 
		}, 1000);
	}

	//if (!valid) alert("Some words are invalid !");
	//else alert("All words are valid !");
}

function emptyBoardAndRack()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;
	
	board.EmptyTiles();
	rack.EmptyTiles();
}

function replenishRack()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;
	
	rack.ReplenishRandomTiles();
}

function placeRandomTiles_Rack()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;
	
	rack.GenerateRandomTiles();
}

function placeRandomTiles_Board()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;
	
	board.GenerateRandomTiles();
}

function setLanguage(lang)
{
	game.SetLanguage(lang);
}

function placeTileSet()
{
	boardUI.SetCurrentlySelectedSquareUpdateTargets(0);
	//boardUI.CurrentlySelectedSquare = 0;
	
	board.GenerateTilesLetterDistribution();
	//rack.GenerateRandomTiles();
}

function init() {

	// quit if this function has already been called
	if (arguments.callee.done) return;

	// flag this function so we don't do the same thing twice
	arguments.callee.done = true;
	
	boardUI = new window.Scrabble.UI.Html(); // must be instantiated first (to listen to Core events)

	board = new window.Scrabble.Core.Board();
	rack = new window.Scrabble.Core.Rack();

	game = new window.Scrabble.Core.Game(board, rack);
	
	/*
	if (false)
	{
		board.GenerateRandomTiles();
	}
	else
	{
		board.GenerateTilesLetterDistribution();
	}
	*/
	
	//rack.GenerateRandomTiles();
	
	$(document).ready(function(){
		
						/*
							$(this).animate({left: "+=200px", width:320 }, 1500, function() {

									$(this).animate({left: "-=200px", width:280 }, 1500, function() {

									});
								});
								*/
		//$("#board").fadeOut();
							//	table.fadeIn("fast");
							//		table.animate({rotate: '+=45deg'});


		var obj = $("#title");

		//obj.effect("pulsate", { times:2 }, 500);

		//obj.css({opacity: 0.5});
		//obj.scale(0.5);

		//obj.animate({opacity: 1}, 500);

	//obj.show("clip", {}, 500);

	//obj.effect("transfer", { to: $("#title") }, 1000);

	//obj.effect("bounce", { times: 3, direction: "right" }, 500);

		obj.rotate3Di(360, 1000,
		        {
		            sideChange: function(front) {
					    if (front) {
					        //
					    } else {
		//			        obj.animate({scale: 1}, 500);
					    }
					}
					,
		            complete: function() {
					    //
					}
		        }
		    );

	/*
			$('.drag')
				.drag("start",function( ev, dd ){

					return $( this ).clone()
						.css("opacity", .75 )
						.appendTo(this.parentNode );
				})
				.drag(function( ev, dd ){

					$( dd.proxy ).css({
						top: dd.offsetY,
						left: dd.offsetX
					},{ relative:true });
				})
				.drag("end",function( ev, dd ){
				$( dd.proxy ).animate({
				            top: dd.originalY,
				            left: dd.originalX
				         }, 420 );
					$( dd.proxy ).remove();
				});
			$('.drop')
			.drop("start",function(){
			         $( this ).addClass("dropActive");
			      })
			      .drop(function( ev, dd ){
			         $( this ).toggleClass("dropped");
			      })
			      .drop("end",function(){
			         $( this ).removeClass("dropActive");
			      });
					*/
	   });

};

/* for Mozilla */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", init, false);
}

/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
document.write("<script defer src=ie_onload.js><"+"/script>");
/*@end @*/

/* for other browsers */
window.onload = init;
