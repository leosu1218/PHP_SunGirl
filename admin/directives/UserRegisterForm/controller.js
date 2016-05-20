/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/UserRegisterForm/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("userRegisterForm", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $location, $http) {

                /**
                 * Create new user by rest api
                 */
                $scope.create = function () {
                    var request = {
                        method: 'POST',
                        url: configs.api.user + "/register",
                        headers: configs.api.headers,
                        data: {
                            domain: configs.domain,
                            account: $scope.account,
                            password: $scope.password,
                            groupId: configs.registerGroupId,
                            name: $scope.name,
                            email: $scope.email,
                            personPermissions: configs.registerPersonPermission,
                            groupPermissions: []
                        }
                    };

                    $http(request).success(function(data, status, headers, config) {
                        location.reload();
                    }).error(function(data, status, headers, config) {
                        $scope.alert.show("新增使用者失敗, 請重新嘗試");
                    });
                }
            }
        };
    });
});