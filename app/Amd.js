/*global require*/
'use strict';

require.config({
	paths: {
		text: '../common/libs/require-text',
		ngCarousel: 'libs/angular-carousel.min',
		jquery: 	'../common/libs/jquery-2.1.1',
		smoothscroll: 'libs/smoothscroll',
		//bootstrap : '../common/libs/bootstrap.min',
		configs		: '../common/libs/configs',
		angular : 	'../common/libs/angular-1.3.0.min',
		ngAnimate : '../common/libs/angular-animate-1.3.0.min',
		ngCookies : '../common/libs/angular-cookies-1.3.0.min',
        ngCookiesHelper : '../common/libs/angular-cookies-helper-1.1',
		ngResource : '../common/libs/angular-resource-1.3.0.min',
		ngRoute : 	'../common/libs/angular-route-1.3.0.min',
		ngSanitize : '../common/libs/angular-sanitize-1.3.0.min',
		ngTouch : 	'../common/libs/angular-touch-1.3.0.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.13.0',
        sunGirl : '../app/libs/sungirl',
        sunGirlPlugin : '../app/libs/sungirl_plugin',

		app: 'App',
		common: '../common',
		views: 'views',
		controllers: 'controllers'
	},
	shim: {
		smoothscroll: { exports: 'smoothscroll' },
		jquery: { exports: '$'},
        sunGirl: { exports: 'sunGirl', deps: ['jquery']},
        sunGirlPlugin: { exports: 'sunGirlPlugin', deps: ['sunGirl']},
		//bootstrap: { exports: 'bootstrap', deps: ['jquery'] },
		angular: { exports: 'angular', },
		ngAnimate: { exports: 'ngAnimate', deps: ['angular'] },
		ngCookies: { exports: 'ngCookies', deps: ['angular'] },
        ngCookiesHelper: { exports: 'ngCookiesHelper', 	deps: ['ngCookies'] },
		ngResource: { exports: 'ngResource', deps: ['angular'] },
		ngRoute: { exports: 'ngRoute', deps: ['angular'] },
		ngSanitize: { exports: 'ngSanitize', deps: ['angular'] },
		ngTouch: { exports: 'ngTouch', deps: ['angular'] },
        ngBootstrap: { exports: 'ngBootstrap', deps: ['angular'] },
        ngCarousel: { exports: 'ngCarousel', deps: ['angular', 'ngTouch'] },

	}
});

require(
    [
        // Dependencies libs
        'angular',
        'ngRoute',
        'ngBootstrap',
        'ngSanitize',
        'ngAnimate',
        'ngCarousel',
        'ngCookies',
        'ngCookiesHelper',
        //'bootstrap',
        'smoothscroll',
        'sunGirl',
        'sunGirlPlugin',

        // Directives
        'directives/AppHeader/controller',
        'directives/AppFooter/controller',

        // Angular controllers/services
        'app',
        'Router'
    ],
    function (angular) {
        var AppRoot = angular.element(document.getElementById('ng-app'));
        AppRoot.attr('ng-controller','AppController');
        angular.bootstrap(document, ['app']);
    }
);