
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
      animateV = 500,
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
    bigimgLi.eq(imgNow).removeClass("active").stop().animate({left: nowAnimate},animateV);
    bigimgLi.eq(imgNext).addClass('active').css({"left": startPos+"%"})
    .stop().animate({left: 0},animateV, function() {
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
      dpos =x-dragStartX;
      $(this).css({ left:dpos/bigimgLi.width()*100+"%" });
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
      if (e.type == "mouseleave" || e.type == "touchleave" || passDate > 150  && endX!=dragStartX) { 
        imgNext = endX > dragStartX ? imgNow-1 : imgNow+1;
        moveNext(imgNext);
        $(this).find('a').click(function() {return false});
        //處理快速左右拖動後,非移動方向的該張定位點回歸
        if(endX > dragStartX){ bigimgLi.eq(imgNow+1).stop().css({left: 100+"%"});}else{ bigimgLi.eq(imgNow-1).stop().css({left: -100+"%"});}                
      } else {
        $(this).find('a').off();
          //處理快速滑動的歸位設定
        if(endX > dragStartX){
          bigimgLi.eq(imgNow).stop().animate({left: 0},animateV);
          bigimgLi.eq(imgNow-1).stop().animate({left: -100+"%"},animateV);
        }else{
          bigimgLi.eq(imgNow).stop().animate({left: 0},animateV);
          bigimgLi.eq(imgNow+1).stop().animate({left: 100+"%"},animateV);
        }                
      }
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
      maskbg = $(this).parents(".th-maskbg"),
      closebg = $(this).parent(".gp-mdCont"),
      colosBtn = element.find(".pt-closebtn"),
      albumCont = element.find(".albumCont"),
      downloadCont = element.find(".downloadCont"),
      videoCont = element.find(".videoCont"),
      videoIfrome = element.find("iframe"),
      resizeTimer,
      clean_uri;

  clean_uri = location.protocol + "//" + location.host + location.pathname;
  $(window).resize(function(){  
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(doResizething, 50);
  }).resize();

  function doResizething(){
    videoIfrome.css({height: videoCont.width()/1.777});
  }

  albumCont.on("mousedown",function(e){ e.stopPropagation(); }).parent().addClass('show');
  downloadCont.on("mousedown",function(e){ e.stopPropagation();}).parent().addClass('show');
  videoCont.on("mousedown",function(e){ e.stopPropagation();}).parent().addClass('show');

  function closeMask(event){ 
    maskbg.removeClass('showMask'); 
    albumCont.parent().remove();
    downloadCont.parent().remove();
    videoCont.parent().remove();
    $('body').removeClass('hiddenY');
    //if(location.search!=""){
    //  if(navigator.userAgent.indexOf("MSIE 9.0")>0){
    //    location.href = clean_uri;
    //  }else{
    //    window.history.replaceState({}, document.title, clean_uri);
    //  }
    //}
  }
  closebg.on("mousedown",closeMask);
  colosBtn.on("mousedown",closeMask);
  return element;
}


//相簿popup plugin
$.fn.albumPopup = function(){
  var element = $(this),//輪播最大外框
      bigimgUl = element.find(".pt-bannerList"),
      bigimgLi = element.find(".pt-bannerList li"),
      bigimg = element.find(".pt-bannerList li img"),
      smallimgLi = element.find(".pt-btnList li"),
      smallimg = element.find(".pt-btnList li img"),
      ptNext = element.find(".pt-next"),
      ptPrev = element.find(".pt-prev"),
      window_W = $(window).width(),
      btnCont = $(".btnCont"),
      imgMax = bigimgLi.length,
      startPos, nowAnimate,
      resizeTimer,
      imgNow = 0,
      imgNext = null,
      nextClick = 5000;
    

  //初始預設;
  smallimgLi.eq(imgNow).addClass("on").siblings().removeClass('on');
  bigimgLi.eq(imgNow).addClass('active').css({left:0}).siblings().removeClass('active').css({"left":'100%'});
  if(imgMax<2){ ptNext.hide(); ptPrev.hide(); }
    
  function doResize(){
    bigimg.each(function(){
      if($(window).width()>980){
        if((+$(this).attr("data-width"))> (+$(this).attr("data-height"))){
          $(this).css({width:860,height:574});     
        }else{
          $(this).css({width:384,height:574}); 
        } 
      }
    }); 
    smallimg.each(function(){
      if($(window).width()>980){
        if((+$(this).attr("data-width"))> (+$(this).attr("data-height"))){
          $(this).css({height:150,width:"auto"});        
        }else{
          $(this).css({height:"auto",width:172}); 
        } 
      }else{
        $(this).css({width:"100%",height:"auto"});
      }
    });
    kn.common.window_W = $(window).width()
  }
  doResize();
  
  function moveNext(num) {
    imgNext = num;
    startPos = imgNext > imgNow ? 100: -100;
    nowAnimate = imgNext > imgNow ? -100: 100;          
    imgNext = imgNext > imgNow ? imgNext%imgMax : (imgNext+imgMax)%imgMax;
    smallimgLi.eq(imgNext).addClass("on").siblings().removeClass("on");
    bigimgLi.eq(imgNow).stop().animate({left: nowAnimate+"%"});
    bigimgLi.eq(imgNext).addClass('active').css({"left": startPos+"%"})
    .stop().animate({left: 0}, function() {
      imgNow = imgNext;
      imgNext = null;
    }).siblings().removeClass('active');
  }

  $(window).resize(function(){  
    if(window_W != $(window).width()){
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(doResize, 300);      
    }
  }).resize();

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

  smallimgLi.on("click", function(e) {
    e.preventDefault(); //擋掉圖片被拖拉而造成無法執行後續的moveNext(_this.index());
    if (imgNext != null || $(this).hasClass("on")) return; 
    moveNext($(this).index());
    $(".th-maskbg").animate({scrollTop:0});
  });
  return element;
}


