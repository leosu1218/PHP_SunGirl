/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/AppHeader/view.html','configs'], function (angular, app, view, configs) {

    app.directive("appHeader", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
            },
            controller:  function($scope,$timeout,$http) {

                //取得輪播圖片
                var homePageApi = configs.api.website + "banner/image/1/999";
                var homePageRequest = {
                    method: 'GET',
                    url: homePageApi,
                    headers: configs.api.headers
                };

                $http(homePageRequest).success(function(data, status, headers, config) {
                    $scope.homepagepPath = configs.path.upload + "carousel/";
                    $scope.homepages = data.records;

                }).error(function(data, status, headers, config){
                    $scope.alert.show("無法取得圖片，請再次嘗試。");
                });


                $timeout(function() {
                    //右上角m版icon開關
                    $("li").on("click", function () {
                        $("li").removeClass("on");
                        $(this).addClass("on");
                    });

                    //輪播
                    $(".th-sliderShow").sliderShow();

                    $(".icon-share").on("click", function () {
                        $(".th-share ul").slideToggle(300);
                        return false;
                    });
                },500);
            }
        };
    });
});