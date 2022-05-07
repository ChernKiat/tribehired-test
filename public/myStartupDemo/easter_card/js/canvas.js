////////////////////////////////////////////////////////////
// CANVAS
////////////////////////////////////////////////////////////
var stage
var canvasW=0;
var canvasH=0;

/*!
 * 
 * START GAME CANVAS - This is the function that runs to setup game canvas
 * 
 */
function initAppCanvas(){
	stage = new createjs.Stage("appCanvas");
	
	createjs.Touch.enable(stage);
	stage.enableMouseOver(20);
	stage.mouseMoveOutside = true;
	
	createjs.Ticker.framerate = 60;
	createjs.Ticker.addEventListener("tick", tick);	
}

var guide = false;
var canvasContainer, mainContainer, gameContainer, resultContainer;
var guideline, buttonFullscreen, buttonSoundOn, buttonSoundOff, fpsLabel;

$.container = {};
$.objects = {};
$.particle = {};

/*!
 * 
 * BUILD GAME CANVAS ASSERTS - This is the function that runs to build game canvas asserts
 * 
 */
function buildAppCanvas(){
	canvasContainer = new createjs.Container();
	landscapeContainer = new createjs.Container();
	portraitContainer = new createjs.Container();
	
	landscapeAppContainer = new createjs.Container();
	landscapeParticleContainer = new createjs.Container();
	landscapeCoverContainer = new createjs.Container();
	landscapeCoverShadowContainer = new createjs.Container();
	landscapeCardContainer = new createjs.Container();
	landscapeCardInnerContainer = new createjs.Container();
	
	portraitAppContainer = new createjs.Container();
	portraitParticleContainer = new createjs.Container();
	portraitCoverContainer = new createjs.Container();
	portraitCoverShadowContainer = new createjs.Container();
	portraitCardContainer = new createjs.Container();
	portraitCardInnerContainer = new createjs.Container();
	
	optionsContainer = new createjs.Container();
	
	fpsLabel = new createjs.Text("-- fps", "bold 18px Arial", "#FFF");
	fpsLabel.x = 70;
	fpsLabel.y = 50;
	fpsLabel.visible = false;
	
	if(guide){
		guideline = new createjs.Shape();	
		guideline.graphics.setStrokeStyle(2).beginStroke('red').drawRect((stageW-contentW)/2, (stageH-contentH)/2, contentW, contentH);
		fpsLabel.visible = true;
	}
	
	pushViewportCanvas('landscape');
	pushViewportCanvas('portrait');
	
	//option
	buttonFullscreen = new createjs.Bitmap(loader.getResult('buttonFullscreen'));
	centerReg(buttonFullscreen);
	buttonSoundOn = new createjs.Bitmap(loader.getResult('buttonSoundOn'));
	centerReg(buttonSoundOn);
	buttonSoundOff = new createjs.Bitmap(loader.getResult('buttonSoundOff'));
	centerReg(buttonSoundOff);
	buttonSoundOn.visible = false;
	
	createHitarea(buttonFullscreen);
	createHitarea(buttonSoundOn);
	createHitarea(buttonSoundOff);
	
	if(!greetingOptions.fullscreen){
		buttonFullscreen.visible = false;	
	}
	
	optionsContainer.addChild(buttonFullscreen, buttonSoundOn, buttonSoundOff);
	
	landscapeCardContainer.addChild(landscapeParticleContainer, landscapeCardInnerContainer, landscapeCoverShadowContainer, landscapeCoverContainer);
	portraitCardContainer.addChild(portraitParticleContainer, portraitCardInnerContainer, portraitCoverShadowContainer, portraitCoverContainer);
	landscapeContainer.addChild(landscapeAppContainer, landscapeCardContainer);
	portraitContainer.addChild(portraitAppContainer, portraitCardContainer);
	
	canvasContainer.addChild(landscapeContainer, portraitContainer, optionsContainer, fpsLabel, guideline);
	stage.addChild(canvasContainer);
	
	resizeCanvas();
}

