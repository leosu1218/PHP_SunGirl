/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("LineChart2Controller", function ($scope, $timeout) {
        $scope.$watch("chart", function(chart) {

            if(chart) {

                var pageNo      = null;
                var pageSize    = null;
                var success     = function(){};
                var error       = function(){};
                var params      = null;
                chart.loadByUrl("/api/test/curvechart", pageNo, pageSize, success, error, params);

                $timeout(function(){
                    chart.load({
                        labels: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
                            '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
                            '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
                            '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                        records: [
                            {values: [10,15,30,34,44,49,12,42,66,21,34,29,45,21,46,72,12,58,49,12,30,44,27,46], label: '1'},
                            {values: [12,35,77,23,46,12,34,56,23,45,56,12,56,44,67,23,45,67,12,84,35,78,23,46], label: '2', type: "bar"},
                            {values: [90,85,32,34,56,23,67,23,67,85,32,45,46,77,54,57,23,12,33,56,78,56,69,34], label: '3'}
                        ]
                    });
                }, 3000);

                $timeout(function(){
                    chart.loadByUrl("/api/test/curvechart", pageNo, pageSize, success, error, params);
                }, 6000);
            }
        });

    });
});