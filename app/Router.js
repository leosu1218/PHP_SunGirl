/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'text!views/Home.html',

	'controllers/HomeController',
	'controllers/AboutController',
	'controllers/WorkProjectController',
	'controllers/AnalysisController',
	'controllers/StandardCaseController',
	'controllers/StandardCasePageController',
	'controllers/ActivityController',
	'controllers/ActivityPageController',
	'controllers/ConsultationController',
	'controllers/ConnectionController',
	'controllers/NewsController',
	'controllers/NewsPageController',
	//'controllers/HeaderController',
	//'controllers/FooterController',
	//'controllers/BsCarouselController',
	//'controllers/BsThumbnailDoctorController',
	//'controllers/BsThumbnailCircleController',
	//'controllers/BsThumbnailController',
	//'controllers/BsLoginPanelController',
	//'controllers/MainController'
], function (angular, app, Home) {

	return app.config([ '$routeProvider', function ($routeProvider) {

		function currentPath(path) {
			return 'app/views' + path;
		}

		$routeProvider
			.when('/', {template: Home, controller: 'HomeController'})
			.when('/about', {templateUrl: currentPath('/About.html'), controller: 'AboutController'})
			.when('/work/project', {templateUrl: currentPath('/WorkProject.html'), controller: 'WorkProjectController'})
			.when('/analysis', {templateUrl: currentPath('/Analysis.html'), controller: 'AnalysisController'})
			.when('/standard/case', {templateUrl: currentPath('/StandardCase.html'), controller: 'StandardCaseController'})
			.when('/standard/case/page', {templateUrl: currentPath('/StandardCasePage.html'), controller: 'StandardCasePageController'})
			.when('/activity', {templateUrl: currentPath('/Activity.html'), controller: 'ActivityController'})
			.when('/activity/page', {templateUrl: currentPath('/ActivityPage.html'), controller: 'ActivityPageController'})
			.when('/consultation', {templateUrl: currentPath('/Consultation.html'), controller: 'ConsultationController'})
			.when('/connection', {templateUrl: currentPath('/Connection.html'), controller: 'ConnectionController'})
			.when('/news', {templateUrl: currentPath('/News.html'), controller: 'NewsController'})
			.when('/news/page', {templateUrl: currentPath('/NewsPage.html'), controller: 'NewsPageController'})
			.otherwise({redirectTo: '/'});

	}]);
});