function pushViewportCanvas(viewport){

	for(var n=0; n<greetingOptions[viewport].cover_container.length; n++){
		var containerID = viewport+'_container'+n;
		$.container[containerID] = new createjs.Container();
		pushTransitionData($.container[containerID], greetingOptions[viewport].cover_container[n].property);
		
		window[viewport+'CoverContainer'].addChild($.container[containerID]);
	}

	var dimObj = null;
	for(var n=0; n<greetingOptions[viewport].cover_assets.length; n++){
		var coverID = viewport+'_cover'+n;

		if(greetingOptions[viewport].cover_assets[n].property.type == 'text'){
			$.objects[coverID] = new createjs.Text();
			$.objects[coverID].id = coverID;
			$.objects[coverID].font = greetingOptions[viewport].cover_assets[n].property.font;
			$.objects[coverID].lineHeight = greetingOptions[viewport].cover_assets[n].property.lineHeight;
			$.objects[coverID].color = greetingOptions[viewport].cover_assets[n].property.color;
			$.objects[coverID].textAlign = greetingOptions[viewport].cover_assets[n].property.align;
			$.objects[coverID].text = greetingOptions[viewport].cover_assets[n].src;
			$.objects[coverID].isText = true;
		}else{
			$.objects[coverID] = new createjs.Bitmap(loader.getResult(coverID));

			if(greetingOptions[viewport].popup_assets[n].property.type == "base"){
				$.objects[coverID+'Shadow'] = createDimObject($.objects[coverID]);
				window[viewport+'CoverShadowContainer'].addChild($.objects[coverID+'Shadow']);
			}
			
			if(greetingOptions[viewport].cover_assets[n].property.dim != undefined){
				$.objects[coverID+'Dim'] = createDimObject($.objects[coverID]);
				dimObj = $.objects[coverID+'Dim'];
			}
		}
		pushTransitionData($.objects[coverID], greetingOptions[viewport].cover_assets[n].property);
		
		$.container[viewport+'_container'+0].addChild($.objects[coverID]);
	}

	if(dimObj != null){
		$.container[viewport+'_container'+0].addChild(dimObj);
	}

	//
	for(var n=0; n<greetingOptions[viewport].popup_container.length; n++){
		var containerID = viewport+'_popcontainer'+n;
		$.container[containerID] = new createjs.Container();
		pushTransitionData($.container[containerID], greetingOptions[viewport].popup_container[n].property);
		
		window[viewport+'CardInnerContainer'].addChild($.container[containerID]);
	}

	for(var n=0; n<greetingOptions[viewport].popup_assets.length; n++){
		var popupID = viewport+'_'+greetingOptions[viewport].popup_assets[n].id;
		$.objects[popupID+'Shadow'] = new createjs.Shape();
		$.objects[popupID+'Dim'] = new createjs.Shape();

		if(greetingOptions[viewport].popup_assets[n].property.type == 'text'){
			$.objects[popupID] = new createjs.Text();
			$.objects[popupID].id = popupID;
			$.objects[popupID].font = greetingOptions[viewport].popup_assets[n].property.font;
			$.objects[popupID].lineHeight = greetingOptions[viewport].popup_assets[n].property.lineHeight;
			$.objects[popupID].color = greetingOptions[viewport].popup_assets[n].property.color;
			$.objects[popupID].textAlign = greetingOptions[viewport].popup_assets[n].property.align;
			$.objects[popupID].text = greetingOptions[viewport].popup_assets[n].src;
			$.objects[popupID].isText = true;
		}else{
			$.objects[popupID] = new createjs.Bitmap(loader.getResult(popupID));

			if(greetingOptions[viewport].popup_assets[n].property.type == 'spritesheet'){
				var imgW = getValue(greetingOptions[viewport].popup_assets[n].property.width);
				var imgH = getValue(greetingOptions[viewport].popup_assets[n].property.height);
				var regX = getValue(greetingOptions[viewport].popup_assets[n].property.regX);
				var regY = getValue(greetingOptions[viewport].popup_assets[n].property.regY);
				var count = getValue(greetingOptions[viewport].popup_assets[n].property.count);
				var speed = getValue(greetingOptions[viewport].popup_assets[n].property.speed);
				speed = speed == 0 ? 1 : speed;
				regX = regX == 0 ? imgW/2 : regX;
				regY = regY == 0 ? imgH/2 : regY;
	
				var _frame = {"regX": regX, "regY": regY, "width": imgW, "height": imgH, "count": count};
				var _animations = {animate:[0,count-1, "animate", speed]};
				
				var animateData = new createjs.SpriteSheet({
					"images": [loader.getResult(popupID).src],
					"frames": _frame,
					"animations": _animations
				});
				
				$.objects[popupID] = new createjs.Sprite(animateData, "animate");
				$.objects[popupID].framerate = 20;
			}

			if(greetingOptions[viewport].popup_assets[n].property.dim != undefined){
				$.objects[popupID+'Dim'] = createDimObject($.objects[popupID]);
				$.objects[popupID].objDim = $.objects[popupID+'Dim'];
			}

			if(greetingOptions[viewport].popup_assets[n].property.type == "base"){
				$.objects[popupID+'Shadow'] = createDimObject($.objects[popupID]);
				$.objects[popupID].objShadow = $.objects[popupID+'Shadow'];

				$.objects[popupID].cursor = "pointer";
				$.objects[popupID].addEventListener("click", function(evt) {
					toggleAnimation();
				});
			}
		}

		pushTransitionData($.objects[popupID], greetingOptions[viewport].popup_assets[n].property);
		
		$.container[viewport+'_popcontainer'+0].addChild($.objects[popupID+'Shadow'], $.objects[popupID], $.objects[popupID+'Dim']);
	}
	resetObjectProperty(viewport);
}

