/*global require*/
'use strict';

require.config({
	paths: {
		text: '../common/libs/require-text',
		ngCarousel: 'libs/angular-carousel.min',
		jquery: 	'../common/libs/jquery-2.1.1',
		smoothscroll: 'libs/smoothscroll',
		bootstrap : '../common/libs/bootstrap.min',
		angular : 	'../common/libs/angular-1.3.0.min',
		ngAnimate : '../common/libs/angular-animate-1.3.0.min',
		ngCookies : '../common/libs/angular-cookies-1.3.0.min',
		ngResource : '../common/libs/angular-resource-1.3.0.min',
		ngRoute : 	'../common/libs/angular-route-1.3.0.min',
		ngSanitize : '../common/libs/angular-sanitize-1.3.0.min',
		ngTouch : 	'../common/libs/angular-touch-1.3.0.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.13.0',

		app: 'App',
		common: '../common',
		views: 'views',
		controllers: 'controllers',
		boot: 'Boot'
	},
	shim: {
		smoothscroll: { exports: 'smoothscroll' },
		jquery: { exports: '$'},
		bootstrap: { exports: 'bootstrap', deps: ['jquery'] },
		angular: { exports: 'angular', },
		ngAnimate: { exports: 'ngAnimate', deps: ['angular'] },
		ngCookies: { exports: 'ngCookies', deps: ['angular'] },
		ngResource: { exports: 'ngResource', deps: ['angular'] },
		ngRoute: { exports: 'ngRoute', deps: ['angular'] },
		ngSanitize: { exports: 'ngSanitize', deps: ['angular'] },
		ngTouch: { exports: 'ngTouch', deps: ['angular'] },
        ngBootstrap: { exports: 'ngBootstrap', deps: ['angular'] },
        ngCarousel: { exports: 'ngCarousel', deps: ['angular', 'ngTouch'] },
	}
});

require(['boot']);