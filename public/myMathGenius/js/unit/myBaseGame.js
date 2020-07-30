(function(){

var _OBJECT_ROOT_ = window;

if (typeof _OBJECT_ROOT_.console == "undefined" || !_OBJECT_ROOT_.console)
{
	// !console.firebug
    
	//_OBJECT_ROOT_.console = {log: function() {}};
	
	var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

	_OBJECT_ROOT_.console = {};

	for (var i = 0; i < names.length; ++i)
	{
		_OBJECT_ROOT_.console[names[i]] = function(){};
	}
}

function type_of(obj)
{
	if (typeof(obj) == 'object')
		if (typeof obj.length == "undefined" || !obj.length)
			return 'object';
		else
			return 'array';
	else
		return typeof(obj);
}

//=================================================
// BEGIN Namespace util ------------------
//var HTMLScrabble = (typeof HTMLScrabble == "undefined" || !HTMLScrabble ) ? {} : HTMLScrabble;
//var HTMLScrabble.UI = (typeof HTMLScrabble.UI == "undefined" || !(HTMLScrabble.UI) ) ? {} : HTMLScrabble.UI;
/*
console.log("Checking namespace: " + "HTMLScrabble.UI");
EnsureNamespace("HTMLScrabble.UI");

console.log("Checking namespace: " + "HTMLScrabble.Core");
EnsureNamespace("HTMLScrabble.Core");
*/
function EnsureNamespace(nsString)
//_OBJECT_ROOT_.EnsureNamespace = function(nsString)
{
	//console.log("Ensuring namespace: " + nsString);
	
	var nsStrings = nsString.split(".");
	var root = _OBJECT_ROOT_;
	for (var i = 0; i < nsStrings.length; i++)
	{
		var nsName = nsStrings[i];
		var val = root[nsName];
		if (typeof val == "undefined" || !val)
		{
			//console.log("Creating namespace object: " + nsName);
			root[nsName] = new Object(); // {} ?
		}
		else
		{
			//console.log("Namespace object already exists: " + nsName);
		}
		root = root[nsName];
	}
}
// END Namespace util ------------------
//=================================================

EnsureNamespace("Scrabble.Core");
EnsureNamespace("Scrabble.UI");

var IDPrefix_Board_SquareOrTile = "BoardSquareOrTile_";
var IDPrefix_Rack_SquareOrTile = "RackSquareOrTile_";
var IDPrefix_Letters_SquareOrTile = "LettersSquareOrTile_";

function PlayAudio(id)
{
	var audio = document.getElementById(id);
	
	/*
	audio.load()
	audio.addEventListener("load", function() {

	}, true);
	*/

	if (audio.playing)
	{
		audio.pause();
	}
    
	audio.defaultPlaybackRate = 1;
	audio.volume = 1;
	
	try
	{
		audio.currentTime = 0;
		audio.play();
	}
	catch(e)
	{
		function currentTime()
		{
			audio.currentTime = 0;
			audio.removeEventListener("canplay", currentTime, true);
			audio.play();
		}
		audio.addEventListener("canplay", currentTime, true);
		//alert("DEBUG 2" + e.message);
	}
}



function randomInt(N)
{
	// % 1 is needed because some implementations of Math.random() can
	// actually return 1 (early version of Opera for example).
	// | 0 does the same as Math.floor() would here, but is probably
	// slightly quicker.
	// For details, see: http://www.merlyn.demon.co.uk/js-randm.htm
	return (N * (Math.random() % 1)) | 0;
}

function getCookie(cookieName) {
	var theCookie = document.cookie;
	if (!theCookie) return 0;
	var cookies = theCookie.split("; ");
	for (var i = 0; i < cookies.length; ++i) {
		var nameVal = cookies[i].split("=");
		if (nameVal[0] == cookieName) return nameVal[1];
	}
	return 0;
}

/*

function getBestPlay() {
	return getCookie("sscrable_bestplay");
}

function setBestPlay(value) {
	document.cookie = "sscrable_bestplay=" + value + ";expires=Tue, 19-Jan-2038 03:14:07 GMT";
}

*/

})();
// END script-scope ------------------
