/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("BarChart2Controller", function ($scope, $timeout) {
        $scope.$watch("chart", function(chart) {

            if(chart) {

                var pageNo      = null;
                var pageSize    = null;
                var success     = function(){};
                var error       = function(){};
                var params      = null;
                chart.loadByUrl("/api/test/barchart", pageNo, pageSize, success, error, params);

                $timeout(function(){
                    chart.load({
                        records: [
                            {value: 10, label: 'type1'},
                            {value: 50, label: 'type2'},
                            {value: 100, label: 'type3'}
                        ]
                    });
                }, 3000);

                $timeout(function(){
                    chart.loadByUrl("/api/test/piechart", pageNo, pageSize, success, error, params);
                }, 6000);
            }
        });

    });
});