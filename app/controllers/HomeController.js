/*global define*/
'use strict';

define(['angular', 'app' , 'configs'], function (angular, app , configs) {

    return app.controller("HomeController", function ($scope,$timeout ,$http) {
        $scope.pageNo = 1;
        $scope.pageSize = 12;
        kn.index = {
            apiData:"",
            oldLIST:"",
            liLength:0,
            setList:function(elemId,moreBtn){
                var oldList = $(".th-asideInfo >div");
                var data;
                var liDom="";
                var dateTime ="";
                var array=[];
                kn.index.apiData = elemId =="indexNew" ? kn.API.indexNew : kn.API.indexMost;
                if(moreBtn != "indexMore"){
                    kn.index.liLength = 0;
                    oldList.hide();
                }
                data = kn.index.apiData;
                if(data.pageNo < data.totalPage){ $("#indexMore").show();}else{ $("#indexMore").hide(); }
                for(kn.index.liLength; kn.index.liLength<data.records.length; kn.index.liLength++){
                    dateTime = data.records[kn.index.liLength].ready_time;
                    array = dateTime.substr(0,10).split("-");
                    switch(data.records[kn.index.liLength].category){
                        case "photo":
                            liDom+= '<div class="md-album">'+
                            '<div class="album-cont">'+
                            '<time>'+
                            '<span>'+array[0]+'</span>年'+
                            '<span>'+parseInt(array[1],10)+'</span>月'+
                            '<span>'+parseInt(array[2],10)+'</span>日'+
                            '</time>'+
                            '<figure id="'+data.records[kn.index.liLength].id+'">'+
                            '<img src="upload/photo/'+data.records[kn.index.liLength].banner_name+'" alt="">'+
                            '<figcaption>'+
                            '<i class="icon-video"></i>'+data.records[kn.index.liLength].title+
                            '<span class="times"><i>1,2342</i>次点击</span>'+
                            '</figcaption>'+
                            '</figure>'+
                            '</div>'+
                            '</div>'
                            break;
                        case "video":
                            liDom+= '<div class="md-video">'+
                            '<div class="video-cont">'+
                            '<time>'+
                            '<span>'+array[0]+'</span>年'+
                            '<span>'+parseInt(array[1],10)+'</span>月'+
                            '<span>'+parseInt(array[2],10)+'</span>日'+
                            '</time>'+
                            '<figure id="'+data.records[kn.index.liLength].id+'">'+
                            '<img src="upload/photo/'+data.records[kn.index.liLength].banner_name+'" alt="">'+
                            '<figcaption>'+
                            '<i class="icon-video"></i>'+data.records[kn.index.liLength].title+
                            '<span class="times"><i>1,2342</i>次点击</span>'+
                            '</figcaption>'+
                            '</figure>'+
                            '</div>'+
                            '</div>'
                            break;
                    }
                }
                $(".th-asideInfo").append(liDom);
                kn.index.clickSetting();
                if(moreBtn != "indexMore"){oldList.remove(); }
            },
            clickSetting:function(){
                var hashID = location.hash.substr(1);
                $(".album-cont figure").on('click',function(){
                    var _this = $(this).parents(".md-album").index();
                    var _parent = $(this).parents('.th-asideInfo').attr('class');
                    $('body').addClass('hiddenY');
                    $(".th-maskbg").addClass('showMask');
                    $(".th-maskbg .gp-mdCont").load("popupPage/popalbum.html",function(){
                        kn.common.albumSetpop(_this,_parent);
                    });
                });

                $(".video-cont figure").on('click',function(){
                    var _this =  $(this).parents(".md-video").index();
                    var _parent = $(this).parents('.th-asideInfo').attr('class');
                    $('body').addClass('hiddenY');
                    $(".th-maskbg").addClass('showMask');
                    $(".th-maskbg .gp-mdCont").load("popupPage/popvideo.html",function(){
                        kn.common.videoSetpop(_this,_parent);
                    });
                });
                if(hashID !=""){$("#"+hashID).click();}
            },
            setNewestData:function(data){
                return kn.API.indexNew = data;
            },
            setMostData:function(data){
                return kn.API.indexMost = data;
            }
        };

        var url = configs.api.sungirl + "/all/client/" + $scope.pageNo + '/' +  $scope.pageSize;
        var req = {
            method: 'GET',
            url: url,
            headers: configs.api.headers
        };
        $http(req).success(function(data, status, headers, config) {
            $scope.sungirlData = data;
            kn.index.setNewestData($scope.sungirlData);
            kn.index.setList("indexNew");
        }).error(function(data, status, headers, config) {
            alert("找不到資料");
        });


        //index 最新更新
        $("#indexNew").click(function(){
            kn.index.setNewestData(newlist);
            kn.index.setList($(this).attr("id"));
            $(this).addClass('active').siblings().removeClass('active');
        });
        //index 最多觀看
        $("#indexMost").click(function(){
            kn.index.setMostData(mostlist);
            kn.index.setList($(this).attr("id"));
            $(this).addClass('active').siblings().removeClass('active');
        });

        //index 更多
        $("#indexMore").click(function(e){
            e.preventDefault();
            var _index = $("#dataBtn").find(".active").index();
            if(_index==0){ kn.index.setNewestData(newlist); }else{ kn.index.setMostData(mostlist); }
            kn.index.setList("indexNew",$(this).attr("id"));
        });

        $timeout(function() {
            //首頁右側浮動
            $(window).scroll(function(){
                var scrollH = $(this).scrollTop();
                var finalScrollH = ($(document).outerHeight(true)-$(window).innerHeight())-scrollH;
                if($(".index").length>=1){
                    if(scrollH > $("#footer").offset().top - $(".md-activity").outerHeight(true) - $(".fixedHdBg").height() ){
                        //大於footer;
                        $(".md-activity").attr("style","").css({ position:"fixed",bottom: $("#footer").height() + 20 - finalScrollH +"px" });
                    }else if(scrollH > ($(".fullbar").offset().top + $(".fullbar").height())){
                        //大於header;
                        $(".md-activity").attr("style","").css({position:"fixed", top: "0", marginTop:"70px"});
                    } else{
                        //小於header;
                        $(".md-activity").attr("style","");
                    }
                }
            }).scroll();

            //燈箱開啟時 按browser back時關掉燈箱
            $(window).on("hashchange", function() {
                var hashID = location.hash.substr(1);
                function hashchangeCloseBg(){
                    $(".th-maskbg").removeClass('showMask');
                    $(".albumCont").parent().remove();
                    $(".downloadCont").parent().remove();
                    $(".videoCont").parent().remove();
                    $('body').removeClass('hiddenY');
                    if(navigator.userAgent.indexOf("MSIE 9.0")>0){
                        location.hash="";
                    }else{
                        window.history.pushState(null, null, location.href.replace(location.hash,''));
                    }
                }
                if(hashID==""){
                    hashchangeCloseBg();
                }else{
                    if( $("#"+location.hash.substr(1)).length<1){
                        hashchangeCloseBg();
                    }else{
                        $("#"+location.hash.substr(1)).click();
                    }
                }
            });

        },500);
    });
});