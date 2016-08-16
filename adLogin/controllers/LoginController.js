/*global define*/
'use strict';

define(['angular', 'app', 'message', 'configs'], function (angular, app, message, configs) {

	/**
	*	Controller for platform user login view.
	*
	*/
	return app.controller("LoginController", function ($scope, $http, $location) {		

		var adminRoot 	= configs.path.admin;
		var domain 		= configs.domain;
		var api 		= configs.api;

		/**
		*	Get ui customer error message.
		*
		*	@params status int Http status code.
		*	@return string Error message.
		*/
		var getErrorMessage = function(status) {
			if(!(status)){
				return message.UNDEFINE_ERROR;
			}
			else if(status == 403) {
				return message.LOGIN_FORBIDDEN_ERROR;
			}
			else {
				return message.SERVER_ERROR;
			}
		}

		var successHandler = function() {			
			window.location = adminRoot;
		}

		var errorHandler = function(status) {
			$scope.showMessage = true;
			$scope.message = getErrorMessage(status);
		}

		var checkLogin = function() {
			var request = {
				method: 'GET',
			 	url: api.userSelf,
			 	headers: api.headers,			 	
			}

			$http(request).success(function(data, status, headers, config){				
				successHandler();
			});
		}

		$scope.login = function() {			
			var request = {
				method: 'POST',
			 	url: api.loginPlatform,
			 	headers: api.headers,
			 	data: {
			 		domain: domain,
			 		account: $scope.account, 
			 		password: $scope.password
			 	}
			}

			$http(request).success(function(data, status, headers, config){				
				successHandler();
			}).error(function(data, status, headers, config){				
				errorHandler(status);
			});
		}

		checkLogin();
	});
});