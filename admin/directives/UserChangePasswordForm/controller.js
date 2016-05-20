/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/UserChangePasswordForm/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("userChangePasswordForm", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $location, $http) {

                $scope.url = configs.api.user + "/self";

                /**
                 * Change user self's password by rest api.
                 */
                $scope.change = function () {
                    if($scope.newPassword == $scope.checkPassword) {
                        var data = {
                            password: $scope.oldPassword,
                            newpassword: $scope.newPassword
                        };

                        var request = {
                            method: 'PUT',
                            url: $scope.url,
                            headers: configs.api.headers,
                            data: data
                        };

                        $http(request).success(function(data, status, headers, config) {
                            location.reload();
                        }).error(function(data, status, headers, config) {
                            $scope.alert.show("修改密碼失敗, 請重新嘗試");
                        });
                    }
                    else {
                        $scope.alert.show("兩次輸入的密碼不一樣, 請重新嘗試");
                    }
                }
            }
        };
    });
});