
//共用輪播plugin
$.fn.sliderShow = function(){
    var element = $(this),//輪播最大外框
        bigimgUl = element.find(".pt-bannerList"),
        bigimgLi = element.find(".pt-bannerList li"),
        bigimg = element.find(".pt-bannerList li img"),
        ptPrev = element.find(".pt-prev"),
        ptNext = element.find(".pt-next"),
        imgMax = bigimgLi.length,
        startPos, nowAnimate,
        imgNowR = 0,
        imgNextR = 0,
        imgNow = 0,
        imgNext = null,
        nextClick = 4000,
        timer,
        dragState = false,
        dragStartX = 0,
        dragDate = null,
        dpos = null,//紀錄拖動向左或向右的起始位置
        moveState = false;//判斷是否移動中 

    //初始預設
    bigimgLi.eq(imgNow).addClass('active').siblings().css({"left":'100%'});
    if(imgMax<2){ ptNext.hide(); ptPrev.hide(); }
    // bigimgUl.css({ paddingTop: (((bigimgLi.eq(imgNext).find("img").height()/bigimgLi.eq(imgNext).find("img").width())*100 )|0) +"%"});

    function moveNext(num) {
        clearInterval(timer);
        moveState = false//開始move()後就不可以被mousemove
        imgNext = num;
        startPos = imgNext > imgNow ? 100: -100;
        nowAnimate = imgNext > imgNow ? "-100%": "100%";          
        imgNext = imgNext > imgNow ? imgNext%imgMax : (imgNext+imgMax)%imgMax;  
        if(dragState){startPos = (startPos+dpos/bigimgLi.width()*100);}//重新計算拖動後的起始位置
        bigimgLi.eq(imgNow).removeClass("active").stop().animate({left: nowAnimate},500);
        bigimgLi.eq(imgNext).addClass('active').css({"left": startPos+"%"})
        .stop().animate({left: 0},500, function() {
            imgNow = imgNext;
            imgNext = null;
            timer = setInterval(autoMove, nextClick);
        });
    }   

    //大圖左右按鈕
    ptNext.on("mousedown", function() {
        clearInterval(timer);
        if (imgNext == null && imgMax>1) {
            imgNext = imgNow + 1;
            moveNext(imgNext);
        }
        return false;
    });

    ptPrev.on("mousedown", function() {
        clearInterval(timer);
        if (imgNext == null  && imgMax>1) {
            imgNext = imgNow - 1;
            moveNext(imgNext);
        }
        return false;
    });

    bigimgLi.on("mousedown touchstart", function(e) {
        clearInterval(timer); 
        //防止被drag以致後續動作無法繼續
        if(e.type == "mousedown"){   e.preventDefault();}        
        if (!dragState && imgNext == null && imgMax>1) {
            dragStartX = e.type == "mousedown" ? e.pageX : e.originalEvent.changedTouches[0].pageX;
            dragState = true;
            dragDate = new Date();
        }
    });

    bigimgLi.on("mousemove touchmove", function(e) {
        moveState = true;
        if (dragState && moveState) {
            var x = e.type == "mousemove" ? e.pageX : e.originalEvent.changedTouches[0].pageX;  
            if(new Date() - dragDate<100)return false; 
            dpos =x-dragStartX;
            var dis = dpos/bigimgLi.width()*100;
            $(this).css({ left:dis+"%" });
            if(x > dragStartX){
                bigimgLi.eq( (($(this).index()-1)+imgMax)%imgMax ).css({left:(-100+Math.abs(dpos)/bigimgLi.width()*100)+"%"});
            }else if(x < dragStartX){
                bigimgLi.eq(($(this).index()+1)%imgMax).css({left:(100-Math.abs(dpos)/bigimgLi.width()*100)+"%"});
            } 
        }
    });

    bigimgLi.on("mouseup touchend mouseleave", function(e) {
        if (dragState) {
            var passDate = new Date() - dragDate;
            var endX = (e.type == "mouseup" || e.type == "mouseleave") ? e.pageX : e.originalEvent.changedTouches[0].pageX;
            if (e.type == "mouseleave" || e.type == "touchleave" || passDate > 130) { 
                if(endX > dragStartX){
                    imgNext =imgNow - 1;
                    bigimgLi.eq((imgNow+1)%imgMax).css({left:"100%"});
                }else{
                    imgNext =imgNow + 1;
                    bigimgLi.eq( ((imgNow-1)+imgMax)%imgMax ).css({left:"-100%"});
                }
                moveNext(imgNext);
                $(this).find('a').click(function() {return false});
            } else {$(this).find('a').off();}
        } else {$(this).find('a').off();}
        dragState = false;
    });
    function autoMove() { ptNext.mousedown();}
    timer = setInterval(autoMove, nextClick);
    return element;
}

