////////////////////////////////////////////////////////////
// MAIN
////////////////////////////////////////////////////////////

/*!
 * 
 * START BUILD GAME - This is the function that runs build game
 * 
 */
function initMain(){
	buildGameButton();
	
	goPage('main');
}

var windowW=windowH=0;
var modeW=modeH=0;
var scalePercent=0;
var offset = {x:0, y:0, left:0, top:0,};
var holderW=holderH = 0;

/*!
 * 
 * GAME RESIZE - This is the function that runs to resize and centralize the game
 * 
 */
function resizeGameFunc(){
	setTimeout(function() {
		windowW = window.innerWidth;
		windowH = window.innerHeight;
		window.scrollTo(0,1);
		
		detectScreenSize();
		
		var newW = modeW;
		var newH = modeH;
		scalePercent = windowW/modeW;
		
        if(detectScreenSize()){
            gameData.mode = 'portrait';
        }else{
            gameData.mode = 'landscape';	
        }
        
        $('#mainHolder').removeClass('portrait');
        $('#mainHolder').removeClass('landscape');

        $('#mainHolder').addClass(gameData.mode);

        if(fitToScreen){
            newW = windowW;	
            newH = windowH;

            if(maintainAspectRatio){
                if(newW > modeW){
                    scalePercent = newW/modeW;
                    if((modeH*scalePercent)>newH){
                        scalePercent = newH/modeH;
                    }	
                }
                
                if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                    if(gameData.mode == 'landscape'){
                        scalePercent = scalePercent * .8;
                    }
                }
                newW = ((modeW)*scalePercent);
                newH = ((modeH)*scalePercent);
            }
        }else{
            scalePercent = scalePercent > 1 ? 1 : scalePercent;
            newW = 	modeW > windowW ? windowW : modeW;
            newH = 	modeH > windowH ? windowH : modeH;

            if(maintainAspectRatio){
                if(newW > modeW){
                    scalePercent = newW/modeW;
                    if((modeH*scalePercent)>newH){
                        scalePercent = newH/modeH;
                    }	
                }
                
                newW = ((modeW)*scalePercent);
                newH = ((modeH)*scalePercent);
            }
        }

        $('#mainHolder').css('width', newW);
        $('#mainHolder').css('height', newH);

        $('#mainHolder').css('left', (windowW/2)-(newW/2));
        $('#mainHolder').css('top', (windowH/2)-(newH/2));
		
		holderW = newW;
		holderH = newH;
		
		resizeGameDetail();
	}, 300);
}

/*!
 * 
 * DETECT SCREEN SIZE - This is the function that runs to detect screen size
 * 
 */
function detectScreenSize(){
    if($.browser.mobile || isTablet){
        if (window.matchMedia("(orientation: landscape)").matches) {
			modeW = stageW;
			modeH = stageH;
			return false;
		}else{
			modeW = portraitW;
			modeH = portraitH;
			return true;
		}
    }else{
        if(windowW > windowH){
            modeW = stageW;
            modeH = stageH;
            return false;
        }else {
            modeW = portraitW;
            modeH = portraitH;
            return true;
        }
    }
}