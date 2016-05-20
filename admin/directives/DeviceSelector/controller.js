/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/DeviceSelector/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("deviceSelector", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope, $http) {

                /**
                 * Defined params.
                 * @type {{startDate: string, endDate: string}}
                 */
                $scope.params = {};

                $scope.boxList = $scope.boxList || [];
                $scope.camList = $scope.camList|| [];

                /**
                 * Get box id list by rest api and render to ui.
                 */
                $scope.getBoxList = function() {
                    var request = {
                        method: 'GET',
                        url: '/api/imediaevent/self-all/box/list/1/9999',
                        headers: {'Content-Type': 'application/json'},
                        params: {}
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.boxList = data.records;
                    }).error(function(data, status, headers, config) {
                        $scope.alert.show("無法取得Box列表, 請重新嘗試");
                    });
                };

                /**
                 * Get cam list by box id under self device.
                 * @param boxId int Box id.
                 */
                $scope.getCamList = function(boxId) {
                    var request = {
                        method: 'GET',
                        url: '/api/imediaevent/self-all/box/' + boxId + '/cam/list/1/9999',
                        headers: {'Content-Type': 'application/json'},
                        params: {}
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.camList = data.records;
                        $scope.disabledCamSelect = false;
                    }).error(function(data, status, headers, config) {
                        $scope.disabledCamSelect = false;
                        $scope.alert.show("無法取得Cam列表, 請重新嘗試");
                    });
                };

                /**
                 * Fix null option selected
                 */
                $scope.$watch("params.boxId", function(boxId) {
                    $scope.disabledCamSelect = true;
                    $scope.params.camId = null;
                    if($scope.params.boxId == "") {
                        $scope.params.boxId = null
                    }
                    if(boxId) {
                        $scope.getCamList(boxId);
                    }
                    else {
                        $scope.camList = [];
                        $scope.disabledCamSelect = false;
                    }
                });

                /**
                 * Fix null option selected
                 */
                $scope.$watch("params.camId", function(camId) {
                    if($scope.params.camId == "") {
                        $scope.params.camId = null
                    }
                });

                $scope.getBoxList();

                /**
                 * Invoker for outside of directive.
                 * @type {{}}
                 */
                $scope.instance = {
                    get: function(name) {
                        return $scope.params[name];
                    },

                    getAll: function() {
                        return $scope.params;
                    },

                    set: function() {
                        throw {message: "Not implement"};
                    }
                };

            }
        };
    });
});