/*!
 * 
 * BUILD CANVAS FUNC - This is the function that runs to build game canvas func
 * 
 */
function getValue(value){
	if(isNaN(value)){
		return 0;	
	}else{
		return value;	
	}
}

function createShadowObject(obj){
	var newDim = obj.clone();
	newDim.filters = [
		new createjs.ColorFilter(0,0,0,1,0,0,0,0)
	];
	newDim.cache(0, 0, obj.image.naturalWidth, obj.image.naturalHeight);

	return newDim;
}

function createDimObject(obj){
	var newDim = obj.clone();
	newDim.filters = [
		new createjs.ColorFilter(0,0,0,1,0,0,0,0)
	];
	newDim.cache(0, 0, obj.image.naturalWidth, obj.image.naturalHeight);

	return newDim;
}

function resetObjectProperty(viewport){
	for(var n=0; n<greetingOptions[viewport].cover_container.length; n++){
		var containerID = viewport+'_container'+n;
		setObjectProperty($.container[containerID]);
	}

	for(var n=0; n<greetingOptions[viewport].cover_assets.length; n++){
		var containerID = viewport+'_container'+n;
		var objID = viewport+'_cover'+n;
		var objDimID = viewport+'_cover'+n+'Dim';
		var objShadowID = viewport+'_cover'+n+'Shadow';

		setObjectShadowProperty($.container[containerID], $.objects[objShadowID], greetingOptions[viewport].cover_assets[n].property.type, 'close');
		setObjectProperty($.objects[objID], $.objects[objDimID], greetingOptions[viewport].cover_assets[n].property.dim, 'open');
	}

	for(var n=0; n<greetingOptions[viewport].popup_container.length; n++){
		var containerID = viewport+'_popcontainer'+n;

		setObjectProperty($.container[containerID]);
	}

	for(var n=0; n<greetingOptions[viewport].popup_assets.length; n++){
		var objID = viewport+'_'+greetingOptions[viewport].popup_assets[n].id;
		var objDimID = viewport+'_'+greetingOptions[viewport].popup_assets[n].id+'Dim';
		var objShadowID = viewport+'_'+greetingOptions[viewport].popup_assets[n].id+'Shadow';

		setObjectShadowProperty($.objects[objID], $.objects[objShadowID], greetingOptions[viewport].popup_assets[n].property.type, 'open');
		setObjectProperty($.objects[objID], $.objects[objDimID], greetingOptions[viewport].popup_assets[n].property.dim, 'close');
	}

	this[viewport+'CardInnerContainer'].visible = false;
}

