/*global define*/
'use strict';

// Hook up all modules to the 'app' module
// Returns TheApp (see commonRoutes.js for usage)
define(['angular', 'ngCookiesHelper'], function (angular, ngCookiesHelper) {
    
    angular.module('HashBangURLs', ['ngRoute'])
        .config([
            '$locationProvider', 
            function($location) {
                $location.hashPrefix('!');
	        }
        ]);
	
    angular.module('HTML5ModeURLs', ['ngRoute'])
        .config([
            '$locationProvider', 
            function($location) {
                $location.html5Mode(true);
        }]);
    
    // Choose to inject either HashBangs or HTML5 mode, your preference.
	var app = angular.module('app', 
        ['HashBangURLs', 'ui.bootstrap', 'ngSanitize', 'ngAnimate', 'ngFileUpload', 'ui.tree', 'ngCookies'],
        function ($routeProvider, $locationProvider, $httpProvider) {
	});

    // Inject services
    app.service("$cookiesHelper", ngCookiesHelper);

    app.commonPath      = "/common";
    app.applicationPath = "/admin";

    app.controller("AppController", function ($scope, $log, $q, $timeout, $http, $interval) {

    });

	return app;

});