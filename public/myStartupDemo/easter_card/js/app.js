////////////////////////////////////////////////////////////
// APP
////////////////////////////////////////////////////////////
var stageW=1920;
var stageH=1080;
var contentW = 1600;
var contentH = 850;

var appData = {
					viewport:'', 
					viewport_old:'',
					init:false,
					action:false,
					active:false,
					moveX:0,
					moveY:0,
					landscape:{
								active:false,
								width:0,
								height:0,
								cardX:0,
								cardY:0,
								contentWidth:0,
								contentHeight:0,
								parallaxX:[],
								parallaxY:[],
								parallaxSkewX:[],
								parallaxSkewY:[],
								parallaxRotate:[]
							},
							
					portrait:{
								active:false,
								width:0,
								height:0,
								cardX:0,
								cardY:0,
								contentWidth:0,
								contentHeight:0,
								parallaxX:[],
								parallaxY:[],
								parallaxSkewX:[],
								parallaxSkewY:[],
								parallaxRotate:[]
							},
				};
				
var greetingOptions = {
						colors:{
									text:'#000',
									background:'#000',
									background_image:''
								},
						music:false,
						music_file:'',
						fullscreen:false,
						shadow:{
							scaleX:.02,
							scaleY:.02,
							alpha:.2,
							x:0,
							y:20
						},
						dim:{
							openLeft:0,
							openRight:.2,
							closeLeft:.4,
							closeRight:.6,
						},
						landscape:{
									cover_container:[],
									popup_container:[],
									cover_assets:[],
									popup_assets:[],
									text:[],
									parallax_effects:[],
									links:[],
									particle_effects:{
														enable:false,
														assets:[],
														alpha:true,
														scale:true,
														total:50,
														speed:1.5,
														startY:-400,
														endY:-200,
														rangeX:50
												}
									},
						portrait:{
									cover_container:[],
									popup_container:[],
									cover_assets:[],
									popup_assets:[],
									text:[],
									parallax_effects:[],
									links:[],
									particle_effects:{
														enable:false,
														assets:[],
														alpha:true,
														scale:true,
														total:50,
														speed:1.5,
														startY:-400,
														endY:-200,
														rangeX:50
												}
									},
					}

/*!
 * 
 * START BUILD APP - This is the function that runs build app
 * 
 */
function initMain(){
	if(!$.browser.mobile || !isTablet){
		$('#canvasHolder').show();	
	}
	
	$('#appCanvas').hide();
	$('#appCanvas').fadeIn();
	
	initAppCanvas();
	buildAppCanvas();
	buildAppGeneral();

	initApp();	
	
	resizeCanvas();
}

var windowW=windowH=0;
var scalePercent=0;
var offset = {x:0, y:0, left:0, top:0};

/*!
 * 
 * GAME RESIZE - This is the function that runs to resize and centralize the game
 * 
 */
function resizeGameFunc(){
	setTimeout(function() {
		$('.mobileRotate').css('left', checkContentWidth($('.mobileRotate')));
		$('.mobileRotate').css('top', checkContentHeight($('.mobileRotate')));
		
		var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);
		var extra = 0;
		windowW = $(window).width();
		windowH = $(window).height();
		
		if(isSafari){
			//fix safari browser
			windowW = window.innerWidth;
			windowH = window.innerHeight;
			extra = $(window).height() - window.innerHeight;
		}
		
		if(detectScreenSize()){
			appData.viewport = 'portrait';
        }else{
			appData.viewport = 'landscape';
        }
		
		stageW = appData[appData.viewport].width;
		stageH = appData[appData.viewport].height;
		contentW = appData[appData.viewport].contentWidth;
		contentH = appData[appData.viewport].contentHeight;
		
		scalePercent = windowW/contentW;
		if((contentH*scalePercent)>windowH){
			scalePercent = windowH/contentH;
		}
		
		scalePercent = scalePercent > 1 ? 1 : scalePercent;
		
		if(windowW > stageW && windowH > stageH){
			if(windowW > stageW){
				scalePercent = windowW/stageW;
				if((stageH*scalePercent)>windowH){
					scalePercent = windowH/stageH;
				}	
			}
		}
		
		var newCanvasW = (stageW*scalePercent);
		var newCanvasH = (stageH*scalePercent);
		
		offset.left = 0;
		offset.top = 0;
		
		if(newCanvasW > windowW){
			offset.left = -((newCanvasW) - windowW);
		}else{
			offset.left = windowW - (newCanvasW);
		}
		
		if(newCanvasH > windowH){
			offset.top = -((newCanvasH) - windowH);
		}else{
			offset.top = windowH - (newCanvasH);	
		}
		
		offset.x = 0;
		offset.y = 0;
		
		if(offset.left < 0){
			offset.x = Math.abs((offset.left/scalePercent)/2);
		}
		if(offset.top < 0){
			offset.y = Math.abs((offset.top/scalePercent)/2);
		}
		
		$('canvas').css('width',newCanvasW);
		$('canvas').css('height',newCanvasH);
		
		$('canvas').css('left', (offset.left/2));
		$('canvas').css('top', (offset.top/2));
		
		if(isSafari){
			$(window).scrollTop(1000);
		}
		
		resizeCanvas();
	}, 100);	
}

