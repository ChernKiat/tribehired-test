var re = /(,[a-z]+)\+/g;
var re0 = /(,[a-z])([a-z]*)0/g;
var re1 = /(,[a-z]{2})([a-z]*)1/g;
var re2 = /(,[a-z]{3})([a-z]*)2/g;
var re3 = /(,[a-z]{4})([a-z]*)3/g;
var re4 = /(,[a-z]{5})([a-z]*)4/g;
var re5 = /(,[a-z]{6})([a-z]*)5/g;
var re6 = /(,[a-z]{7})([a-z]*)6/g;
var re7 = /(,[a-z]{8})([a-z]*)7/g;
var re8 = /(,[a-z]{9})([a-z]*)8/g;
var re9 = /(,[a-z]{10})([a-z]*)9/g;

function checkDictionary(theWord)
{
	theWord = theWord.toLowerCase();

	if (theWord.length == 2)
	{
		return (D2.indexOf(theWord) != -1);
	}
	first3 = theWord.replace(/^(...).*/, "$1");
	if (typeof(D[first3]) == "undefined")
	{
		return false;
	}
	var theEntry = D[first3];
	if (!theEntry.match(/,$/))
	{
		// We've not looked at this entry before - uncompress it, etc.
		theEntry = theEntry.replace(/W/g, "le");
		theEntry = theEntry.replace(/K/g, "al");
		theEntry = theEntry.replace(/F/g, "man");
		theEntry = theEntry.replace(/U/g, "ous");
		theEntry = theEntry.replace(/M/g, "ment");
		theEntry = theEntry.replace(/B/g, "able");
		theEntry = theEntry.replace(/C/g, "ic");
		theEntry = theEntry.replace(/X/g, "on");
		theEntry = theEntry.replace(/Q/g, "ng");
		theEntry = theEntry.replace(/R/g, "ier");
		theEntry = theEntry.replace(/S/g, "st");
		theEntry = theEntry.replace(/Y/g, "ly");
		theEntry = theEntry.replace(/J/g, "ally");
		theEntry = theEntry.replace(/E/g, "es");
		theEntry = theEntry.replace(/L/g, "less");
		theEntry = theEntry.replace(/Z/g, "ies");
		theEntry = theEntry.replace(/P/g, "tic");
		theEntry = theEntry.replace(/I/g, "iti");
		theEntry = theEntry.replace(/V/g, "tion");
		theEntry = theEntry.replace(/H/g, "zation");
		theEntry = theEntry.replace(/A/g, "abiliti");
		theEntry = theEntry.replace(/O/g, "ologi");
		theEntry = theEntry.replace(/T/g, "est");
		theEntry = theEntry.replace(/D/g, "ed");
		theEntry = theEntry.replace(/N/g, "ness");
		theEntry = theEntry.replace(/G/g, "ing");
		theEntry = "," + theEntry + ",";
		// May have prefixes on prefixes, so need to repeat the replace.
		var more = true;
		while (more)
		{
			var theLength = theEntry.length;
			theEntry = theEntry.replace(re, "$1$1");
			theEntry = theEntry.replace(re0, "$1$2$1");
			theEntry = theEntry.replace(re1, "$1$2$1");
			theEntry = theEntry.replace(re2, "$1$2$1");
			theEntry = theEntry.replace(re3, "$1$2$1");
			theEntry = theEntry.replace(re4, "$1$2$1");
			theEntry = theEntry.replace(re5, "$1$2$1");
			theEntry = theEntry.replace(re6, "$1$2$1");
			theEntry = theEntry.replace(re7, "$1$2$1");
			theEntry = theEntry.replace(re8, "$1$2$1");
			theEntry = theEntry.replace(re9, "$1$2$1");
			more = (theLength != theEntry.length);
		}
		if (theEntry.match(/[0-@+]/))
		{
			alert("decompression oops!");
		}
		D[first3] = theEntry;
	}
	rest = theWord.replace(/^...?/, "");
	return (D[first3].indexOf("," + rest + ",") != -1);
}
