/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery 		: '../common/libs/jquery-2.1.4.min',
		bootstrap 	: '../common/libs/bootstrap.min',
        angular 	: '../common/libs/angular.min',
        ngAnimate 	: '../common/libs/angular-animate.min',
        ngCookies 	: '../common/libs/angular-cookies.min',
        ngCookiesHelper : '../common/libs/angular-cookies-helper-1.1',
        ngResource 	: '../common/libs/angular-resource.min',
        ngRoute 	: '../common/libs/angular-route.min',
        ngSanitize 	: '../common/libs/angular-sanitize.min',
        ngTouch 	: '../common/libs/angular-touch.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.11.2',
		message		: '../common/libs/common-messages',
		configs		: 'libs/configs',
		
		metisMenu	: '../common/libs/metisMenu/dist/metisMenu-min',		

		raphael		: '../adLogin/libs/components/raphael/raphael-min',
		morris 		: '../adLogin/libs/components/morrisjs/morris.min',
		morrisData 	: '../adLogin/libs/componentsData/morris-data',
		
		Class 		: '../common/libs/ooad-class-1.0',		
		
		app 		: 'App',
		common 		: '../common',
		views 		: 'views',
		controllers	: 'controllers',
	},
	shim: {
		jquery 		: { exports: '$'},
		bootstrap 	: { exports: 'bootstrap', 	deps: ['jquery'] },
		angular 	: { exports: 'angular', },
		ngAnimate 	: { exports: 'ngAnimate', 	deps: ['angular'] },
		ngCookies 	: { exports: 'ngCookies', 	deps: ['angular'] },
		ngResource 	: { exports: 'ngResource', 	deps: ['angular'] },
		ngRoute 	: { exports: 'ngRoute', 	deps: ['angular'] },
		ngSanitize 	: { exports: 'ngSanitize', 	deps: ['angular'] },
		ngTouch 	: { exports: 'ngTouch', 	deps: ['angular'] },
        ngBootstrap : { exports: 'ngBootstrap', deps: ['angular'] },

        // Sb ui modules.
        metisMenu   : { exports: 'metisMenu', 	deps: ['jquery'] },        

        // Extends angularJs tools.
        Class: { exports: 'Class' },        
	}
});

require(
	[
	// Dependencies from lib
		'angular', 
		'ngRoute',
		'ngBootstrap',
		'ngSanitize',
		'ngAnimate',
		'bootstrap',
	// Angular directives/controllers/services
		'app',
		'Router',
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);