/*!
 * 
 * DETECT SCREEN SIZE - This is the function that runs to detect screen size
 * 
 */
function detectScreenSize(){
    if($.browser.mobile || isTablet){
        if (window.matchMedia("(orientation: landscape)").matches) {
			return false;
		}else{
			return true;
		}
    }else{
        if(windowW > windowH){
            return false;
        }else {
            return true;
        }
    }
}


/*!
 * 
 * BUILD APP - This is the function that runs build app
 * 
 */
function buildAppGeneral(){
	appData.moveX = canvasW/2;
	appData.moveY = canvasH/2;
	
	stage.on("stagemousemove", function(evt) {
		appData.moveX = evt.stageX;
		appData.moveY = evt.stageY;
	});
	
	buttonSoundOff.cursor = "pointer";
	buttonSoundOff.addEventListener("click", function(evt) {
		toggleGameMute(true);
	});
	
	buttonSoundOn.cursor = "pointer";
	buttonSoundOn.addEventListener("click", function(evt) {
		toggleGameMute(false);
	});
	
	buttonFullscreen.cursor = "pointer";
	buttonFullscreen.addEventListener("click", function(evt) {
		toggleFullScreen();
	});
	
	landscapeCoverContainer.cursor = "pointer";
	landscapeCoverContainer.addEventListener("click", function(evt) {
		toggleCoverAnimation();
	});
	
	portraitCoverContainer.cursor = "pointer";
	portraitCoverContainer.addEventListener("click", function(evt) {
		toggleCoverAnimation();
	});
}

/*!
 * 
 * INIT APP - This is the function that runs start app
 * 
 */
function initApp(){
	appData.init = true;
	startGreetingApp();
	
	initAssetsParallax('landscape');
	initAssetsParallax('portrait');
	
	initAssetsLink('landscape');
	initAssetsLink('portrait');
}

function initAssetsParallax(viewport){
	for(var n=0; n<greetingOptions[viewport].parallax_effects.length; n++){
		var parralxArray = greetingOptions[viewport].parallax_effects[n].movement.split(',');
		var percentArray = greetingOptions[viewport].parallax_effects[n].percentage.split(',');
		for(var p=0; p<parralxArray.length; p++){
			if(parralxArray[p] == 'x'){
				parallaxType = parallaxElementX;
			}else if(parralxArray[p] == 'y'){
				parallaxType = parallaxElementY;
			}else if(parralxArray[p] == 'rotation'){
				parallaxType = parallaxElementRotation;
			}else if(parralxArray[p] == 'skewX'){
				parallaxType = parallaxElementSkewX;
			}else if(parralxArray[p] == 'skewY'){
				parallaxType = parallaxElementSkewY;
			}

			parallaxType(viewport, viewport+'_'+greetingOptions[viewport].parallax_effects[n].id, Number(percentArray[p]), greetingOptions[viewport].parallax_effects[n].reverse);
		}
	}
}

function initAssetsLink(viewport){
	for(var n=0; n<greetingOptions[viewport].links.length; n++){
		createURL(viewport, greetingOptions[viewport].links[n]);
	}	
}

/*!
 * 
 * OPEN COVER - This is the function that runs open cover
 * 
 */
