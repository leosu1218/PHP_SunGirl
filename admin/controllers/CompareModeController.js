/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("CompareModeController", function ($scope, $timeout) {

        $scope.charts = {
            recentlyMonthChart: {isHide: true}
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
                console.log("loadChart", name, params);
                //$scope.charts[name].instance.loadByParams(params);
                //$scope.charts[name].isHide = false;
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