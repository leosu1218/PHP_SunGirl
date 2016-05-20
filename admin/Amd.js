/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery 		: '../common/libs/jquery-2.1.4.min',
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
		
		Class 		: '../common/libs/ooad-class-1.0',
		ControllerCreator : '../common/libs/controller-creator-1.0',
		SbControllerCreator: 'libs/SbControllerCreator',

        FullProportionCollection : 'collections/FullProportionCollection',
        GenderDailyCollection: 'collections/GenderDailyCollection',
        FullHourlyCollection: 'collections/FullHourlyCollection',
        GenderHourlyCollection: 'collections/GenderHourlyCollection',
        FeatureTranslateRule: 'collections/FeatureTranslateRule',


		app 		: 'App',
		common 		: '../common',
		views 		: 'views',
		controllers	: 'controllers',
	},
	shim: {
        jquery 		: { exports: '$'},
        EventHero 	: { exports: 'EventHero'},
		bootstrap 	: { exports: 'bootstrap', 	deps: ['jquery'] },
		angular 	: { exports: 'angular', },
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

        // Collections.
        'FullProportionCollection',
        'GenderDailyCollection',
        'FullHourlyCollection',
        'GenderHourlyCollection',
        'FeatureTranslateRule',

		// Common directive.
        'directives/SbAlert/controller',
        'directives/SbModal/controller',
        'directives/SbSmartTable/controller',
        'directives/SbPagination/controller',
        'directives/SbUpload/controller',
        'directives/Compile/controller',
        'directives/SbTree/controller',
        'directives/SbBarChart2/controller',
        'directives/SbLineChart2/controller',
        'directives/SbPieChart2/controller',
        'directives/HoursTable/controller',

        // App directive.
        'directives/SbHeader/controller',
        'directives/SbFooter/controller',

        // App selector directive.
        'directives/IntervalSelectorTools/controller',
        'directives/BetweenDateSelector/controller',
        'directives/DeviceSelector/controller',
        'directives/MonthlySelectorTools/controller',
        'directives/OnlyMonthSelector/controller',
        'directives/CompareSelectorTools/controller',

        // App chart directive.
        'directives/FullHourlyChart/controller',
        'directives/FullProportionChart/controller',
        'directives/GenderDailyChart/controller',
        'directives/GenderHourlyChart/controller',

        'directives/MonthlySelectorTools/controller',
        'directives/OnlyMonthSelector/controller',
        'directives/GenderMonthlyChart/controller',

        'directives/SbBarChart2/controller',
        'directives/SbLineChart2/controller',
        'directives/SbPieChart2/controller',
        'directives/SbCalendar/controller',

        // App other directive.
        'directives/UserRegisterForm/controller',
        'directives/UserChangePasswordForm/controller',
        'directives/ChangePassword/controller',
        'directives/FeatureTranslationForm/controller'
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);