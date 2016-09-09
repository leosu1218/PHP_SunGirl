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
                });


                $timeout(function() {
                    $("li").on("click", function () {
                        $("li").removeClass("on");
                        $(this).addClass("on");
                    });
                    var aa = location.pathname.substring(1);
                    if(aa == ""){
                        $('.li-home').addClass("on");
                    }else{
                        $('.li-'+aa).addClass("on");
                    }


                    //輪播
                    $(".th-sliderShow").sliderShow();

                    //共用  右上角m版icon開關
                    $(".icon-share").on("click",function(){
                        $(".th-share ul").slideToggle(300);
                        return false;
                    });

                    //共用  變動輪播箭頭的lineheight in firefox
                    var oBrowser;
                    function DetectBrowser(){
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
                    oBrowser = new DetectBrowser();
                    if(oBrowser.isFF) {
                        $(".icon-nextBtn").css({"line-height":"56px"});
                    }
                },500);
            }
        };
    });
});