function toggleCoverAnimation(){
	if(!appData.action){
		return;
	}

	if(appData.animation){
		return;
	}

	appData.animation = true;
	var transition = 'close';

	if(appData.coverAnimation){
		//close
		appData.active = false;
		appData.coverAnimation = false;
	}else{
		//open
		playSound('soundClick');
		playSound('soundFlip');
		if(greetingOptions[appData.viewport].particle_effects.enable){
			toggleParticles(appData.viewport, true);
		}
		appData.active = true;
		appData.coverAnimation = true;
		transition = 'open';
	}

	var ease = transition == 'open' ? Expo.easeOut : Expo.easeOut;
	for(var n=0; n<greetingOptions[appData.viewport].cover_container.length; n++){
		var coverID = appData.viewport+'_container'+n;
		var obj = $.container[coverID];

		TweenMax.to(obj, obj.transition[transition].speed, {
			delay:obj.transition[transition].delay,
			x:obj.transition[transition].x,
			y:obj.transition[transition].y,
			scaleX:obj.transition[transition].scaleX,
			scaleY:obj.transition[transition].scaleY,
			skewX:obj.transition[transition].skewX,
			skewY:obj.transition[transition].skewY,
			regX:obj.transition[transition].regX,
			regY:obj.transition[transition].regY,
			rotation:obj.transition[transition].rotation,
			alpha:obj.transition[transition].alpha,
			ease:ease,
			overwrite:true,
			onComplete:checkNextAniamtion,
			onCompleteParams:['cover',transition,n],
		});
	}

	for(var n=0; n<greetingOptions[appData.viewport].cover_assets.length; n++){
		var coverID = appData.viewport+'_cover'+n;
		var obj = $.objects[coverID];

		TweenMax.to(obj, obj.transition[transition].speed, {
			delay:obj.transition[transition].delay,
			x:obj.transition[transition].x,
			y:obj.transition[transition].y,
			scaleX:obj.transition[transition].scaleX,
			scaleY:obj.transition[transition].scaleY,
			skewX:obj.transition[transition].skewX,
			skewY:obj.transition[transition].skewY,
			regX:obj.transition[transition].regX,
			regY:obj.transition[transition].regY,
			rotation:obj.transition[transition].rotation,
			alpha:obj.transition[transition].alpha,
			ease:ease,
			overwrite:true
		});

		if(greetingOptions[appData.viewport].cover_assets[n].property.dim != undefined){
			var objID = appData.viewport+'_cover'+n+'Dim';
			var objShadow = $.objects[objID];

			var alphaTransition = transition == 'open' ? 'close' : 'open';
			var alphaNum = getDimAlpha(greetingOptions[appData.viewport].cover_assets[n].property.dim, alphaTransition);
			TweenMax.to(objShadow, obj.transition[transition].speed, {
				delay:obj.transition[transition].delay,
				x:obj.transition[transition].x,
				y:obj.transition[transition].y,
				scaleX:obj.transition[transition].scaleX,
				scaleY:obj.transition[transition].scaleY,
				skewX:obj.transition[transition].skewX,
				skewY:obj.transition[transition].skewY,
				regX:obj.transition[transition].regX,
				regY:obj.transition[transition].regY,
				rotation:obj.transition[transition].rotation,
				alpha:alphaNum,
				ease:ease,
				overwrite:true
			});
		}

		if(greetingOptions[appData.viewport].cover_assets[n].property.type == 'base'){
			var objID = appData.viewport+'_cover'+n+'Shadow';
			var objShadow = $.objects[objID];

			var alphaNum = transition == 'open' ? 0 : greetingOptions.shadow.alpha;
			TweenMax.to(objShadow, obj.transition[transition].speed, {
				delay:obj.transition[transition].delay,
				alpha:alphaNum,
				ease:ease,
				overwrite:true
			});
		}
	}
}

/*!
 * 
 * OPEN POPUP - This is the function that runs open popup
 * 
 */
function toggleAnimation(){
	if(!appData.action){
		return;
	}

	if(appData.animation){
		return;
	}

	appData.animation = true;
	var transition = 'close';

	if(appData.cardAnimation){
		//close
		playSound('soundFlip2');
		if(greetingOptions[appData.viewport].particle_effects.enable){
			toggleParticles(appData.viewport, false);
		}
		appData.cardAnimation = false;
	}else{
		//open
		playSound('soundFlip2');
		appData.cardAnimation = true;
		transition = 'open';
	}

	var ease = transition == 'open' ? Bounce.easeOut : Expo.easeOut;

	for(var c=0; c<greetingOptions[appData.viewport].cover_container.length; c++){
		var coverTransition = transition + 'Inner';
		var coverID = appData.viewport+'_container'+c;
		var container = $.container[coverID];

		TweenMax.to(container, (container.transition[coverTransition].speed/2), {
			delay:container.transition[coverTransition].delay,
			alpha:container.transition[coverTransition].alpha,
		});

		TweenMax.to(container, container.transition[coverTransition].speed, {
			delay:container.transition[coverTransition].delay,
			x:container.transition[coverTransition].x,
			y:container.transition[coverTransition].y,
			scaleX:container.transition[coverTransition].scaleX,
			scaleY:container.transition[coverTransition].scaleY,
			skewX:container.transition[coverTransition].skewX,
			skewY:container.transition[coverTransition].skewY,
			regX:container.transition[coverTransition].regX,
			regY:container.transition[coverTransition].regY,
			rotation:container.transition[coverTransition].rotation,
			ease:ease,
		});
	}

	for(var n=0; n<greetingOptions[appData.viewport].popup_container.length; n++){
		var coverID = appData.viewport+'_popcontainer'+n;
		var obj = $.container[coverID];

		TweenMax.to(obj, obj.transition[transition].speed, {
			delay:obj.transition[transition].delay,
			x:obj.transition[transition].x,
			y:obj.transition[transition].y,
			scaleX:obj.transition[transition].scaleX,
			scaleY:obj.transition[transition].scaleY,
			skewX:obj.transition[transition].skewX,
			skewY:obj.transition[transition].skewY,
			regX:obj.transition[transition].regX,
			regY:obj.transition[transition].regY,
			rotation:obj.transition[transition].rotation,
			alpha:obj.transition[transition].alpha,
			ease:ease,
			overwrite:true
		});
	}
	
	for(var n=0; n<greetingOptions[appData.viewport].popup_assets.length; n++){
		var ease = transition == 'open' ? Bounce.easeOut : Expo.easeOut;

		var objID = appData.viewport+'_'+greetingOptions[appData.viewport].popup_assets[n].id;
		var obj = $.objects[objID];

		if(greetingOptions[appData.viewport].popup_assets[n].property.transition == 'fade'){
			ease = transition == 'open' ? Elastic.easeOut.config( 1, 0.3) : Expo.easeOut;
		}

		TweenMax.to(obj, obj.transition[transition].speed, {
			delay:obj.transition[transition].delay,
			x:obj.transition[transition].x,
			y:obj.transition[transition].y,
			scaleX:obj.transition[transition].scaleX,
			scaleY:obj.transition[transition].scaleY,
			skewX:obj.transition[transition].skewX,
			skewY:obj.transition[transition].skewY,
			regX:obj.transition[transition].regX,
			regY:obj.transition[transition].regY,
			rotation:obj.transition[transition].rotation,
			alpha:obj.transition[transition].alpha,
			ease:ease,
			overwrite:true,
			onStart:playPopSound,
			onStartParams:[greetingOptions[appData.viewport].popup_assets[n].property.transition],
			onComplete:checkNextAniamtion,
			onCompleteParams:['inner',transition,n]
		});

		if(greetingOptions[appData.viewport].popup_assets[n].property.dim != undefined){
			var objID = appData.viewport+'_'+greetingOptions[appData.viewport].popup_assets[n].id+'Dim';
			var objShadow = $.objects[objID];

			var alphaNum = getDimAlpha(greetingOptions[appData.viewport].popup_assets[n].property.dim, transition);
			TweenMax.to(objShadow, obj.transition[transition].speed, {
				delay:obj.transition[transition].delay,
				x:obj.transition[transition].x,
				y:obj.transition[transition].y,
				scaleX:obj.transition[transition].scaleX,
				scaleY:obj.transition[transition].scaleY,
				skewX:obj.transition[transition].skewX,
				skewY:obj.transition[transition].skewY,
				regX:obj.transition[transition].regX,
				regY:obj.transition[transition].regY,
				rotation:obj.transition[transition].rotation,
				alpha:alphaNum,
				ease:ease,
				overwrite:true
			});
		}

		if(greetingOptions[appData.viewport].popup_assets[n].property.type == 'base'){
			var objID = appData.viewport+'_'+greetingOptions[appData.viewport].popup_assets[n].id+'Shadow';
			var objShadow = $.objects[objID];

			var alphaNum = transition == 'close' ? 0 : greetingOptions.shadow.alpha;
			TweenMax.to(objShadow, obj.transition[transition].speed, {
				delay:obj.transition[transition].delay,
				alpha:alphaNum,
				ease:ease,
				overwrite:true
			});
		}
	}
}

