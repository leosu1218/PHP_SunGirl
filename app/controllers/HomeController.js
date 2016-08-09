/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("HomeController", function ($scope,$timeout) {

        $timeout(function() {
            //輪播
            $(".th-sliderShow").sliderShow();

            //album,video,download開啟popup
            $(".pt-cont").on("click",function(){
                $(".md-album-popup").maskSet().albumPopup();
                $(".md-video-popup").maskSet();
                $(".md-download-popup").maskSet();
                $(".th-maskbg").addClass('showMask');
                $("body, html").animate({scrollTop:0});
            });


            //首頁開啟popup
            $(".main-aside .album-cont figure").on("click",function(){
                $(".md-album-popup").maskSet();
                $(".md-album-popup").albumPopup();
                $(".th-maskbg").addClass('showMask');
                $("body, html").animate({scrollTop:0});
            });
            $(".main-aside .md-video figure").on("click",function(){
                $(".md-video-popup").maskSet();
                $(".th-maskbg").addClass('showMask');
                $("body, html").animate({scrollTop:0});
            });

            //首頁 右側浮動
            $(window).scroll(function(){
                var scrollH = $(this).scrollTop();
                var finalScrollH = ($(document).outerHeight(true)-$(window).innerHeight())-scrollH;
                if($(".index").length>=1){
                    if(scrollH > $("#footer").offset().top - $(".md-activity").outerHeight(true) - $(".fixedHdBg").height() ){
                        //大於footer;
                        $(".md-activity").attr("style","").css({
                            position:"fixed",
                            bottom: $("#footer").height() + 20 - finalScrollH +"px",
                        });
                    }else if(scrollH > ($(".fullbar").offset().top + $(".fullbar").height())){
                        //大於header;
                        $(".md-activity").attr("style","").css({
                            position:"fixed",
                            top: "0",
                            marginTop:"70px"
                        });
                    } else{
                        //小於header;
                        $(".md-activity").attr("style","");
                    }
                }

            }).scroll();
        },500);


    });
});