function setObjectShadowProperty(obj, objShadow, type, transition){
	if(type == 'base'){	
		objShadow.regX = obj.transition[transition].regX;
	
		objShadow.regY = obj.transition[transition].regY;
	
		objShadow.x = obj.transition[transition].x + greetingOptions.shadow.x;
	
		objShadow.y = obj.transition[transition].y + greetingOptions.shadow.y;
	
		objShadow.scaleX = obj.transition[transition].scaleX + greetingOptions.shadow.scaleX;
	
		objShadow.scaleY = obj.transition[transition].scaleY + greetingOptions.shadow.scaleX;
	
		objShadow.skewX = obj.transition[transition].skewX;
	
		objShadow.skewY = obj.transition[transition].skewY;
	
		objShadow.alpha = transition == 'close' ? greetingOptions.shadow.alpha : 0;
	
		objShadow.rotation = obj.transition[transition].rotation;
	}
}

function setObjectProperty(obj, objDim, dim, transition){
	if(dim != undefined){
		var alphaNum = getDimAlpha(dim, transition);
	
		objDim.regX = obj.transition.open.regX;
		objDim.regX = obj.transition.close.regX;
	
		objDim.regY = obj.transition.open.regY;
		objDim.regY = obj.transition.close.regY;
	
		objDim.x = obj.transition.open.x;
		objDim.x = obj.transition.close.x;
	
		objDim.y = obj.transition.open.y;
		objDim.y = obj.transition.close.y;
	
		objDim.scaleX = obj.transition.open.scaleX;
		objDim.scaleX = obj.transition.close.scaleX;
	
		objDim.scaleY = obj.transition.open.scaleY;
		objDim.scaleY = obj.transition.close.scaleY;
	
		objDim.skewX = obj.transition.open.skewX;
		objDim.skewX = obj.transition.close.skewX;
	
		objDim.skewY = obj.transition.open.skewY;
		objDim.skewY = obj.transition.close.skewY;
	
		objDim.alpha = alphaNum;
	
		objDim.rotation = obj.transition.open.rotation;
		objDim.rotation = obj.transition.close.rotation;
	}
	
	obj.regX = obj.transition.open.regX;
	obj.regX = obj.transition.close.regX;
	
	obj.regY = obj.transition.open.regY;
	obj.regY = obj.transition.close.regY;
	
	obj.x = obj.transition.open.x;
	obj.x = obj.transition.close.x;
	
	obj.y = obj.transition.open.y;
	obj.y = obj.transition.close.y;
	
	obj.scaleX = obj.transition.open.scaleX;
	obj.scaleX = obj.transition.close.scaleX;
	
	obj.scaleY = obj.transition.open.scaleY;
	obj.scaleY = obj.transition.close.scaleY;
	
	obj.skewX = obj.transition.open.skewX;
	obj.skewX = obj.transition.close.skewX;
	
	obj.skewY = obj.transition.open.skewY;
	obj.skewY = obj.transition.close.skewY;
	
	obj.alpha = obj.transition.open.alpha;
	obj.alpha = obj.transition.close.alpha;
	
	obj.rotation = obj.transition.open.rotation;
	obj.rotation = obj.transition.close.rotation;
}

