/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("PieChart2Controller", function ($scope, $timeout, $FullProportionCollection) {

        // Test collection.
        var counter = 0;
        var startDates = ['2016-02-26', '2016-02-27', '2016-02-28', '2016-02-26', '2016-02-27'];

        $scope.collection = $FullProportionCollection;

        $scope.$watchCollection("collection", function(instance) {
            console.log('collection:', instance);
            console.log('collection.$toJson:', instance.$toJson());
        });


        for(var i in startDates) {
            $timeout(function() {
                if(startDates[counter]) {
                    console.log("get new data: ", counter);
                    $scope.collection.$find({
                        params: {
                            endDate: '2016-03-03',
                            startDate: startDates[counter]
                        }
                    }).$onSuccess(function(request, response) {
                        //console.log("success new data: request, response->", request, response);
                    });
                    counter++;
                }
            }, i * 1000);
        }


        //$scope.$watch("chart", function(chart) {
        //
        //    if(chart) {
        //
        //        var pageNo      = null;
        //        var pageSize    = null;
        //        var success     = function(){};
        //        var error       = function(){};
        //        var params      = null;
        //        chart.loadByUrl("/api/test/piechart", pageNo, pageSize, success, error, params);
        //
        //        $timeout(function(){
        //            chart.load({
        //                records: [
        //                    {value: 10, label: 'type1'},
        //                    {value: 50, label: 'type2'},
        //                    {value: 100, label: 'type3'}
        //                ]
        //            });
        //        }, 3000)
        //
        //        $timeout(function(){
        //            chart.loadByUrl("/api/test/piechart", pageNo, pageSize, success, error, params);
        //        }, 6000)
        //    }
        //});

    });
});