/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("FeatureTranslateRuleController", function ($scope, $FeatureTranslateRule) {

        $scope.pageNo = 1;
        $scope.pageSize = 3;

        $scope.movePage = function(offset) {
            $scope.pageNo = parseInt($scope.pageNo, 10);
            $scope.pageNo += offset;
            $scope.collection.$list({
                pageNo: $scope.pageNo,
                pageSize: $scope.pageSize
            });
        };

        $scope.collection = $FeatureTranslateRule;
        $scope.collection.$list({
            pageNo: $scope.pageNo,
            pageSize: $scope.pageSize
        }).$onSuccess(function(request, response, collection) {
            console.log("API success: ", request, response, collection);
        });

        $scope.$watchCollection("collection", function(collection) {
            console.log("Watched collection changed -> ", collection);
        });

        //// for test model.
        //var model = $scope.collection.$createModel({id: 1});
        //
        //console.log(model);
        //
        //// for test collection.
        //$scope.collection.$list().$onSuccess(function(request, response, collection) {
        //    console.log("onSuccess: ", request, response, collection);
        //});
        //
        //$timeout(function() {
        //    $scope.collection.$list({pageNo: 1, pageSize: 2});
        //}, 2000);
        //
        //$timeout(function() {
        //    $scope.collection.$list({pageNo: 2, pageSize: 2});
        //}, 3000);
        //
        //$timeout(function() {
        //    $scope.collection.$list({pageNo: 3, pageSize: 2});
        //}, 4000);
        //
        //$timeout(function() {
        //    $scope.collection.$list({pageNo: 1, pageSize: 2});
        //}, 5000);
        //
        //$timeout(function() {
        //    $scope.collection.$list({pageNo: 1, pageSize: 2, useCache: true});
        //}, 6000);

    });
});