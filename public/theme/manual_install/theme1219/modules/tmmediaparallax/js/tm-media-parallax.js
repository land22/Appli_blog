/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery)

/* jquery.simplr.smoothscroll version 1.0 copyright (c) 2012 https://github.com/simov/simplr-smoothscroll licensed under MIT */

jQuery(document).ready(function($) {
    if(!device.mobile() && !device.tablet()){
        $.srSmoothscroll({
            step:150,
            speed:800
        });
    }
});

;(function(e){"use strict";e.srSmoothscroll=function(t){var platform = window.navigator.platform; if ((platform === 'MacIntel' || platform === 'MacPPC')){return false}; var n=e.extend({step:170,speed:1000,ease:"swing"},t||{});var r=e(window),i=e(document),s=0,o=n.step,u=n.speed,a=r.height(),f=navigator.userAgent.indexOf("AppleWebKit")!==-1?e("body"):e("html"),l=false;e("body").mousewheel(function(e,t){l=true;if(t<0)s=s+a>=i.height()?s:s+=o;else s=s<=0?0:s-=o;f.stop().animate({scrollTop:s},u,n.ease,function(){l=false});return false});r.on("resize",function(e){a=r.height()}).on("scroll",function(e){if(!l)s=r.scrollTop()})}})(jQuery);