//共用popup maskbg plugin
$.fn.maskSet = function(){
    var element = $(this),//最大外框
        maskbg = $(this).parent(".th-maskbg"),
        colosBtn = element.find(".pt-closebtn"),
        albumCont = element.find(".albumCont"),
        downloadCont = element.find(".downloadCont"),
        videoCont = element.find(".videoCont"),
        videoIfrome = element.find("iframe"),
        resizeTimer;

    $(window).resize(function(){  
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(doResizething, 50);
    }).resize();

    function doResizething(){
        maskbg.attr("style","").css({minHeight : $(document).height()}); 
        videoIfrome.css({height: videoCont.width()/1.777});
    }

    albumCont.on("mousedown",function(e){ e.stopPropagation(); }).parent().addClass('show');
    downloadCont.on("mousedown",function(e){ e.stopPropagation();}).parent().addClass('show');
    videoCont.on("mousedown",function(e){ e.stopPropagation();}).parent().addClass('show');
    
    function closeMask(event){ 
        maskbg.removeClass('showMask'); 
        albumCont.parent().removeClass('show');
        downloadCont.parent().removeClass('show');
        videoCont.parent().removeClass('show');
    }
    maskbg.on("mousedown",closeMask);
    colosBtn.on("mousedown",closeMask);

    return element;
}

