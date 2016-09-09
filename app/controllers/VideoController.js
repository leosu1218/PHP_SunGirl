/*global define*/
'use strict';

define(['angular', 'app' , 'configs'], function (angular, app , configs) {

    return app.controller("VideoController", function ($scope,$timeout, $http) {
        $scope.pageNo = 1;
        $scope.pageSize = 12;

        var url = configs.api.sungirl + "/video/client/" + $scope.pageNo + '/' +  $scope.pageSize;
        var req = {
            method: 'GET',
            url: url,
            headers: configs.api.headers
        };
        $http(req).success(function(data, status, headers, config) {
            console.log(data);
        }).error(function(data, status, headers, config) {
            alert("找不到資料");
        });

        $timeout(function() {
            //輪播
            //桌布下載
            var mon={
                dwdata:"",
                dwlength:0,
                chooseMonth:function(pIndex){
                    var indexNum = pIndex;
                    var data;
                    var dwCont_header = $(".downloadCont header");
                    var dwCont_img = $(".downloadCont figure img");
                    var month_img = $(".othermonth li");
                    var pc_alink = $(".pt-pc a");
                    var phone_alink = $(".pt-phone a");
                    var numString = "00";
                    mon.dwlength =Object.keys(mon.dwdata).length;
                    numString = numString + (indexNum+1).toString();
                    try{
                        data =  mon.dwdata[numString][0];
                        dwCont_header.text(data.title);
                        dwCont_img.attr("src",month_img.eq(indexNum).find('img').attr("src"));
                        for(var i=0; i<pc_alink.length; i++){
                            pc_alink.eq(i).attr('href', data.computer[i]);
                            phone_alink.eq(i).attr('href', data.mobile[i]);
                        }
                    }catch(err){}
                }
            }

            //get download json
            $.getJSON('json/download.json',function(data){
                mon.dwdata = data;
                return mon.dwdata;
            });

            //popup list click to change image and href
            $(".othermonth li").on('click',function(){
                mon.chooseMonth($(this).index());
                $("html body").animate({scrollTop:0});
            });

            //album,video,download開啟popup
            $(".pt-cont").on("click",function(){
                $(".md-album-popup").maskSet().albumPopup();
                $(".md-video-popup").maskSet();
                if($(".md-download-popup").length>=1){
                    mon.chooseMonth($(this).index());
                    if($(this).index()< mon.dwlength){
                        $(".md-download-popup").maskSet();
                    }else{return}
                }
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
                            bottom: $("#footer").height() + 20 - finalScrollH +"px"
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

        //相簿,影音,下載hover
        $(".md-list figure").hover(function(){
            if($(window).width()>960){$(this).find("figcaption").stop().animate({top:"40%"},400);}
        },function(){
            if($(window).width()>960){$(this).find("figcaption").stop().animate({top: $(this).height()-40+"px" },400);}
        });
    });
});