/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

    return app.controller("StandardCasePageController", function ($scope, $routeParams, $http) {

    	$scope.pageNo = 1;
		$scope.pageSize = 20;
        $scope.params = $routeParams;	
    	$scope.items = [
    		{value:'all', country:'page-word.png',         name:'全部'},
    		{value:'1', country:'page-eastasia.png',       name:'東亞'},
    		{value:'2', country:'page-southeastasia.png',  name:'東南亞'},
    		{value:'3', country:'page-centralasia.png',    name:'中亞'},
    		{value:'4', country:'page-southernasia.png',   name:'南亞'},
    		{value:'5', country:'page-africa.png',         name:'非洲'},
    		{value:'6', country:'page-southamerica.png',   name:'南美洲'},
    		{value:'7', country:'page-europe.png',         name:'歐洲'},
    	]

    	var standardCasePageGetApi = configs.api.standardCase + "/get/" + $scope.pageNo + '/' + $scope.pageSize + '/' ;
        var Request = {
            method: 'GET',
            url: standardCasePageGetApi,
            headers: configs.api.headers,
            params: $scope.params
        };

        $http(Request).success(function(data, status, headers, config) {
        	$scope.pageDetail = data.records;
            // console.log("success");
        }).error(function(data, status, headers, config){
            // console.log("fail");
        });
    	
    	function getCountryCoverImg(num) {
            var value = "";
            var item = {};
            for(var index in $scope.items) {
                item = $scope.items[index];
                if(item.value == num) {
                    $scope.valueOfCountry = item.country;
                }
            }
            return $scope.valueOfCountry;
        }

        getCountryCoverImg($scope.params.classification);
    });
});