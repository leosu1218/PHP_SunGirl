/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/UrlInput/view.html',
    'jquery'
], function(
    angular,
    app,
    configs,
    view,
    $) {
    app.directive("urlInput", function() {
        return {
            restrict: "E",
            template: view,
            controller: function($scope, $http) {

                $scope.buttonClick = function() {
                    var data = {};
                    data['bannerUrl'] = decodeURIComponent($scope.bannerUrl);
                    var api = $scope.getImgApi + "/" + $scope.bannerid;
                    var request = {
                        method: 'PUT',
                        url: api,
                        headers: configs.api.headers,
                        data: data,
                    };
                    $http(request).success(function(data, status, headers, config) {
                        $scope.alert.show("success");
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("資料傳送有誤，請再次嘗試。");
                    });
                };
            },
            scope: {
                instance: '=?instance',
                bannerid: '=?bannerid',
                bannerUrl: '=?bannerimg',
                getImgApi: '=?getImgApi'
            }
        }
    });
});