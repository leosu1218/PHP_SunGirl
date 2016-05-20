/*global define*/
'use strict';

// Hook up all modules to the 'app' module
// Returns TheApp (see commonRoutes.js for usage)
define([
    'angular',
    'ngCookiesHelper',

    // Collections
    'FullProportionCollection',
    'GenderDailyCollection',
    'FullHourlyCollection',
    'GenderHourlyCollection',
    'FeatureTranslateRule'

], function (
    angular,
    ngCookiesHelper,

    //Collections
    FullProportionCollection,
    GenderDailyCollection,
    FullHourlyCollection,
    GenderHourlyCollection,
    FeatureTranslateRule
) {
    
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
        ['HashBangURLs', 'ui.bootstrap', 'ngSanitize', 'ngAnimate', 'ngFileUpload', 'ui.tree', 'tc.chartjs', 'ngCookies'],
        function ($routeProvider, $locationProvider, $httpProvider) {
	});

    // Inject services
    app.service("$cookiesHelper", ngCookiesHelper);
    app.service("$FullProportionCollection", FullProportionCollection);
    app.service("$GenderDailyCollection", GenderDailyCollection);
    app.service("$FullHourlyCollection", FullHourlyCollection);
    app.service("$GenderHourlyCollection", GenderHourlyCollection);
    app.service("$FeatureTranslateRule", FeatureTranslateRule);

	app.controller("AppController", function ($scope, $log, $q, $timeout, $http, $interval) {

    });

	return app;

});