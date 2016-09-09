/*global define*/
'use strict';

// set up base routes
define(['angular', 'app', 'configs',
	'controllers/UserListController',
    'controllers/CarouselController',
    'controllers/SungirlbbPhotoListController',
    'controllers/CreateSungirlPhotoController',
    'controllers/SungirlPhotoController',
    'controllers/SungirlbbVideoListController',
    'controllers/CreateSungirlVideoController',
    'controllers/SungirlVideoController',
    'controllers/SungirlbbDownloadListController',
    'controllers/CreateSungirlDownloadController',
    'controllers/SungirlDownloadController'

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
            .when('/photoList/create',
            {
                templateUrl: currentPath('/CreateSungirlPhoto.html'),
                controller: 'CreateSungirlPhotoController'
            })
            .when('/photoList/view/:id',
            {
                templateUrl: currentPath('/SungirlPhoto.html'),
                controller: 'SungirlPhotoController'
            })
            .when('/videoList',
            {
                templateUrl: currentPath('/SungirlbbVideoList.html'),
                controller: 'SungirlbbVideoListController'
            })
            .when('/videoList/create',
            {
                templateUrl: currentPath('/CreateSungirlVideo.html'),
                controller: 'CreateSungirlVideoController'
            })
            .when('/videoList/view/:id',
            {
                templateUrl: currentPath('/SungirlVideo.html'),
                controller: 'SungirlVideoController'
            })
            .when('/downloadList',
            {
                templateUrl: currentPath('/SungirlbbDownloadList.html'),
                controller: 'SungirlbbDownloadListController'
            })
            .when('/downloadList/create',
            {
                templateUrl: currentPath('/CreateSungirlDownload.html'),
                controller: 'CreateSungirlDownloadController'
            })
            .when('/downloadList/view/:id',
            {
                templateUrl: currentPath('/SungirlDownload.html'),
                controller: 'SungirlDownloadController'
            })

			.otherwise({redirectTo: '/'});

	}]);
	
});