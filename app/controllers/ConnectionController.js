/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("ConnectionController", function ($scope) {
    	
    	$scope.items = [
    		{img:'icon-2.png', detail:'聯絡電話：02-8237-7213'},
    		{img:'icon-3.png', detail:'聯絡電郵：aemarkets.org@gmail.com'},
    		{img:'icon-4.png', detail:'facebook：www.facebook.com/aemarkets'},
    	]

    });
});