function playPopSound(type){
	if(type == 'fade'){
		playSound('soundPop');
	}
}

function getDimAlpha(dim, transition){
	var alphaNum = dim == 'left' ? greetingOptions.dim.openLeft : greetingOptions.dim.openRight;
	if(transition == 'close'){
		alphaNum = transition == 'left' ? greetingOptions.dim.closeLeft : greetingOptions.dim.closeRight;
	}
	return alphaNum;
}

function checkNextAniamtion(type,transition,index){
	if(index == 0){
		appData.animation = false;

		if(type == 'cover' && transition == 'open'){
			playSound('soundDropping');
			window[appData.viewport+'CardInnerContainer'].visible = true;
			toggleAnimation();

			TweenMax.to(window[appData.viewport+'CardContainer'], 2, {
				overwrite:true,
				onComplete:function(){
					if(greetingOptions.music){
						playSoundLoop('greetingMusic');
					}
				}
			});
		}

		if(type == 'inner' && transition == 'close'){
			playSound('soundDrop');
			window[appData.viewport+'CardInnerContainer'].visible = false;
			toggleCoverAnimation();
			stopSoundLoop('greetingMusic');
		}
	}
}

/*!
 * 
 * START GREETING - This is the function that runs to start greeting
 * 
 */
function startGreetingApp(){
	appData.action = true;
	greetingOptions[appData.viewport].active = true;

	landscapeParticleContainer.visible = false;
	portraitParticleContainer.visible = false;

	resetObjectProperty(appData.viewport);

	appData.coverAnimation = false;
	appData.cardAnimation = false;
	appData.animation = false;

	
	if(appData.active){
		toggleCoverAnimation();
	}else{
		stopSoundLoop('greetingMusic');
	}
}

/*!
 * 
 * OPTIONS - This is the function that runs to mute and fullscreen
 * 
 */
function toggleGameMute(con){
	buttonSoundOff.visible = false;
	buttonSoundOn.visible = false;
	toggleMute(con);
	if(con){
		buttonSoundOn.visible = true;
	}else{
		buttonSoundOff.visible = true;	
	}
}

