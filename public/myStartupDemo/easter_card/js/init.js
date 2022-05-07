////////////////////////////////////////////////////////////
// INIT
////////////////////////////////////////////////////////////
var isLoaded=false;

 /*!
 *
 * DOCUMENT READY
 *
 */
 $(function() {
	// Check for running exported on file protocol
	if (window.location.protocol.substr(0, 4) === "file"){
		alert("To install the app just upload folder 'app' to your server. The game won't run locally with some browser like Chrome due to some security mode.");
	}

	$(window).resize(function(){
		resizeLoaderFunc();
	});
	resizeLoaderFunc();
	checkBrowser();
});

/*!
 *
 * LOADER RESIZE - This is the function that runs to centeralised loader when resize
 *
 */
 function resizeLoaderFunc(){
	$('#mainLoader').css('left', checkContentWidth($('#mainLoader')));
	$('#mainLoader').css('top', checkContentHeight($('#mainLoader')));
 }

/*!
 *
 * BROWSER DETECT - This is the function that runs for browser and feature detection
 *
 */
var browserSupport=false;
var isTablet;
function checkBrowser(){
	isTablet = (/ipad|android|android 3.0|xoom|sch-i800|playbook|tablet|kindle/i.test(navigator.userAgent.toLowerCase()));
	deviceVer=getDeviceVer();

	var canvasEl = document.createElement('canvas');
	if(canvasEl.getContext){
	  browserSupport=true;
	}

	if(browserSupport){
		if(!isLoaded){
			isLoaded=true;
			loadConfigXML('/myStartupDemo/easter_card/config.xml');
		}
	}else{
		//browser not support
		$('#notSupportHolder').show();
	}
}
