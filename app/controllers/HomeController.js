/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("HomeController", function ($scope) {

        $scope.purpose = [
            {img: 'p-1.png', text: '參與新興市場國家未來的發展與轉變。'},
            {img: 'p-2.png', text: '針對新興市場國家的需求，協助探討更佳發展策略。'},
            {img: 'p-3.png', text: '努力展現台灣面對新興市場國家發展中的整理策略。'},
            {img: 'p-4.png', text: '籌謀與區域和全球化發展連結的策略。'},
            {img: 'p-5.png', text: '協助台灣中小企業建立新興市場國家產官學的網路，發展各種商機。'},
        ];

        $scope.service = [
            {img: 'service-icon-1.png', title: '國際', text: '針對新興市場國家的需求，協助探討更佳發展策略。針對新興市場國家的需求，協助探討更佳發展策略。'},
            {img: 'service-icon-2.png', title: '商情分析', text: '針對新興市場國家的需求，協助探討更佳發展策略。針對新興市場國家的需求，協助探討更佳發展策略。', href: 'analysis'},
            {img: 'service-icon-3.png', title: '策略諮詢', text: '針對新興市場國家的需求，協助探討更佳發展策略。針對新興市場國家的需求，協助探討更佳發展策略。'}
        ];

    });
});