function toggleFullScreen() {
  if (!document.fullscreenElement &&    // alternative standard method
      !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

/*!
 * 
 * CREATE URL - This is the function that runs to create url
 * 
 */
function createURL(viewport, data){
	if($.objects[viewport+'_'+data.id] == undefined){
		return;
	}
	
	if(!$.objects[viewport+'_'+data.id].isText){
		createHitarea($.objects[viewport+'_'+data.id]);
	}
	
	if(!isNaN(data.hitX) && !isNaN(data.hitY) && !isNaN(data.hitWidth) && !isNaN(data.hitHeight)){
		$.objects[viewport+'_'+data.id].hitArea = new createjs.Shape(new createjs.Graphics().beginFill("#000").drawRect(data.hitX, data.hitY, data.hitWidth, data.hitHeight));	
	}
	
	$.objects[viewport+'_'+data.id].cursor = "pointer";
	$.objects[viewport+'_'+data.id].addEventListener("click", function(evt) {
		gtag('event','click',{'event_category':'link','event_label':data.link});
		window.open(data.link, '_blank');
	});
}

/*!
 * 
 * PARALLAX EFFECTS - This is the function that runs to animate parallax effects
 * 
 */
function parallaxElementX(viewport, id, percent, invert){
	if($.objects[id] == undefined){
		return;   
	}

	var invertStatus = invert == undefined ? false : invert;
	appData[viewport].parallaxX.push({id:id, percent:percent, invert:invertStatus});
}
function parallaxElementY(viewport, id, percent, invert){
	if($.objects[id] == undefined){
		return;   
	}
	var invertStatus = invert == undefined ? false : invert;
	appData[viewport].parallaxY.push({id:id, percent:percent, invert:invertStatus});
}

function parallaxElementRotation(viewport, id, percent, invert){
	if($.objects[id] == undefined){
		return;   
	}
	var invertStatus = invert == undefined ? false : invert;
	appData[viewport].parallaxRotate.push({id:id, percent:percent, invert:invertStatus});
}

function parallaxElementSkewX(viewport, id, percent, invert){
	if($.objects[id] == undefined){
		return;   
	}
	var invertStatus = invert == undefined ? false : invert;

	appData[viewport].parallaxSkewX.push({id:id, percent:percent, invert:invertStatus});
}

function parallaxElementSkewY(viewport, id, percent, invert){
	if($.objects[id] == undefined){
		return;   
	}
	var invertStatus = invert == undefined ? false : invert;
	appData[viewport].parallaxSkewY.push({id:id, percent:percent, invert:invertStatus});
}

/*!
 * 
 * APP LOOP - This is the function that runs to loop app
 * 
 */
function updateApp(){
	if(appData.action){
		if(greetingOptions[appData.viewport].particle_effects.enable){
			for(var n=0; n<greetingOptions[appData.viewport].particle_effects.total; n++){
				var obj = $.particle[appData.viewport+'_'+n];
				if(obj != undefined){
					obj.visible = true;
					obj.y = obj.y + obj.yspeed;
					obj.x = obj.x + obj.xspeed;
					obj.rotation = obj.rotation + obj.xspeed;
			
					obj.yspeed = obj.yspeed * gravityData.drag + greetingOptions[appData.viewport].particle_effects.speed;
					obj.xspeed = obj.xspeed * gravityData.drag;
			
					if (obj.y > greetingOptions[appData.viewport].particle_effects.endY) {
						resetParticles(obj);
					}
				}
			}
		}

		if(!appData.animation && appData.cardAnimation){
			for(var n=0; n<appData[appData.viewport].parallaxX.length; n++){
				var obj = $.objects[appData[appData.viewport].parallaxX[n].id];
				var moveX = appData[appData.viewport].parallaxX[n].invert == false ? appData.moveX : (canvasW - appData.moveX);
				moveX = moveX - window[appData.viewport+'CardContainer'].x;
				var newX = obj.transition.open.x + (((obj.transition.open.x - moveX)/canvasW * appData[appData.viewport].parallaxX[n].percent));
				
				TweenMax.to(obj, .5, {x:newX});
				if(obj.objDim != undefined){
					TweenMax.to(obj.objDim, .5, {x:newX});
				}

				if(obj.objShadow != undefined){
					TweenMax.to(obj.objShadow, .5, {x:newX + greetingOptions.shadow.x});
				}
			}
			
			for(var n=0; n<appData[appData.viewport].parallaxY.length; n++){
				var obj = $.objects[appData[appData.viewport].parallaxY[n].id];
				var moveY = appData[appData.viewport].parallaxY[n].invert == false ? appData.moveY : (canvasH - appData.moveY);
				moveY = moveY - window[appData.viewport+'CardContainer'].y;
				var newY = obj.transition.open.y + (((obj.transition.open.y - moveY)/canvasH * appData[appData.viewport].parallaxY[n].percent));
				
				TweenMax.to(obj, .5, {y:newY});
				if(obj.objDim != undefined){
					TweenMax.to(obj.objDim, .5, {y:newY});
				}

				if(obj.objShadow != undefined){
					TweenMax.to(obj.objShadow, .5, {y:newY + greetingOptions.shadow.y});
				}
			}

			for(var n=0; n<appData[appData.viewport].parallaxSkewX.length; n++){
				var obj = $.objects[appData[appData.viewport].parallaxSkewX[n].id];
				var moveX = appData[appData.viewport].parallaxSkewX[n].invert == false ? appData.moveX : (canvasW - appData.moveX);
				moveX = moveX - window[appData.viewport+'CardContainer'].x;
				var skewX = obj.transition.open.skewX + (((obj.transition.open.skewX - moveX)/canvasW * appData[appData.viewport].parallaxSkewX[n].percent));
				
				TweenMax.to(obj, .5, {skewX:skewX});
				if(obj.objDim != undefined){
					TweenMax.to(obj.objDim, .5, {skewX:skewX});
				}

				if(obj.objShadow != undefined){
					TweenMax.to(obj.objShadow, .5, {skewX:skewX});
				}
			}
			
			for(var n=0; n<appData[appData.viewport].parallaxSkewY.length; n++){
				var obj = $.objects[appData[appData.viewport].parallaxSkewY[n].id];
				var moveY = appData[appData.viewport].parallaxSkewY[n].invert == false ? appData.moveY : (canvasH - appData.moveY);
				moveY = moveY - window[appData.viewport+'CardContainer'].y;
				var skewY = obj.transition.open.skewY + (((obj.transition.open.skewY - moveY)/canvasH * appData[appData.viewport].parallaxSkewY[n].percent));
				
				TweenMax.to(obj, .5, {skewY:skewY});
				if(obj.objDim != undefined){
					TweenMax.to(obj.objDim, .5, {skewY:skewY});
				}

				if(obj.objShadow != undefined){
					TweenMax.to(obj.objShadow, .5, {skewY:skewY});
				}
			}
			
			for(var n=0; n<appData[appData.viewport].parallaxRotate.length; n++){
				var obj = $.objects[appData[appData.viewport].parallaxRotate[n].id];
				var moveX = appData[appData.viewport].parallaxRotate[n].invert == false ? appData.moveX : (canvasW - appData.moveX);

				var rotatePercent = (moveX/canvasW * appData[appData.viewport].parallaxRotate[n].percent);
				var newRotation = (obj.transition.open.rotation - (appData[appData.viewport].parallaxRotate[n].percent/2))+(rotatePercent);
				TweenMax.to(obj, .5, {rotation:newRotation});

				if(obj.objDim != undefined){
					TweenMax.to(obj.objDim, .5, {rotation:newRotation});
				}

				if(obj.objShadow != undefined){
					TweenMax.to(obj.objShadow, .5, {rotation:newRotation});
				}
			}
		}
	}
}


/*!
 * 
 * CREATE PARTICLES - This is the function that runs to create particles
 * 
 */
var gravityData = {animate:false, total:10, gravity:3, drag:.99, range:100};
function toggleParticles(viewport, con){
	this[viewport+'ParticleContainer'].visible = con;
	this[viewport+'ParticleContainer'].removeAllChildren();
	
	if(con){
		for(var n=0; n<greetingOptions[viewport].particle_effects.total; n++){
			var randomAssetNum = Math.floor(Math.random()*greetingOptions[viewport].particle_effects.assets.length);
			randomAssetNum = randomAssetNum < 0 ? 0 : randomAssetNum;
			
			$.particle[viewport+'_'+n] = new createjs.Bitmap(loader.getResult(appData.viewport+'_particle_effects'+randomAssetNum));
			$.particle[viewport+'_'+n].visible = false;

			resetParticles($.particle[viewport+'_'+n]);
			
			this[viewport+'ParticleContainer'].addChild($.particle[viewport+'_'+n]);
		}
	}else{
		for(var n=0; n<greetingOptions[viewport].particle_effects.total; n++){
			$.particle[viewport+'_'+n].visible = false;
		}
	}
}

function resetParticles(obj){
	obj.x = randomIntFromInterval(-(greetingOptions[appData.viewport].particle_effects.rangeX), greetingOptions[appData.viewport].particle_effects.rangeX);
	obj.y = greetingOptions[appData.viewport].particle_effects.startY;

	obj.xspeed = randomIntFromInterval(-10, 10);
	obj.yspeed = randomIntFromInterval(-15, -25);

	if(greetingOptions[appData.viewport].particle_effects.scale){
		var randomScale = (Math.random()*5) *.1;
		randomScale += .5;
		obj.scaleX = obj.scaleY = randomScale;
	}
	
	if(greetingOptions[appData.viewport].particle_effects.alpha){
		var alphaNum = (Math.random()*5) *.1;
		alphaNum += .5;
		obj.alpha = alphaNum;
	}

	obj.rotation = Math.random()*360;

	var randomNumber = Math.random() >= 0.5;
	obj.rotateDirection = randomNumber;
}

/*!
 * 
 * XML - This is the function that runs to load word from xml
 * 
 */
function loadConfigXML(src){
	$.ajax({
       url: src,
       type: "GET",
       dataType: "xml",
       success: function (config) {
			
			greetingOptions.colors.text = $(config).find('options colors text').text();
			greetingOptions.colors.background = $(config).find('options colors background').text();
			greetingOptions.colors.background_image = $(config).find('options colors background_image').text();
			
			greetingOptions.fullscreen = getBooleanValue($(config).find('options fullscreen').text());
			greetingOptions.music = getBooleanValue($(config).find('options music').text());
			greetingOptions.music_file = $(config).find('options music').attr('audio');

			greetingOptions.shadow.scaleX = Number($(config).find('options shadow').attr('scaleX'));
			greetingOptions.shadow.scaleY = Number($(config).find('options shadow').attr('scaleY'));
			greetingOptions.shadow.alpha = Number($(config).find('options shadow').attr('alpha'));
			greetingOptions.shadow.x = Number($(config).find('options shadow').attr('x'));
			greetingOptions.shadow.y = Number($(config).find('options shadow').attr('y'));

			greetingOptions.dim.openLeft = Number($(config).find('options dim').attr('openLeft'));
			greetingOptions.dim.openRight = Number($(config).find('options dim').attr('openRight'));
			greetingOptions.dim.closeLeft = Number($(config).find('options dim').attr('closeLeft'));
			greetingOptions.dim.closeRight = Number($(config).find('options dim').attr('closeRight'));
			
			$('#loaderImg').hide();
			if($(config).find('options loader').text() != ''){
				$('#loaderImg').show();
				$('#loaderImg').attr('src', $(config).find('options loader').text());
			}
			
			var landscapeMode = getBooleanValue($(config).find('landscape').attr('enable'));
			var portraitMode = getBooleanValue($(config).find('portrait').attr('enable'));
			
			if(landscapeMode){
				pushViewportOption(config, 'landscape');
			}
			
			if(portraitMode){
				pushViewportOption(config, 'portrait');
			}
			
			if(!landscapeMode && !portraitMode){
				alert("You need to enable at least one viewport mode to run the greeting app (landscape or portrait).");
			}
			
			if(landscapeMode && portraitMode){
				rotateInstruction = false;	
			}else if(!landscapeMode || !portraitMode){
				rotateInstruction = true;
				if(landscapeMode){
					forPortrait = false;
				}else{
					forPortrait = true;
				}
			}
			
			initPreload();
       }
	});
}

function pushViewportOption(config, viewport){
	appData[viewport].width = Number($(config).find(viewport).attr('width'));
	appData[viewport].height = Number($(config).find(viewport).attr('height'));
	appData[viewport].contentWidth = Number($(config).find(viewport).attr('contentWidth'));
	appData[viewport].contentHeight = Number($(config).find(viewport).attr('contentHeight'));
	appData[viewport].cardX = Number($(config).find(viewport).attr('cardX'));
	appData[viewport].cardY = Number($(config).find(viewport).attr('cardY'));
	
	
	greetingOptions[viewport].cover_container = [];
	$(config).find(viewport+' greeting_cover').each(function(coverIndex, coverElement){
		pushAssetsArray(greetingOptions[viewport].cover_container, coverElement);
	});

	greetingOptions[viewport].cover_assets = [];
	$(config).find(viewport+' greeting_cover item').each(function(coverIndex, coverElement){
		pushAssetsArray(greetingOptions[viewport].cover_assets, coverElement);
	});

	greetingOptions[viewport].popup_container = [];
	$(config).find(viewport+' greeting_popup').each(function(popupIndex, popupElement){
		pushAssetsArray(greetingOptions[viewport].popup_container, popupElement);
	});
	
	greetingOptions[viewport].popup_assets = [];
	$(config).find(viewport+' greeting_popup item').each(function(popupIndex, popupElement){
		pushAssetsArray(greetingOptions[viewport].popup_assets, popupElement);
	});

	greetingOptions[viewport].particle_effects.enable = getBooleanValue($(config).find(viewport+' particle_effects').attr('enable'));
	greetingOptions[viewport].particle_effects.alpha = getBooleanValue($(config).find(viewport+' particle_effects').attr('alpha'));
	greetingOptions[viewport].particle_effects.scale = getBooleanValue($(config).find(viewport+' particle_effects').attr('scale'));
	greetingOptions[viewport].particle_effects.total = Number($(config).find(viewport+' particle_effects').attr('total'));
	greetingOptions[viewport].particle_effects.speed = Number($(config).find(viewport+' particle_effects').attr('speed'));
	greetingOptions[viewport].particle_effects.startY = Number($(config).find(viewport+' particle_effects').attr('startY'));
	greetingOptions[viewport].particle_effects.endY = Number($(config).find(viewport+' particle_effects').attr('endY'));
	greetingOptions[viewport].particle_effects.rangeX = Number($(config).find(viewport+' particle_effects').attr('rangeX'));
	
	greetingOptions[viewport].particle_effects.assets = [];
	$(config).find(viewport+' particle_effects item').each(function(particleIndex, particleElement){
		greetingOptions[viewport].particle_effects.assets.push($(particleElement).text());
	});

	greetingOptions[viewport].parallax_effects = [];
	$(config).find(viewport+' parrallax_effects item').each(function(parallaxIndex, parallaxElement){
		greetingOptions[viewport].parallax_effects.push({movement:$(parallaxElement).attr('movement'), percentage:$(parallaxElement).attr('percentage'), id:$(parallaxElement).attr('id'), reverse:getBooleanValue($(parallaxElement).attr('reverse'))});
	});

	greetingOptions[viewport].links = [];
	$(config).find(viewport+' links item').each(function(linkIndex, linkElement){
		greetingOptions[viewport].links.push({id:$(linkElement).attr('id'), link:$(linkElement).text()});
		
		var currentIndex = greetingOptions[viewport].links.length-1;
		pushAssetsHitarea(linkElement, greetingOptions[viewport].links, currentIndex, 'hitX');
		pushAssetsHitarea(linkElement, greetingOptions[viewport].links, currentIndex, 'hitY');
		pushAssetsHitarea(linkElement, greetingOptions[viewport].links, currentIndex, 'hitWidth');
		pushAssetsHitarea(linkElement, greetingOptions[viewport].links, currentIndex, 'hitHeight');
	});
}

function pushAssetsArray(array, element){
	array.push({src:$(element).text(), property:{}});
	
	var currentIndex = array.length-1;
	pushAssetsProperty(element, array, currentIndex, 'openX');
	pushAssetsProperty(element, array, currentIndex, 'openY');
	pushAssetsProperty(element, array, currentIndex, 'openRegX');
	pushAssetsProperty(element, array, currentIndex, 'openRegY');
	pushAssetsProperty(element, array, currentIndex, 'openSkewX');
	pushAssetsProperty(element, array, currentIndex, 'openSkewY');
	pushAssetsProperty(element, array, currentIndex, 'openScaleX');
	pushAssetsProperty(element, array, currentIndex, 'openScaleY');
	pushAssetsProperty(element, array, currentIndex, 'openRotation');
	pushAssetsProperty(element, array, currentIndex, 'openAlpha');
	pushAssetsProperty(element, array, currentIndex, 'openDelay');
	pushAssetsProperty(element, array, currentIndex, 'openSpeed');

	pushAssetsProperty(element, array, currentIndex, 'closeX');
	pushAssetsProperty(element, array, currentIndex, 'closeY');
	pushAssetsProperty(element, array, currentIndex, 'closeRegX');
	pushAssetsProperty(element, array, currentIndex, 'closeRegY');
	pushAssetsProperty(element, array, currentIndex, 'closeSkewX');
	pushAssetsProperty(element, array, currentIndex, 'closeSkewY');
	pushAssetsProperty(element, array, currentIndex, 'closeScaleX');
	pushAssetsProperty(element, array, currentIndex, 'closeScaleY');
	pushAssetsProperty(element, array, currentIndex, 'closeRotation');
	pushAssetsProperty(element, array, currentIndex, 'closeAlpha');
	pushAssetsProperty(element, array, currentIndex, 'closeDelay');
	pushAssetsProperty(element, array, currentIndex, 'closeSpeed');

	pushAssetsProperty(element, array, currentIndex, 'openInnerX');
	pushAssetsProperty(element, array, currentIndex, 'openInnerY');
	pushAssetsProperty(element, array, currentIndex, 'openInnerRegX');
	pushAssetsProperty(element, array, currentIndex, 'openInnerRegY');
	pushAssetsProperty(element, array, currentIndex, 'openInnerSkewX');
	pushAssetsProperty(element, array, currentIndex, 'openInnerSkewY');
	pushAssetsProperty(element, array, currentIndex, 'openInnerScaleX');
	pushAssetsProperty(element, array, currentIndex, 'openInnerScaleY');
	pushAssetsProperty(element, array, currentIndex, 'openInnerRotation');
	pushAssetsProperty(element, array, currentIndex, 'openInnerAlpha');
	pushAssetsProperty(element, array, currentIndex, 'openInnerDelay');
	pushAssetsProperty(element, array, currentIndex, 'openInnerSpeed');

	pushAssetsProperty(element, array, currentIndex, 'closeInnerX');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerY');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerRegX');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerRegY');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerSkewX');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerSkewY');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerScaleX');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerScaleY');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerRotation');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerAlpha');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerDelay');
	pushAssetsProperty(element, array, currentIndex, 'closeInnerSpeed');

	pushAssetsProperty(element, array, currentIndex, 'font');
	pushAssetsProperty(element, array, currentIndex, 'lineHeight');
	pushAssetsProperty(element, array, currentIndex, 'color');
	pushAssetsProperty(element, array, currentIndex, 'align');
	pushAssetsProperty(element, array, currentIndex, 'transition');
	pushAssetsProperty(element, array, currentIndex, 'dim');
	pushAssetsProperty(element, array, currentIndex, 'type');
	
	pushAssetsProperty(element, array, currentIndex, 'width');
	pushAssetsProperty(element, array, currentIndex, 'height');
	pushAssetsProperty(element, array, currentIndex, 'count');
	pushAssetsProperty(element, array, currentIndex, 'speed');

	if($(element).attr('id') != undefined){
		array[currentIndex].id = $(element).attr('id');
	}
}

function pushAssetsProperty(element, array, index, property){
	if($(element).attr(property) != undefined){
		if(property == 'type' || property == 'transition' || property == 'font' || property == 'lineHeight' || property == 'color' || property == 'align' || property == 'dim'){
			array[index].property[property] = $(element).attr(property);
		}else{
			array[index].property[property] = Number($(element).attr(property));
		}
	}
}

function pushAssetsHitarea(element, array, index, property){
	if($(element).attr(property) != undefined){
		array[index][property] = Number($(element).attr(property));
	}
}