(function($){
    var methods = {
        init : function( options ) {

            var settings = {
                bufferRatio: 2.25
            ,   invert: true
            }

            return this.each(function(){
                if ( options ){
                    $.extend(settings, options);
                } 
                
                var 
                    $this = $(this)
                ,   windowSelector = $(window)
                ,   documentSelector = $(document)
                ,   thisHeight = 0
                ,   innerHolderObj
                ,   mediaHolderObj
                ,   infoObj
                ,   imageObj
                ,   innerHolderHeight
                ,   thisOffsetTop
                ,   heightBuffer = 0
                ,   check_mp4
                ,   check_webm
                ,   check_ogv
                ,   check_poster
                ,   check_none_format
                ,   check_all_format
                ,   statusDevice = "desktop"
                ,   typeMedia = "video_html"
                ,   msie8 = Boolean(navigator.userAgent.match(/MSIE ([8]+)\./))
                ,   checkBrowser
                ,   bufferRatio = parseFloat(settings.bufferRatio)
                ;
                
                _constructor();
                function _constructor(){
                    
                    innerHolderObj = $('.parallax_inner', $this);
                    mediaHolderObj = $('.parallax_media', $this);

                    typeMedia = $this.data("type-media");

                    checkBrowser = checkBrowser();
                    
                    check_mp4 = $this.data("mp4");
                    check_webm = $this.data("webm");
                    check_ogv = $this.data("ogv");
                    check_poster = $this.data("poster");

                    (!check_mp4 && !check_webm && !check_ogv)? check_none_format = false : check_none_format = true;
                    (check_mp4 && check_webm && check_ogv)? check_all_format = true : check_all_format = false;

                    if (device.mobile() || device.tablet() || msie8 || checkBrowser == 'Safari') {
                        statusDevice = "mobile";
                        if(typeMedia == "video_html"){
                           posterUrl = mediaHolderObj.attr('poster'); 
                       }else{
                            posterUrl = mediaHolderObj.attr('src');
                       }
                        
                        innerHolderObj.css({"background-image": "url("+posterUrl+")" });   
                        $this.addClass('mobileState');  
                       mediaHolderObj.remove();
                    }

                    if (typeMedia == "video_html"){
                        sourcesCheckInfo();
                    }

                    addEventsFunction();                    
                }
                
                function addEventsFunction(){
                    //------------------ window scroll event -------------//
                    windowSelector.on('scroll',
                        function(){
                            if(statusDevice=="desktop"){
                                mainScrollFunction();
                            }
                        }
                    ).trigger('scroll');
                    //------------------ window resize event -------------//
                    windowSelector.on("resize",
                        function(){
                            $this.width(windowSelector.width());
							if(statusDevice=="mobile"){
                           		$this.css({'width' : windowSelector.width(), 'margin-left' : Math.floor(windowSelector.width()*-0.5), 'left' : '50%'});
							}else
							{
								$this.css({'width' : windowSelector.width() - 220, 'margin-left' : Math.floor((windowSelector.width()-220)*-0.5), 'left' : '50%'});
							}

                            if(statusDevice=="desktop"){
                                mainResizeFunction();
                            }
                        }
                    ).trigger('resize');
                }
                //------------------ window scroll function -------------//
                function mainScrollFunction(){
                    parallaxEffect();
                }
                //------------------ window resize function -------------//
                function mainResizeFunction(){                    
                    parallaxEffect();
                    if (typeMedia == "video_html"){
                        videoResize(mediaHolderObj, innerHolderObj);
                    }else{
                        objectResize(mediaHolderObj, innerHolderObj, "fill");
                    }
                }
                
                function parallaxEffect(){
                    var 
                        documentScrollTop
                    ,   startScrollTop
                    ,   endScrollTop
                    ,   visibleScrollValue
                    ;

                    thisHeight = $this.outerHeight();

                    windowHeight = windowSelector.height();
                    thisOffsetTop = $this.offset().top;
                    documentScrollTop = documentSelector.scrollTop();
                    innerHolderHeight = thisHeight*bufferRatio;
                    heightBuffer = innerHolderHeight-thisHeight;
                    startScrollTop = documentScrollTop + windowHeight;
                    endScrollTop = documentScrollTop - thisHeight;
                    visibleScrollValue = startScrollTop - endScrollTop;

                    _height = thisHeight*bufferRatio;
                    innerHolderObj.css({"height": _height});

                    if( ( startScrollTop > thisOffsetTop ) && ( endScrollTop < thisOffsetTop ) ){
                        pixelScrolled = documentScrollTop - (thisOffsetTop - windowHeight);
                        percentScrolled = pixelScrolled / visibleScrollValue;
                        thisHidenScrollVal = thisOffsetTop - documentScrollTop;
                        deltaTopScrollVal = heightBuffer * percentScrolled;

                        if(settings.invert){
                            _x = - heightBuffer + (deltaTopScrollVal);
                            innerHolderObj.css({"top": _x});
                        }else{
                            _x = - deltaTopScrollVal;
                            innerHolderObj.css({"top": _x});
                        }
                    }
                }
                //-------------------------------- objectResize --------------------------------------//
                //objectResize($('> img', primaryImageHolder), mainImageHolder, "fill");
                function objectResize(obj, container, type){
                    var 
                        prevImgWidth = 0
                    ,   prevImgHeight = 0
                    ,   imageRatio
                    ,   newImgWidth
                    ,   newImgHeight
                    ,   newImgTop
                    ,   newImgLeft
                    ,   alignIMG = 'center'
                    ;
       
                    prevImgWidth = parseInt(obj.data('base-width'));
                    prevImgHeight = parseInt(obj.data('base-height'));

                    imageRatio = prevImgHeight/prevImgWidth;
                    containerRatio = container.height()/container.width();

                    switch(type){
                        case 'fill':
                            if(containerRatio > imageRatio){
                                newImgHeight = container.height();
                                newImgWidth = Math.round( (newImgHeight*prevImgWidth) / prevImgHeight );
                            }else{
                                newImgWidth = container.width();
                                newImgHeight = Math.round( (newImgWidth*prevImgHeight) / prevImgWidth );
                            }

                            obj.css({width: newImgWidth, height: newImgHeight});

                            screenWidth = container.width();
                            screenHeight = container.height();
                            imgWidth = obj.width();
                            imgHeight = obj.height();

                            switch(alignIMG){
                                case "top":
                                    newImgLeft=-(imgWidth-screenWidth)*.5;
                                    newImgTop=0;
                                break;
                                case "bottom":
                                    newImgLeft=-(imgWidth-screenWidth)*.5;
                                    newImgTop=-(imgHeight-screenHeight);
                                break;
                                case "right":
                                    newImgLeft=-(imgWidth-screenWidth);
                                    newImgTop=-(imgHeight-screenHeight)*.5;
                                break;
                                case "left":
                                    newImgLeft=0;
                                    newImgTop=-(imgHeight-screenHeight)*.5;
                                break;
                                case "top_left":
                                    newImgLeft=0;
                                    newImgTop=0;
                                break;
                                case "top_right":
                                    newImgLeft=-(imgWidth-screenWidth);
                                    newImgTop=0;
                                break;
                                case "bottom_right":
                                    newImgLeft=-(imgWidth-screenWidth);
                                    newImgTop=-(imgHeight-screenHeight);
                                break;
                                case "bottom_left":
                                    newImgLeft=0;
                                    newImgTop=-(imgHeight-screenHeight);
                                break;
                                default:
                                    newImgLeft=-(imgWidth-screenWidth)*.5;
                                    newImgTop= -(imgHeight-screenHeight)*.5;
                                }
                        break
                        case 'fit':
                            if(containerRatio > imageRatio){
                                newImgWidth = container.width();
                                newImgHeight = (prevImgHeight*container.width())/prevImgWidth;
                                newImgTop = container.height()/2 - newImgHeight/2;
                                newImgLeft = 0; 
                            }else{
                                newImgWidth = (prevImgWidth*container.height())/prevImgHeight;
                                newImgHeight = container.height();
                                newImgTop = 0;
                                newImgLeft = container.width()/2 - newImgWidth/2;  
                            }
                            obj.css({width: newImgWidth, height: newImgHeight});
                        break
                    }

                    obj.css({top: newImgTop, left: newImgLeft});
                }
                function videoResize(obj, container){
                    var 
                        prevImgWidth = 0
                    ,   prevImgHeight = 0
                    ,   imageRatio
                    ,   newImgWidth
                    ,   newImgHeight
                    ;
       
                    prevImgWidth = parseInt(obj.data('base-width'));
                    prevImgHeight = parseInt(obj.data('base-height'));

                    imageRatio = prevImgHeight/prevImgWidth;
                    containerRatio = container.height()/container.width();
                    

                    if(containerRatio > imageRatio){
                        newImgWidth = "auto";
                        newImgHeight = container.height();
                    }else{
                        newImgWidth = container.width();
                        newImgHeight = "auto";
                    }

                    
                    obj.css({width: newImgWidth, height: newImgHeight});

                }
                /*----------------------- sourcesCheckInfo --------------------------------------------------------*/
                function sourcesCheckInfo(){
                    var 
                        infostring = ""
                    ,   formatCounter = 0
                    ,   posterUrl
                    ;

                    $this.append("<div class='info_alert'><span></span></div>");
                    infoObj = $('.info_alert', $this);


                    if(!check_all_format){
                        infostring += "Not loaded the necessary content!<br>Please, make sure format(s) ";
                        if(!check_mp4){
                            infostring +="<b>MP4</b>, "
                            formatCounter++;
                        }
                        if(!check_webm){
                            infostring +="<b>WEBM</b>, "
                            formatCounter++;
                        }
                        if(!check_ogv){
                            infostring +="<b>OGV</b>"
                            formatCounter++;
                        }
                        if(formatCounter == 1){
                            infostring += " is loaded or name is specified correctly!<br>";
                        }else{
                            infostring += " are loaded or name is specified correctly!<br>";
                        }
                        
                    }
                    if(!check_poster){
                        infostring +="Please make sure <b>poster file</b> is loaded or name is specified correctly!"
                    }
                    if(infostring!=""){
                        $("span", infoObj).html(infostring);
                    }else{
                        infoObj.remove();
                    }
                }

                function checkBrowser(){
                    var ua = navigator.userAgent;
                    
                    if (ua.search(/MSIE/) > 0) return 'Internet Explorer';
                    if (ua.search(/Firefox/) > 0) return 'Firefox';
                    if (ua.search(/Opera/) > 0) return 'Opera';
                    if (ua.search(/Chrome/) > 0) return 'Google Chrome';
                    if (ua.search(/Safari/) > 0) return 'Safari';
                    if (ua.search(/Konqueror/) > 0) return 'Konqueror';
                    if (ua.search(/Iceweasel/) > 0) return 'Debian Iceweasel';
                    if (ua.search(/SeaMonkey/) > 0) return 'SeaMonkey';
                    if (ua.search(/Gecko/) > 0) return 'Gecko';

                    return 'Search Bot';
                }
            });
        },
        destroy    : function( ) { },
        reposition : function( ) { },
        update     : function( content ) { }
    };

    $.fn.tmMediaParallax = function( method ){ 
        
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method with name ' +  method + ' is not exist for jQuery.tmMediaParallax' );
        }
         
        
    }//end plugin
})(jQuery)