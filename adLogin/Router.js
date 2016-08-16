/*global define*/
'use strict';

// set up base routes
define(['angular', 'app', 'configs',
	'controllers/LoginController',
	
], function (angular, app, configs) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return configs.path.appRoot + '/views' + path;
		}

		$routeProvider
			.when('/', 		{ templateUrl: currentPath('/login.html'), 			controller: 'LoginController' })
			.otherwise({redirectTo: '/'});

	}]);
	
});