/*global define*/
'use strict';

define(['angular', 'app' , 'configs'], function (angular, app , configs) {

    return app.controller("DownloadController", function ($scope,$timeout , $http) {
        $scope.pageNo = 1;
        $scope.pageSize = 12;

        kn.download = {
            shareImg:"",
            shareTitle:"",
            //download.html的相簿list click
            clickSetting:function(){
                $(".pt-cont").on('click',function(){
                    var _this = $(this);
                    var _id = $(this).attr("id");
                    $('body').addClass('hiddenY');
                    $(".th-maskbg").addClass('showMask');
                    $(".th-maskbg .gp-mdCont").load("popupPage/popdownload.html",function(){
                        kn.download.downloadSetpop(_this.index(),_id);
                    });
                });
            },
            downloadSetpop:function(n,pId){
                var hdText = $(".downloadCont header"),
                    bigImg = $("#dwnload-bigImg"),
                    pc1 = $("#pc-img1"),
                    pc2 = $("#pc-img2"),
                    pc3 = $("#pc-img3"),
                    pc4 = $("#pc-img4"),
                    mobile1 = $("#mobile-img1"),
                    mobile2 = $("#mobile-img2"),
                    mobile3 = $("#mobile-img3"),
                    mobile4 = $("#mobile-img4"),
                    liDom="",
                    popdata,
                    imgsrc = "";
                kn.common.idNow = pId; //for shareBtn
                popdata = location.search==""? kn.common.apiData : kn.common.oneData;
                imgsrc = "upload/photo/"+popdata.records[n].banner_name;
                kn.download.shareImg = popdata.records[n].banner_name;
                kn.download.shareTitle = popdata.records[n].title;
                hdText.text(popdata.records[n].title);
                bigImg.attr("src",imgsrc);
                pc1.attr("href", "upload/download/"+popdata.records[n].pc_img1);
                pc2.attr("href", "upload/download/"+popdata.records[n].pc_img2);
                pc3.attr("href", "upload/download/"+popdata.records[n].pc_img3);
                pc4.attr("href", "upload/download/"+popdata.records[n].pc_img4);
                mobile1.attr("href", "upload/download/"+popdata.records[n].mobile_img1);
                mobile2.attr("href", "upload/download/"+popdata.records[n].mobile_img2);
                mobile3.attr("href", "upload/download/"+popdata.records[n].mobile_img3);
                mobile4.attr("href", "upload/download/"+popdata.records[n].mobile_img4);
                $(".md-download-popup").maskSet();
                kn.download.shareFn();
            },
            shareFn:function(){
                var clean_uri = location.protocol + "//" + location.host + location.pathname;
                $(".fbBtn").off('click').on("click",function(e){
                    e.preventDefault();
                    var _href = clean_uri+"?i="+kn.common.idNow;
                    $(this).attr("href",_href);
                    kn.common.shareFB(kn.download.shareTitle,kn.download.shareImg,_href);
                });
                $('.twitterBtn').off('click').on("click",function(e){
                    e.preventDefault();
                    var _href = clean_uri+"?i="+kn.common.idNow;
                    $(this).attr("href",_href);
                    kn.common.shareTwitter(kn.download.shareTitle,_href);
                });
                $('.weiboBtn').off('click').on("click",function(e){
                    e.preventDefault();
                    var _href = clean_uri+"?i="+kn.common.idNow;
                    $(this).attr("href",_href);
                    kn.common.shareWeibo(kn.download.shareTitle,kn.download.shareImg,_href);
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

        //download 最新更新
        $("#downloadNew").click(function(){
            var url = configs.api.sungirl + "/client/download/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.pageNo = 1;
            var _this = $(this);
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.common.setList(_this.attr("id"),_this.parent().attr("class"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        }).click();
        //download 最多觀看
        $("#downloadMost").click(function(){
            var url = configs.api.sungirl + "/client/download/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.pageNo = 1;
            var _this = $(this);
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.common.setList(_this.attr("id"),_this.parent().attr("class"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        });
        //download 更多
        $("#downloadMore").click(function(e){
            e.preventDefault();
            var _index = $("#dataBtn").find(".active").index();
            var element = "";
            var _this = $(this);
            if(_index==0){
                $scope.pageNo++;
                var url = configs.api.sungirl + "/client/download/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element, "albumSort", _this.attr("id"));
                });
                element = "videoNew";
            }else{
                var url = configs.api.sungirl + "/client/download/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element, "albumSort", _this.attr("id"));
                });
                element = "videoMost";
            }
        });


        $timeout(function() {
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