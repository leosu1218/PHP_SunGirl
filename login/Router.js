/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'controllers/LoginController',
	'controllers/RegisterController',
	'controllers/RegisterSuccessController',
	
], function (angular, app) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return app.applicationPath + '/views' + path;
		}

		$routeProvider
			.when('/', 		{ templateUrl: currentPath('/Login.html'), 			controller: 'LoginController' })
			.when('/register', 		{ templateUrl: currentPath('/Register.html'), 		controller: 'RegisterController' })
			.when('/register/success', 		{ templateUrl: currentPath('/RegisterSuccess.html'), 		controller: 'RegisterSuccessController' })
			.otherwise({redirectTo: '/'});

	}]);
	
});