function pushTransitionData(obj, property){
	obj.transition = {
		open:{
			speed:1.5, x:0, y:0, scaleX:1, scaleY:1, skewX:0, skewY:0, regX:0, regY:0, rotation:0, alpha:1, delay:0
				}, 
		close:{
			speed:1, x:0, y:0, scaleX:1, scaleY:1, skewX:0, skewY:0, regX:0, regY:0, rotation:0, alpha:1, delay:0
		},
		openInner:{
			speed:1.5, x:0, y:0, scaleX:1, scaleY:1, skewX:0, skewY:0, regX:0, regY:0, rotation:0, alpha:1, delay:0
		},
		closeInner:{
			speed:1, x:0, y:0, scaleX:1, scaleY:1, skewX:0, skewY:0, regX:0, regY:0, rotation:0, alpha:1, delay:0
		},
	};

	setTransitionData(obj, property, 'open');
	setTransitionData(obj, property, 'close');
	setTransitionData(obj, property, 'openInner');
	setTransitionData(obj, property, 'closeInner');
}

function setTransitionData(obj, property, transition){
	$.each(obj.transition[transition], function(key, value){
		var getKey = key.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
		var propertyKey = transition + getKey;

		if(key == 'type' && property[key] != undefined){
			obj.transition[transition][key] = property[key];
		}else if(!isNaN(property[propertyKey])){
			obj.transition[transition][key] = property[propertyKey];
		}
	});
}


/*!
 * 
 * RESIZE GAME CANVAS - This is the function that runs to resize game canvas
 * 
 */
function resizeCanvas(){
 	if(canvasContainer!=undefined){
		var appCanvas = document.getElementById("appCanvas");
		appCanvas.width = stageW;
		appCanvas.height = stageH;
		
		canvasW = stageW;
		canvasH = stageH;
		
		appData.moveX = canvasW/2;
		appData.moveY = canvasH/2;
		
		landscapeContainer.visible = false;
		portraitContainer.visible = false;

		landscapeCardContainer.x = appData.landscape.cardX;
		landscapeCardContainer.y = appData.landscape.cardY;

		portraitCardContainer.x = appData.portrait.cardX;
		portraitCardContainer.y = appData.portrait.cardY;
		
		this[appData.viewport+'Container'].visible = true;
		
		if(appData.init){
			if(appData.viewport_old != '' && appData.viewport != appData.viewport_old){
				if(appData.viewport == 'landscape'){
					landscapeCardContainer.visible = true;
					portraitCardContainer.visible = false;
					startGreetingApp();
				}
				
				if(appData.viewport == 'portrait'){
					landscapeCardContainer.visible = false;
					portraitCardContainer.visible = true;
					startGreetingApp();
				}
			}
			appData.viewport_old = appData.viewport;
		}
		
		if(greetingOptions.music){
			buttonSoundOff.x = (canvasW - offset.x) - 60;
			buttonSoundOff.y = offset.y + 45;
			
			buttonSoundOn.x = buttonSoundOff.x;
			buttonSoundOn.y = buttonSoundOff.y;
			
			buttonFullscreen.x = buttonSoundOff.x - 70;
			buttonFullscreen.y = buttonSoundOff.y;
		}else{
			buttonFullscreen.x = (canvasW - offset.x) - 60;
			buttonFullscreen.y = offset.y + 45;
			
			buttonSoundOn.visible = false;
			buttonSoundOff.visible = false;
		}
	}
}

/*!
 * 
 * REMOVE GAME CANVAS - This is the function that runs to remove game canvas
 * 
 */
 function removeAppCanvas(){
	 stage.autoClear = true;
	 stage.removeAllChildren();
	 stage.update();
	 createjs.Ticker.removeEventListener("tick", tick);
	 createjs.Ticker.removeEventListener("tick", stage);
 }

/*!
 * 
 * CANVAS LOOP - This is the function that runs for canvas loop
 * 
 */ 
function tick(event) {
	fpsLabel.text = Math.round(createjs.Ticker.getMeasuredFPS()) + " fps";
	
	updateApp();
	stage.update(event);
}

/*!
 * 
 * CANVAS MISC FUNCTIONS
 * 
 */
function centerReg(obj){
	obj.regX=obj.image.naturalWidth/2;
	obj.regY=obj.image.naturalHeight/2;
}

function createHitarea(obj){
	obj.hitArea = new createjs.Shape(new createjs.Graphics().beginFill("#000").drawRect(0, 0, obj.image.naturalWidth, obj.image.naturalHeight));
}