/*global define*/
'use strict';

// set up base routes
define(['angular', 'app', 'configs',
	'controllers/UserListController',
    'controllers/ImportIMediaController',
    'controllers/CarouselController',
    'controllers/SungirlbbPhotoListController'

], function (angular, app, configs) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return configs.path.appRoot + '/views' + path;
		}

		$routeProvider
            .when('/',
                {
                    templateUrl: currentPath('/SungirlbbPhotoList.html'),
                    controller: 'SungirlbbPhotoListController'
                })
            .when('/user/list',
                {
                    templateUrl: currentPath('/UserList.html'),
                    controller: 'UserListController'
                })
            .when('/carousel',
                {
                    templateUrl: currentPath('/Carousel.html'),
                    controller: 'CarouselController'
                })
            .when('/photoList',
            {
                templateUrl: currentPath('/SungirlbbPhotoList.html'),
                controller: 'SungirlbbPhotoListController'
            })

			.otherwise({redirectTo: '/'});

	}]);
	
});