//相簿popup plugin
$.fn.albumPopup = function(){
    var element = $(this),//輪播最大外框
        bigimgUl = element.find(".pt-bannerList"),
        bigimgLi = element.find(".pt-bannerList li"),
        smallimgLi = element.find(".pt-btnList li"),
        ptNext = element.find(".pt-next"),
        ptPrev = element.find(".pt-prev"),
        btnCont = $(".btnCont"),
        imgMax = bigimgLi.length,
        startPos, nowAnimate,
        resizeTimer,
        imgNow = 0,
        imgNext = null,
        nextClick = 5000,
        dragState = false,
        dragStartX = 0,
        dragDate = null,
        dpos = null,//紀錄拖動向左或向右的起始位置
        moveState = false,//判斷是否移動中 
        pageNow = 1, //小圖現在所在頁數
        ainValue = 0;//目前移動的正負值

    //初始預設;
    smallimgLi.eq(imgNow).addClass("on");
    bigimgLi.eq(imgNow).addClass('active').siblings().css({"left":'100%'});
    if(imgMax<2){ ptNext.hide(); ptPrev.hide(); }
    
    $(window).resize(function(){  
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(doResizething, 300);
    }).resize();

    function doResizething(){
        bigimgUl.stop().animate({ paddingTop: ((bigimgLi.eq(imgNow).find("img").height()/bigimgLi.eq(imgNow).find("img").width())*100|0)+"%"},100);            
    }
    
    function moveNext(num) {
        var imgNowR = 0;
        var imgNextR = 0;
        moveState = false//開始move()後就不可以被mousemove
        imgNext = num;
        startPos = imgNext > imgNow ? 100: -100;
        nowAnimate = imgNext > imgNow ? "-100%": "100%";          
        imgNext = imgNext > imgNow ? imgNext%imgMax : (imgNext+imgMax)%imgMax;
        smallimgLi.eq(imgNext).addClass("on").siblings().removeClass("on");
        imgNowR = (bigimgLi.eq(imgNow).find("img").height()/bigimgLi.eq(imgNow).find("img").width())*100|0;
        imgNextR = (bigimgLi.eq(imgNext).find("img").height()/bigimgLi.eq(imgNext).find("img").width())*100|0; 
        if(imgNowR!=imgNextR){
            bigimgUl.stop().animate({ paddingTop: ((bigimgLi.eq(imgNext).find("img").height()/bigimgLi.eq(imgNext).find("img").width())*100|0)+"%"},300);            
        }
        if(dragState){startPos = (startPos+dpos/bigimgLi.width()*100);}//重新計算拖動後的起始位置
        bigimgLi.eq(imgNow).stop().animate({left: nowAnimate});
        bigimgLi.eq(imgNext).addClass('active').css({"left": startPos+"%"})
        .stop().animate({left: 0}, function() {
            imgNow = imgNext;
            imgNext = null;
        }).siblings().removeClass('active');
    }

    //大圖左右按鈕;
    ptNext.on("mousedown", function() {
        if (imgNext == null && imgMax>1) {
            imgNext = imgNow + 1;
            moveNext(imgNext);
        }
        return false;
    });

    ptPrev.on("mousedown", function() {
        if (imgNext == null  && imgMax>1) {
            imgNext = imgNow - 1;
            moveNext(imgNext);
        }
        return false;
    });

    smallimgLi.on("mousedown", function(e) {      
        e.preventDefault(); //擋掉圖片被拖拉而造成無法執行後續的moveNext(_this.index());
        //阻止事件冒泡;
        e.stopPropagation();
        var _this = $(this);
        if (imgNext != null || _this.hasClass("on")) return false;
        moveNext(_this.index());
    });

    bigimgLi.on("mousedown touchstart", function(e) {
        //阻止事件冒泡;
        e.stopPropagation();
        //防止被drag以致後續動作無法繼續;
        if(e.type == "mousedown"){   e.preventDefault();}        
        if (!dragState && imgNext == null && imgMax>1) {
            dragStartX = e.type == "mousedown" ? e.pageX : e.originalEvent.changedTouches[0].pageX;
            dragState = true;
            dragDate = new Date();
        }
    });

    bigimgLi.on("mousemove touchmove", function(e) {
        moveState = true;
        if (dragState && moveState) {
            var x = e.type == "mousemove" ? e.pageX : e.originalEvent.changedTouches[0].pageX;  
            if(new Date() - dragDate<100)return false; 
            dpos =x-dragStartX;
            var dis = dpos/bigimgLi.width()*100;
            $(this).css({ left:dis+"%" });
            if(x > dragStartX){
                bigimgLi.eq( (($(this).index()-1)+imgMax)%imgMax ).css({left:(-100+Math.abs(dpos)/bigimgLi.width()*100)+"%"});
            }else if(x < dragStartX){
                bigimgLi.eq(($(this).index()+1)%imgMax).css({left:(100-Math.abs(dpos)/bigimgLi.width()*100)+"%"});
            } 
        }
    });

    bigimgLi.on("mouseup touchend mouseleave", function(e) {
        if (dragState) {
            var passDate = new Date() - dragDate;
            var endX = (e.type == "mouseup" || e.type == "mouseleave") ? e.pageX : e.originalEvent.changedTouches[0].pageX;
            if (e.type == "mouseleave" || e.type == "touchleave" || passDate > 130) { 
                if(endX > dragStartX){
                    imgNext =imgNow - 1;
                    bigimgLi.eq((imgNow+1)%imgMax).css({left:"100%"});
                }else{
                    imgNext =imgNow + 1;
                    bigimgLi.eq( ((imgNow-1)+imgMax)%imgMax ).css({left:"-100%"});
                }
                moveNext(imgNext);
                $(this).find('a').click(function() {return false});
            } else {$(this).find('a').off();}
        } else {$(this).find('a').off();}
        dragState = false;
    });
    return element;
}
