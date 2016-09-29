/*global define*/
'use strict';

define(['angular', 'app' , 'configs'], function (angular, app , configs) {

    return app.controller("HomeController", function ($scope,$timeout ,$http) {
        $scope.pageNo = 1;
        $scope.pageSize = 4;

        kn.common.checkDataID = function(pId){
            var pathArray = [];
            var pageName =[];
            var clean_uri = location.protocol + "//" + location.host + location.pathname;
            var url = configs.api.sungirl + "/all/" + pId + "/client";
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.oneData = data;
                if(kn.common.oneData.records.length !=0){
                    pathArray = location.pathname.split("/");
                    pageName = pathArray[(pathArray.length-1)].split(".");
                    kn.common.buildPop(pId,pageName[0]);
                }else{
                    //不存在就導回
                    location.href = clean_uri;
                }
            });
        };

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
                kn.common.btnId = elemId;//通知共用albumSetpop
                data = kn.common.apiData;

                if(moreBtn != "indexMore"){ kn.index.liLength = 0;oldList.hide(); }
                if(data.pageNo < data.totalPage){ $("#indexMore").show();}else{ $("#indexMore").hide(); }
                for(kn.index.liLength; kn.index.liLength < data.records.length; kn.index.liLength++){
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
                            '<span class="times">' + data.records[kn.index.liLength].click_sum + '</i>次点击</span>'+
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
                            '<span class="times"><i>'+ data.records[kn.index.liLength].click_sum +'</i>次点击</span>'+
                            '</figcaption>'+
                            '</figure>'+
                            '</div>'+
                            '</div>'
                            break;
                    }
                }
                $(".th-asideInfo").append(liDom);
                kn.index.clickSetting();
                kn.common.checkQuery();
                if(moreBtn != "indexMore"){oldList.remove(); }
            },
            clickSetting:function(){
                $(".album-cont figure").on('click',function(){
                    var _this = $(this).parents(".md-album");
                    var _id = $(this).attr("id");
                    $('body').addClass('hiddenY');
                    $(".th-maskbg").addClass('showMask');
                    $(".th-maskbg .gp-mdCont").load("popupPage/popalbum.html",function(){
                        kn.common.albumSetpop(_this.index(),_id);
                    });
                });

                $(".video-cont figure").on('click',function(){
                    var _this =  $(this).parents(".md-video");
                    var _id = $(this).attr("id");
                    $('body').addClass('hiddenY');
                    $(".th-maskbg").addClass('showMask');
                    $(".th-maskbg .gp-mdCont").load("popupPage/popvideo.html",function(){
                        kn.common.videoSetpop(_this.index(),_id);
                    });
                });
            }
        };

        $scope.getDateJson = function(url , callback){
            var req = {
                method: 'GET',
                url: url,
                headers: configs.api.headers
            };
            $http(req).success(function(data, status, headers, config) {
                callback(data, status, headers, config);
            }).error(function(data, status, headers, config) {
                alert("找不到資料");
            });

        };
        var url = configs.api.sungirl + "/all/client/" + $scope.pageNo + '/' +  $scope.pageSize;
        $scope.getDateJson(url, function(data, status, headers, config){
            kn.common.setData(data);
            kn.index.setList("indexNew");
        });




        //index 最新更新
        $("#indexNew").click(function(){
            var _this = $(this);
            $scope.pageSize = 4;
            var url = configs.api.sungirl + "/all/client/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.index.setList(_this.attr("id"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        });
        //index 最多觀看
        $("#indexMost").click(function(){
            var _this =$(this);
            $scope.pageSize = 4;
            var url = configs.api.sungirl + "/all/client/clickSum/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.index.setList(_this.attr("id"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        });

        //index 更多
        $("#indexMore").click(function(e){
            e.preventDefault();
            var _index = $("#dataBtn").find(".active").index();
            var element = "";
            var _this = $(this);
            $scope.pageSize =$scope.pageSize + 4 ;
            if(_index==0){
                element = "indexNew";
                var url = configs.api.sungirl + "/all/client/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element,_this.attr("id"));
                });

            }else{
                element = "indexMost";
                var url = configs.api.sungirl + "/all/client/clickSum/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element,_this.attr("id"));
                });

            }

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
            //首頁右側hover效果
            $(".md-activity figure").hover(function(e){
                $(this).find(".pt-hover").css({
                    top:e.pageY - (~~$(this).offset().top),
                    left:e.pageX - (~~$(this).offset().left)
                }).stop().animate({ width: "100%",height: "100%",top:0,left:0},300);
            },function(e){
                $(this).find(".pt-hover").stop().animate({
                    top:e.pageY - (~~$(this).offset().top),
                    left:e.pageX - (~~$(this).offset().left),
                    width: 0,height: 0
                },300);
            });

        },500);
    });
});