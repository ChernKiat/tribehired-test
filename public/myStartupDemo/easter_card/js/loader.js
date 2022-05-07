////////////////////////////////////////////////////////////
// CANVAS LOADER
////////////////////////////////////////////////////////////

 /*!
 *
 * START CANVAS PRELOADER - This is the function that runs to preload canvas asserts
 *
 */
function initPreload(){
	$('html').css("background-color", greetingOptions.colors.background);
	$('html').css("background-image", 'url("'+greetingOptions.colors.background_image+'")');
	$('#rotateHolder').css("background-color",greetingOptions.colors.background);
	$('#mainLoader, #notSupportHolder, #rotateHolder').css("color",greetingOptions.colors.text);

	toggleLoader(true);

	checkMobileEvent();

	$(window).resize(function(){
		resizeGameFunc();
	});
	resizeGameFunc();

	loader = new createjs.LoadQueue(false);
	manifest = [
					{src:'/myStartupDemo/easter_card/assets/button_fullscreen.png', id:'buttonFullscreen'},
					{src:'/myStartupDemo/easter_card/assets/button_sound_on.png', id:'buttonSoundOn'},
					{src:'/myStartupDemo/easter_card/assets/button_sound_off.png', id:'buttonSoundOff'},
			];

	pushViewportLoader('landscape');
	pushViewportLoader('portrait');

	soundOn = true;
	if($.browser.mobile || isTablet){
		if(!enableMobileSound){
			soundOn=false;
		}
	}

	if(soundOn){
		if(greetingOptions.music){
			manifest.push({src:greetingOptions.music_file, id:'greetingMusic'});
		}

		manifest.push({src:'/myStartupDemo/easter_card/assets/drop.mp3', id:'soundDrop'});
		manifest.push({src:'/myStartupDemo/easter_card/assets/dropping.mp3', id:'soundDropping'});
		manifest.push({src:'/myStartupDemo/easter_card/assets/flip.mp3', id:'soundFlip'});
		manifest.push({src:'/myStartupDemo/easter_card/assets/flip2.mp3', id:'soundFlip2'});
		manifest.push({src:'/myStartupDemo/easter_card/assets/click.mp3', id:'soundClick'});
		manifest.push({src:'/myStartupDemo/easter_card/assets/pop.mp3', id:'soundPop'});

		createjs.Sound.alternateExtensions = ["mp3"];
		loader.installPlugin(createjs.Sound);
	}

	loader.addEventListener("complete", handleComplete);
	loader.addEventListener("fileload", fileComplete);
	loader.addEventListener("error",handleFileError);
	loader.on("progress", handleProgress, this);
	loader.loadManifest(manifest);
}

function pushViewportLoader(viewport){
	for(var n=0; n<greetingOptions[viewport].cover_assets.length; n++){
		if(greetingOptions[viewport].cover_assets[n].property.type != 'text'){
			manifest.push({src:greetingOptions[viewport].cover_assets[n].src, id:viewport+'_cover'+n});
		}
	}

	for(var n=0; n<greetingOptions[viewport].popup_assets.length; n++){
		if(greetingOptions[viewport].popup_assets[n].property.type != 'text'){
			manifest.push({src:greetingOptions[viewport].popup_assets[n].src, id:viewport+'_'+greetingOptions[viewport].popup_assets[n].id});
		}
	}

	for(var n=0; n<greetingOptions[viewport].particle_effects.assets.length; n++){
		manifest.push({src:greetingOptions[viewport].particle_effects.assets[n], id:viewport+'_particle_effects'+n});
	}
}

/*!
 *
 * CANVAS FILE COMPLETE EVENT - This is the function that runs to update when file loaded complete
 *
 */
function fileComplete(evt) {
	var item = evt.item;
	//console.log("Event Callback file loaded ", evt.item.id);
}

/*!
 *
 * CANVAS FILE HANDLE EVENT - This is the function that runs to handle file error
 *
 */
function handleFileError(evt) {
	console.log("error ", evt);
}

/*!
 *
 * CANVAS PRELOADER UPDATE - This is the function that runs to update preloder progress
 *
 */
function handleProgress() {
	$('#mainLoader span').html(Math.round(loader.progress/1*100)+'%');
}

/*!
 *
 * CANVAS PRELOADER COMPLETE - This is the function that runs when preloader is complete
 *
 */
function handleComplete() {
	setTimeout(function() {
		toggleLoader(false);
		initMain();
	}, 300);
};

/*!
 *
 * TOGGLE LOADER - This is the function that runs to display/hide loader
 *
 */
function toggleLoader(con){
	if(con){
		$('#mainLoader').show();
	}else{
		$('#mainLoader').hide();
	}

	resizeGameFunc();
}
