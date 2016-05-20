/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("StandardCaseController", function ($scope) {
        $scope.presetCountry = true;
       
    	$scope.items = [
    		{num:'0', img:'title-1.png', country:'word.png',           name:'全部'},
    		{num:'1', img:'title-2.png', country:'eastasia.png',       name:'東亞'},
    		{num:'2', img:'title-3.png', country:'southeastasia.png',  name:'東南亞'},
    		{num:'3', img:'title-4.png', country:'centralasia.png',    name:'中亞'},
    		{num:'4', img:'title-5.png', country:'southernasia.png',   name:'南亞'},
    		{num:'5', img:'title-6.png', country:'africa.png',         name:'非洲'},
    		{num:'6', img:'title-7.png', country:'southamerica.png',   name:'南美洲'},
    		{num:'7', img:'title-8.png', country:'europe.png',         name:'歐洲'},
    	]

        $scope.changeCountry = function(item){
            $scope.presetCountry = false;   
            $scope.getCountry = item.country;
            $scope.changeStyle(item.num);
        }

        $scope.changeStyle = function(num){
            console.log(num);
            
            switch(num){
                case '0':
                    $scope.countryStyle = {left: '13%'};
                    console.log(num);
                    break; 
                case '1':
                    $scope.countryStyle = {left: '19%'};
                    console.log(num);
                    break;
                case '2':
                    $scope.countryStyle = {left: '31%'};
                    console.log(num);
                    break;
                case '3':
                    $scope.countryStyle = {left: '21%'};
                    break;
                case '4':
                    $scope.countryStyle = {left: '32%'};
                    break;
                case '5':
                    $scope.countryStyle = {left: '33%'};
                    break;
                case '6':
                    $scope.countryStyle = {left: '33%'};
                    break; 
                case '7':
                    $scope.countryStyle = {left: '24%'};
                    break; 

            }
        }
    });
});