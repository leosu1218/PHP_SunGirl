/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'text!views/Home.html',

	'controllers/HomeController',
	'controllers/AlbumController',
    'controllers/VideoController',
    'controllers/DownloadController',
    'controllers/InfoController'

], function (angular, app, Home) {

	return app.config([ '$routeProvider', function ($routeProvider) {

		function currentPath(path) {
			return 'app/views' + path;
		}

		$routeProvider
			.when('/', {template: Home, controller: 'HomeController'})
			.when('/album', {templateUrl: currentPath('/Album.html'), controller: 'AlbumController'})
			.when('/video', {templateUrl: currentPath('/Video.html'), controller: 'VideoController'})
			.when('/download', {templateUrl: currentPath('/Download.html'), controller: 'DownloadController'})
            .when('/info', {templateUrl: currentPath('/Info.html'), controller: 'InfoController'})
			.otherwise({redirectTo: '/'});

	}]);
});