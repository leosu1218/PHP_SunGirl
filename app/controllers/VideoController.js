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

        kn.common.checkDataID = function (pId){
            var pathArray = [];
            var pageName =[];
            var clean_uri = location.protocol + "//" + location.host + location.pathname;
            var url = configs.api.sungirl + "/video/" + pId + "/client";
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
            $scope.pageSize = 12;
            var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
            var _this = $(this);
            $scope.getDateJson(url, function(data, status, headers, config){
                kn.common.setData(data);
                kn.common.setList(_this.attr("id"),_this.parent().attr("class"));
            });
            $(this).addClass('active').siblings().removeClass('active');
        }).click();
        //viedo 最多觀看
        $("#videoMost").click(function(){
            $scope.pageSize = 12;
            var url = configs.api.sungirl + "/video/client/clickSum/" + $scope.pageNo + '/' +  $scope.pageSize;
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
            $scope.pageSize = $scope.pageSize + 12;
            var _index = $("#dataBtn").find(".active").index();
            var element = "";
            var _this = $(this);
            if(_index==0){
                var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element, "albumSort", _this.attr("id"));
                });
                element = "videoNew";
            }else{
                var url = configs.api.sungirl + "/video/client/clickSum/" + $scope.pageNo + '/' +  $scope.pageSize;
                $scope.getDateJson(url, function(data, status, headers, config){
                    kn.common.setData(data);
                    kn.index.setList(element, "albumSort", _this.attr("id"));
                });
                element = "videoMost";
            }
        });


        $timeout(function() {
        },500);

    });
});