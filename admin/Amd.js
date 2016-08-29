/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery 		: '../common/libs/jquery-2.1.4.min',
        jqueryui 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui.min',
        timepicker 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui-timepicker-addon',
        sliderAccess 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui-sliderAccess',
		text 		: '../common/libs/require-text',
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
        ngChart     : '../common/libs/tc-angular-chartjs',

        Chart2      : '../common/libs/chart/dist/Chart.bundle',
        EventHero   : '../common/libs/eventhero',
        Chart       : '../common/libs/Chart',
        fastclick   : '../common/libs/fastclick',
        foundation  : '../common/libs/foundation.min',
        modernizr   : '../common/libs/modernizr',
        prism       : '../common/libs/prism',
		message		: '../common/libs/common-messages',
		configs		: 'libs/configs',
		datetime	: '../common/libs/datetime-helper',
		metisMenu	: '../common/libs/metisMenu/dist/metisMenu-min',
		raphael		: '../admin/libs/components/raphael/raphael-min',
		morris 		: '../admin/libs/components/morrisjs/morris.min',
		morrisData 	: '../admin/libs/componentsData/morris-data',
		ngFileUpload : '../admin/libs/ng-file-upload',
		ngFileUploadShim : '../admin/libs/ng-file-upload-shim',
		ngTree : '../admin/libs/angular-ui-tree',

        createController 	: '../admin/controllers/CreateController',
		
		Class 		: '../common/libs/ooad-class-1.0',
		ControllerCreator : '../common/libs/controller-creator-1.0',
		SbControllerCreator: 'libs/SbControllerCreator',


		app 		: 'App',
		common 		: '../common',
		views 		: 'views',
		controllers	: 'controllers',
	},
	shim: {
        jquery 		: { exports: '$'},
        jqueryui 		: { exports: 'jqueryui' ,  deps: ['jquery'] },
        EventHero 	: { exports: 'EventHero'},
		bootstrap 	: { exports: 'bootstrap', 	deps: ['jquery'] },
		angular 	: { exports: 'angular' },
		ngAnimate 	: { exports: 'ngAnimate', 	deps: ['angular'] },
		ngCookies 	: { exports: 'ngCookies', 	deps: ['angular'] },
        ngCookiesHelper: { exports: 'ngCookiesHelper', 	deps: ['ngCookies'] },
		ngResource 	: { exports: 'ngResource', 	deps: ['angular'] },
		ngRoute 	: { exports: 'ngRoute', 	deps: ['angular'] },
		ngSanitize 	: { exports: 'ngSanitize', 	deps: ['angular'] },
		ngTouch 	: { exports: 'ngTouch', 	deps: ['angular'] },
        ngBootstrap : { exports: 'ngBootstrap', deps: ['angular'] },
        ngFileUpload: { exports: 'ngFileUpload', deps: ['angular'] },
        ngFileUploadShim: { exports: 'ngFileUploadShim', deps: ['angular'] },
        ngTree 		: { exports: 'ngTree', deps: ['angular'] },
        ngChart     : { exports: 'ngChart', deps: ['angular', 'Chart', 'fastclick', 'foundation', 'modernizr', 'prism'] },
        foundation  : { exports: 'foundation', deps: ['jquery'] },
        Chart2      : { exports: 'Chart2' },

        // Sb ui modules.
        metisMenu   : { exports: 'metisMenu', 	deps: ['jquery'] },

        //timepicker
        timepicker 		: { exports: 'timepicker' ,  deps: ['jqueryui'] },
        sliderAccess 		: { exports: 'sliderAccess' ,  deps: ['jqueryui'] },

        // Extends angularJs tools.
        Class: { exports: 'Class' },
        ControllerCreator: { exports: 'ControllerCreator', 	deps: ['Class'] },
        SbControllerCreator: { exports: 'SbControllerCreator', 	deps: ['ControllerCreator'] }
	}
});

require(
	[
	    // Dependencies lib
		'angular', 
		'ngRoute',
		'ngBootstrap',
		'ngSanitize',
		'ngAnimate',
        'ngCookies',
        'ngCookiesHelper',
		'bootstrap',
		'ngFileUpload',
		'ngFileUploadShim',	
		'ngTree',
        'ngChart',
		'app',
		'Router',

        'jquery',
        'jqueryui',
        'timepicker',
        //'sliderAccess',

		// Common directive.
        'directives/SbAlert/controller',
        'directives/SbModal/controller',
        'directives/SbSmartTable/controller',
        'directives/SbPagination/controller',
        'directives/SbUpload/controller',
        'directives/Compile/controller',
        'directives/SbTree/controller',
        'directives/AdminImageList/controller',
        'directives/UrlInput/controller',
        'directives/DatetimePicker/controller',

        // App directive.
        'directives/SbHeader/controller',
        'directives/SbFooter/controller',

        // App other directive.
        'directives/UserRegisterForm/controller',
        'directives/UserChangePasswordForm/controller',
        'directives/ChangePassword/controller',



	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);