/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/GenderMonthlyChart/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("genderMonthlyChart", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=?instance',
                startDate:'=startDate',
                boxId:'=boxId',
                camId:'=camId'
            },

            controller: function ($scope) {
                /**
                 * Define customer style.
                 */
                $scope.$watch("chart", function(chart) {
                   if(chart) {
                       $scope.chart.config({
                           labelRules: [
                               {type: "all", name: "當日總人數"},
                               {type: "male", name: "當日男性人數"},
                               {type: "female", name: "當日女性人數"}
                           ]
                       });

                       $scope.chart.setColorGenerator(function(times, data, type) {
                           if(data[times].originalLabel == "male") {
                               return "101,166,222";
                           }
                           else if(data[times].originalLabel == "female"){
                               return "201,70,100";
                           }
                           else {
                               data[times].type = "bar";
                               return "255,204,51";
                           }
                       });

                       var api = configs.api.exporter + "/CurveChartJson/iMedia-Daily";
                       var pageNo      = null;
                       var pageSize    = null;
                       var error       = function(){};
                       var success     = function(){};
                       var params = {startDate:$scope.startDate, endDate:$scope.startDate, boxId:$scope.boxId, camId:$scope.camId};
                       $scope.chart.loadByUrl(api, pageNo, pageSize, success, error, params);
                   }
                });



            }
        };
    });
});