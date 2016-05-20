/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("MonthlyReportController", function ($scope, $timeout) {

        $scope.charts = {
            fullHourlyChart: {isHide: true} ,
            fullProportionChart: {isHide: true}
        };

        /**
         * Hide all chart
         */
        function hideAllChart() {
            for(var name in $scope.charts) {
                $scope.charts[name].isHide = true;
            }
        }

        /**
         * load and show a chart result.
         * @param name Chart name
         */
        function loadChart(name) {
            console.log("loadChart->", name);
            console.log("params->", $scope.selector.getAll());
            var params = $scope.selector.getAll();
            var date = params.startDate.split("-");
            $scope.calendar.setYearMonth(date['0'] , date['1'] , params);
            //if($scope.charts[name].instance) {
            //    $scope.charts[name].instance.loadByParams(params);
            //    $scope.charts[name].isHide = false;
            //}
        }


        /**
         * Controller ready event handle.
         */
        $timeout(function() {

            /**
             * Initial selector directive.
             */
            (function initSelectorTools() {
                $scope.selector.onExportChart(function(event) {
                    hideAllChart();
                    loadChart(event);
                });
            }());

        });

    });
});