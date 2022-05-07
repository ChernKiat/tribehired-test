////////////////////////////////////////////////////////////
// CANVAS LOADER
////////////////////////////////////////////////////////////

 /*!
 * 
 * START CANVAS PRELOADER - This is the function that runs to preload canvas asserts
 * 
 */
function initPreload(){
	toggleLoader(true);
	
	$(window).resize(function(){
		resizeGameFunc();
	});
	resizeGameFunc();
	
	loader = new createjs.LoadQueue(false);
	manifest=[
			{src:'assets/bg_pattern.svg.svg', id:'bg_pattern', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_cancel.svg', id:'button_cancel', type: createjs.LoadQueue.IMAGE},
            {src:'assets/button_continue.svg', id:'button_continue', type: createjs.LoadQueue.IMAGE},
            {src:'assets/button_easy.svg', id:'button_easy', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_exit.svg', id:'button_exit', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_facebook.svg', id:'button_facebook', type: createjs.LoadQueue.IMAGE},
			
			{src:'assets/button_fullscreen.svg', id:'button_fullscreen', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_hard.svg', id:'button_hard', type: createjs.LoadQueue.IMAGE},
            {src:'assets/button_medium.svg', id:'button_medium', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_ok.svg', id:'button_ok', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_option_close.svg', id:'button_option_close', type: createjs.LoadQueue.IMAGE},
			
			{src:'assets/button_option.svg', id:'button_option', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_ok.svg', id:'button_ok', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_sound_off.svg', id:'button_sound_off', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_sound_on.svg', id:'button_sound_on', type: createjs.LoadQueue.IMAGE},
			
			{src:'assets/button_start.svg', id:'button_start', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_twitter.svg', id:'button_twitter', type: createjs.LoadQueue.IMAGE},
            {src:'assets/item_logo.svg', id:'item_logo', type: createjs.LoadQueue.IMAGE},
            {src:'assets/item_number_bg.svg', id:'item_number_bg', type: createjs.LoadQueue.IMAGE},
			{src:'assets/button_whatsapp.svg', id:'button_whatsapp', type: createjs.LoadQueue.IMAGE},
			{src:'assets/item_timer.svg', id:'item_timer', type: createjs.LoadQueue.IMAGE},
			
			];
	
	soundOn = true;		
	if($.browser.mobile || isTablet){
		if(!enableMobileSound){
			soundOn=false;
		}
	}
	
	if(soundOn){
		manifest.push({src:'assets/sounds/click.ogg', id:'soundClick'});
		manifest.push({src:'assets/sounds/complete.ogg', id:'soundComplete'});
		manifest.push({src:'assets/sounds/failed.ogg', id:'soundOver'});
		manifest.push({src:'assets/sounds/start.ogg', id:'soundStart'});
		manifest.push({src:'assets/sounds/select.ogg', id:'soundSelect'});
        manifest.push({src:'assets/sounds/error.ogg', id:'soundError'});
		
		createjs.Sound.alternateExtensions = ["mp3"];
		loader.installPlugin(createjs.Sound);
	}
	
	loader.addEventListener("complete", handleComplete);
	loader.addEventListener("fileload", fileComplete);
	loader.addEventListener("error",handleFileError);
	loader.on("progress", handleProgress, this);
	loader.loadManifest(manifest);
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
	//console.log("error ", evt);
}

/*!
 * 
 * CANVAS PRELOADER UPDATE - This is the function that runs to update preloder progress
 * 
 */
function handleProgress() {
	$('#mainLoader span').html('LOADING '+Math.round(loader.progress/1*100)+'');
}

/*!
 * 
 * CANVAS PRELOADER COMPLETE - This is the function that runs when preloader is complete
 * 
 */
function handleComplete() {
	toggleLoader(false);
	initMain();
};

/*!
 * 
 * TOGGLE LOADER - This is the function that runs to display/hide loader
 * 
 */
function toggleLoader(con){
	if(con){
		$('#mainLoader').show();
		$('#mainHolder').hide();
	}else{
		$('#mainLoader').hide();
		$('#mainHolder').show();
	}
}