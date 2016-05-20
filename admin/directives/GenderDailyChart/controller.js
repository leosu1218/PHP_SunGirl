/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/GenderDailyChart/view.html'], function (angular, app, view) {

    app.directive("genderDailyChart", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope, $GenderDailyCollection) {

                $scope.collection = $GenderDailyCollection;

                /**
                 * Default table values.
                 * @type {{}}
                 */
                $scope.table = {
                    days: [],
                    male: [],
                    female: []
                };

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
                   }
                });

                $scope.$watchCollection("collection", function() {
                    if($scope.collection.length > 0 && $scope.chart) {
                        var result = $scope.collection.$toJson();

                        $scope.chart.load(result);
                        $scope.table.days = result.labels;

                        for (var i in result.records) {
                            if (result.records[i].originalLabel == 'male') {
                                $scope.table.male = result.records[i].values;
                            }
                            else if (result.records[i].originalLabel == 'female') {
                                $scope.table.female = result.records[i].values;
                            }
                            else {
                                $scope.table.all = result.records[i].values;
                            }
                        }
                    }
                });

                /**
                 * Format float variable.
                 * @param num
                 * @param pos
                 * @returns {number}
                 */
                $scope.formatFloat = function(num, pos) {
                    if(!isNaN(num)) {
                        var size = Math.pow(10, pos);
                        return Math.round(num * size) / size;
                    }
                    else {
                        return 0;
                    }
                };

                /**
                 * Invoker for outside of directive.
                 * @type {{}}
                 */
                $scope.instance = {
                    /**
                     * Setting feature name translate rule.
                     * @param rules
                     */
                    config: function(rules) {
                        $scope.rules = rules;
                    },

                    /**
                     * Query date from RESTful API by params.
                     * @param params
                     */
                    loadByParams: function(params) {
                        $scope.collection.$find({
                            params: params,
                            useCache: true
                        });
                    },

                    /**
                     * Handling error event.
                     * @param handler
                     */
                    onError: function(handler) {
                        $scope.errorHandler = handler;
                    }
                };
            }
        };
    });
});