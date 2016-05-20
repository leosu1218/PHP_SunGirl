/*global define*/
'use strict';

// set up base routes
define(['angular', 'app', 'configs',
	'controllers/UserListController',
    'controllers/ImportIMediaController',
    'controllers/FeatureTranslationController',
    'controllers/IntervalModeController',
    'controllers/MonthlyReportController',
    'controllers/CompareModeController',

    'controllers/BarChart2Controller',
    'controllers/LineChart2Controller',
    'controllers/PieChart2Controller',
    'controllers/FeatureTranslateRuleController',

], function (angular, app, configs) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return configs.path.appRoot + '/views' + path;
		}

		$routeProvider
            .when('/',
                {
                    templateUrl: currentPath('/ImportIMedia.html'),
                    controller: 'ImportIMediaController'
                })
            .when('/user/list',
                {
                    templateUrl: currentPath('/UserList.html'),
                    controller: 'UserListController'
                })
            .when('/reports/media-event/import',
                {
                    templateUrl: currentPath('/ImportIMedia.html'),
                    controller: 'ImportIMediaController'
                })
            .when('/reports/media-event/feature-translate',
                {
                    templateUrl: currentPath('/FeatureTranslation.html'),
                    controller: 'FeatureTranslationController'
                })
            .when( '/reports/media-event/interval-mode',
                {
                    templateUrl: currentPath('/IntervalMode.html'),
                    controller: 'IntervalModeController'
                })
            .when('/reports/media-event/monthly-report',
                {
                    templateUrl: currentPath('/MonthlyReport.html'),
                    controller: 'MonthlyReportController'
                })
            .when('/reports/media-event/compare-mode',
                {
                    templateUrl: currentPath('/CompareMode.html'),
                    controller: 'CompareModeController'
                })


            // test directives.
            .when('/directives/barchart2',
                {
                    templateUrl: currentPath('/BarChart2.html'),
                    controller: 'BarChart2Controller'
                })
            .when('/directives/linechart2',
                {
                    templateUrl: currentPath('/LineChart2.html'),
                    controller: 'LineChart2Controller'
                })
            .when('/directives/piechart2',
                {
                    templateUrl: currentPath('/PieChart2.html'),
                    controller: 'PieChart2Controller'
                })
            .when('/collections/feature',
                {
                    templateUrl: currentPath('/FeatureTranslateRule.html'),
                    controller: 'FeatureTranslateRuleController'
                })
			.otherwise({redirectTo: '/'});

	}]);
	
});