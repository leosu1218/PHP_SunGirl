/*global define*/
'use strict';

define(['angular', 'app' , 'configs'], function (angular, app , configs) {

    return app.controller("VideoController", function ($scope,$timeout, $http) {
        $scope.pageNo = 1;
        $scope.pageSize = 12;

        kn.video = {
            //video.html的相簿list click
            clickSetting:function(){
                $(".pt-cont").on('click',function(){
                    var _this = $(this);
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
            // var url = configs.api.sungirl + "/all/client/" + $scope.pageNo + '/' +  $scope.pageSize;
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

        //viedo 最新更新
        $("#videoNew").click(function(){
            var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.pageNo = 1;
            var _this = $(this);
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.common.setList(_this.attr("id"),_this.parent().attr("class"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        }).click();
        //viedo 最多觀看
        $("#videoMost").click(function(){
            var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
            $scope.pageNo = 1;
            var _this = $(this);
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.common.setList(_this.attr("id"),_this.parent().attr("class"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        });
        //viedo 更多
        $("#videoMore").click(function(e){
            e.preventDefault();
            var _index = $("#dataBtn").find(".active").index();
            var element = "";
            var _this = $(this);
            if(_index==0){
                $scope.pageNo++;
                var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element, "albumSort", _this.attr("id"));
                });
                element = "videoNew";
            }else{
                var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
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