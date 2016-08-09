$(function(){
    var readyAct = {
        shareTimer:"",
        resizeRest:function(){
            //右上角icon share reset
            var displayVaule = $(this).innerWidth()>=980 ? "block" : "none";
            $(".th-share ul").css({display:displayVaule}); 
            //移除相簿,影音,下載listy94在快速hover後又拉伸瀏覽器後的style top的殘留
            $(".md-list figure figcaption").attr("style","");
            //infopage maincontetnt Height
            var contentH =  $(".fixedHdBg").height() + $("#footer").height() + $(".page-info .md-textlink ul").height() + $("nav").height();
            if($(window).innerHeight()> contentH){
                $(".page-info .main-content").css({
                    minHeight: $(document).height()- $(".fixedHdBg").height() - $("#footer").height() - $("nav").height()
                });
            }else{
                //infopage maincontetnt Height  100是.md-textlink ul margin-top:100px
                $(".page-info .main-content").css({ minHeight:  $(".page-info .md-textlink ul").outerHeight(true)});
            }
        },
        detectBrowser:function(){
            var sAgent = navigator.userAgent.toLowerCase();
            this.isIE = (sAgent.indexOf("msie")!=-1); //IE6.0-7
            this.isFF = (sAgent.indexOf("firefox")!=-1);//firefox
            this.isSa = (sAgent.indexOf("safari")!=-1);//safari
            this.isOp = (sAgent.indexOf("opera")!=-1);//opera
            this.isNN = (sAgent.indexOf("netscape")!=-1);//netscape
            this.isCh = (sAgent.indexOf("chrome")!=-1);//chrome
            this.isMa = this.isIE;//marthon
            this.isOther = (!this.isIE && !this.isFF && !this.isSa && !this.isOp && !this.isNN && !this.isSa);//unknown Browser
        }
    }
    //變動輪播箭頭的lineheight in firefox
    var oBrowser = new readyAct.detectBrowser();
    if(oBrowser.isFF) { 
        $(".icon-nextBtn").css({"line-height":"56px"})
    } 



    //拉伸browser後將style回歸預設
    $(window).resize(function(){  
        clearTimeout(readyAct.shareTimer);
        readyAct.shareTimer = setTimeout(readyAct.resizeRest, 50);
    }).resize();

    //相簿,影音,下載hover
    $(".md-list figure").hover(function(){
        if($(window).width()>960){$(this).find("figcaption").stop().animate({top:"40%"},400);}       
    },function(){
        if($(window).width()>960){$(this).find("figcaption").stop().animate({top: $(this).height()-40+"px" },400);}
    });
});