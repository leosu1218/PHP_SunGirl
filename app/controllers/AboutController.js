/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("AboutController", function ($scope) {

       $scope.purposes = [
    		{img: 'service-1.png', text: '參與新興市場國家未來之發展與轉變。'},
    		{img: 'service-2.png', text: '籌謀與區域和全球化發展連結之策略。'},
    		{img: 'service-3.png', text: '針對新興市場國家的需求，探討更佳發展策略。'},
    		{img: 'service-4.png', text: '協助台灣中小企業建立與新興市場國家產官學之網絡，開拓各種商機。'},
    		{img: 'service-5.png', text: '努力展現台灣面對新興市場國家發展中之總體策略。'},
       ]

    });
});