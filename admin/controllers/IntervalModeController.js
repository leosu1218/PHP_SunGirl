/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("IntervalModeController", function ($scope, $timeout) {

        $scope.charts = {
            fullHourlyChart: {isHide: true} ,
            fullProportionChart: {isHide: true} ,
            genderDailyChart: {isHide: true} ,
            genderHourlyChart: {isHide: true}
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
            if($scope.charts[name].instance) {
                var params = $scope.selector.getAll();
                $scope.charts[name].instance.loadByParams(params);
                $scope.charts[name].isHide = false;
            }
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