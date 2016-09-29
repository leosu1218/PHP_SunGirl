//fb宣告
window.fbAsyncInit = function() {
  FB.init({
    appId      : '493815240815365',//測試用
    // appId      : '778687812272959',//正式用
    xfbml      : true,
    version    : 'v2.7'
  });
};
(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

var kn = kn || {};
kn.common={
  liLength:0,
  apiData:{},
  oneData:{},
  btnId:"",
  oldLIST:"",
  timer:"",
  iconClass:"",
  thCont:"",
  clickfn:"",
  moreStatus:false,
  indexNow:0,
  idNow:0,
  setData:function(data){
    return kn.common.apiData = data;
  },
  //album video download 組list page
  setList:function(elemId,parentClass,moreBtn){
    kn.common.btnId = elemId;
    kn.common.moreId = moreBtn;
    clearTimeout(kn.common.timer); 
    switch(parentClass){
      case "albumSort":
            if(moreBtn !="albumMore"){ kn.common.liLength =0 }
            if(kn.common.apiData.pageNo < kn.common.apiData.totalPage){ $("#albumMore").show();}else{ $("#albumMore").hide(); }
            kn.common.oldLIST = $(".th-album .md-list li");
            kn.common.iconClass = "icon-photo";
            kn.common.thCont = $(".th-album .md-list");
            kn.common.clickfn = kn.album.clickSetting;
            kn.common.timer = setTimeout(kn.common.getList, 100);
            break;
      case "videoSort":
            if(moreBtn !="videoMore"){ kn.common.liLength =0 }
            if(kn.common.apiData.pageNo < kn.common.apiData.totalPage){ $("#videoMore").show();}else{ $("#videoMore").hide(); }
            kn.common.oldLIST = $(".th-video .md-list li");
            kn.common.iconClass = "icon-video";
            kn.common.thCont = $(".th-video .md-list");
            kn.common.clickfn = kn.video.clickSetting;
            kn.common.timer = setTimeout(kn.common.getList, 100);
            break;
      case "downloadSort":
            if(moreBtn !="downloadMore"){ kn.common.liLength =0 }
            if(kn.common.apiData.pageNo < kn.common.apiData.totalPage){ $("#downloadMore").show();}else{ $("#downloadMore").hide(); }
            kn.common.oldLIST = $(".th-download .md-list li");
            kn.common.iconClass = "icon-download";
            kn.common.thCont = $(".th-download .md-list");
            kn.common.clickfn = kn.download.clickSetting;
            kn.common.timer = setTimeout(kn.common.getList, 100);
            break;    
    }
  },
  getList:function(){
    var oldList = kn.common.oldLIST;
    var liDom = "";
    var data = kn.common.apiData;
    if(kn.common.moreId==null){ oldList.hide();}
    for(kn.common.liLength; kn.common.liLength<data.records.length; kn.common.liLength++){
      liDom+= '<li id="'+data.records[kn.common.liLength].id+'" class="pt-cont">'+
            '<figure>'+
                '<img src="upload/photo/'+data.records[kn.common.liLength].banner_name+'" alt="">'+
                '<figcaption>'+
                    '<i class="'+kn.common.iconClass+'"></i>'+data.records[kn.common.liLength].title+
                    '<span class="times"><i>'+data.records[kn.common.liLength].click_sum+'</i>次点击</span>'+
                    '<button>观看</button>'+
                '</figcaption>'+
            '</figure>'+                                           
        '</li>'
    }
    kn.common.thCont.append(liDom);      
    kn.common.hoverSetting();
    kn.common.clickfn();
    kn.common.checkQuery();
    if(kn.common.moreId==null){ oldList.remove(); }   
  },
  //album video download  listpage的hover處理
  hoverSetting:function(){
    $(".md-list figure").hover(function(){
        var _this = $(this);
        if($(window).width()>960){
          _this.find("figcaption").css({top: _this.height()-40+"px"}).stop().animate({top:"40%"},400);
        }       
    },function(){
        var _this = $(this);
        if($(window).width()>960){
          _this.find("figcaption").stop().animate({top: _this.height()-40+"px" },400);
        }
    });  
  },
  shareFB:function(pTitle,pImg,pHref){
    FB.ui({
      method: 'feed',
      link: pHref,
      title:pTitle,
      picture: 'https://dl.dropboxusercontent.com/u/39078909/sungirl/images/'+ pImg //測試用
      // prcture :'正式的domain'+pImg //正式用
    },function (response) { });
  },
  shareTwitter:function(pTitle,pHref){
    window.open('http://twitter.com/?status='.concat(encodeURIComponent(pHref)).concat(' ').concat(encodeURIComponent(pTitle)));
  },
  shareWeibo:function(pTitle,pImg,pHref){
    var p = 'https://dl.dropboxusercontent.com/u/39078909/sungirl/images/'+ pImg; //測試用
    // var p = '正式的domain'+pImg //正式用
    window.open('http://service.weibo.com/share/share.php?url=' + encodeURIComponent(pHref) + '&title=' + encodeURIComponent(pTitle) + '&pic=' + encodeURIComponent(p));
  },
  albumSetpop:function(n,pId){
    //album及首頁的 相簿pop
    var bigImg = $(".th-maskbg .pt-bannerList");
    var smallImg = $(".th-maskbg .pt-btnList");
    var hdText = $(".albumCont header");
    var popImg="";
    var apiData={};
    kn.common.idNow = pId; //for shareBtn 
    kn.common.indexNow = n //for shareBtn
    apiData = location.search==""? kn.common.apiData : kn.common.oneData;
      console.log(n);
      console.log(apiData);
    for(var i=0; i<apiData.records[n].photo.length;i++){
      popImg+='<li><img src="upload/photo/'+apiData.records[n].photo[i].photo_name
      +'" data-height="'+apiData.records[n].photo[i].height
      +'" data-width="'+apiData.records[n].photo[i].width+'"></li>'
    }
    hdText.text(apiData.records[n].title);
    bigImg.append(popImg);
    smallImg.append(popImg);
    $(".md-album-popup").maskSet().albumPopup();
    kn.common.shareFn();   
  },
  videoSetpop:function(n,pId){
    var iframeTag = $(".videoCont .pt-videoCont iframe");
    var hdText = $(".videoCont header");
    var apiData={};
    kn.common.idNow = pId; //for shareBtn 
    kn.common.indexNow = n //for shareBtn
    apiData = location.search==""? kn.common.apiData : kn.common.oneData;
    hdText.text(apiData.records[n].title);
    iframeTag.attr("src",apiData.records[n].video_url);
    $(".md-video-popup").maskSet();  
    kn.common.shareFn();
  },
  shareFn:function(){
    var apiData = kn.common.apiData;
    var n = kn.common.indexNow ;
    var clean_uri = location.protocol + "//" + location.host + location.pathname;
    $(".fbBtn").off('click').on("click",function(e){
      e.preventDefault();
      var _href = clean_uri+"?i="+kn.common.idNow;
      $(this).attr("href",_href);      
      kn.common.shareFB(apiData.records[n].title,apiData.records[n].banner_name,_href);
    });
    $('.twitterBtn').off('click').on("click",function(e){
      e.preventDefault();
      var _href = clean_uri+"?i="+kn.common.idNow;
      $(this).attr("href",_href); 
      kn.common.shareTwitter(apiData.records[n].title,_href);
    });
    $('.weiboBtn').off('click').on("click",function(e){
      e.preventDefault();
      var _href = clean_uri+"?i="+kn.common.idNow;
      $(this).attr("href",_href); 
      kn.common.shareWeibo(apiData.records[n].title,apiData.records[n].banner_name,_href)
    }); 
  },
  //檢查從分享回來的qeruy id是否存在
  checkQuery:function(){
    var iNum = [];
    //var pathArray = [];
    //var pageName =[];
    //var clean_uri = location.protocol + "//" + location.host + location.pathname;
    if(location.search!=""){
      iNum= location.search.split("=");
        kn.common.checkDataID(iNum[1]);//檢查id是否存在 checkDataID()由後端提供
      //if(!!kn.common.oneData.records[0].id){
      //  pathArray = location.pathname.split("/");
      //  pageName = pathArray[(pathArray.length-1)].split(".");
      //  kn.common.buildPop(iNum[1],pageName[0]);
      //}else{
      //  //不存在就導回
      //  location.href = clean_uri;
      //}
    }
  },
  //query id存在時要組燈箱畫面
  buildPop:function(pId,pPathname){
    $('body').addClass('hiddenY');
    $(".th-maskbg").addClass('showMask');
    function mixPop(){
      if(kn.common.oneData.records[0].category=="photo"){
        $(".th-maskbg .gp-mdCont").load("popupPage/popalbum.html",function(){
          kn.common.albumSetpop(0,pId);
        });            
      }else if(kn.common.oneData.records[0].category=="video"){
        $(".th-maskbg .gp-mdCont").load("popupPage/popvideo.html",function(){
          kn.common.videoSetpop(0,pId);
        });               
      }      
    }
    switch(pPathname){
      case "":
            mixPop();
            break;
      case "index":
            mixPop();
            break;
      case "album":
            $(".th-maskbg .gp-mdCont").load("popupPage/popalbum.html",function(){
              kn.common.albumSetpop(0,pId);
            });
            break;
      case "video":
            $(".th-maskbg .gp-mdCont").load("popupPage/popvideo.html",function(){
              kn.common.videoSetpop(0,pId);
            }); 
            break;
      case "download":
            $(".th-maskbg .gp-mdCont").load("popupPage/popdownload.html",function(){
              kn.download.downloadSetpop(0,pId);
            });  
            break;  
    }
  }
}