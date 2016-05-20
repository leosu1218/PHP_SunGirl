/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/GenderHourlyChart/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("genderHourlyChart", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope, $FeatureTranslateRule, $timeout, $GenderHourlyCollection) {

                $scope.emitError = function() {};

                /**
                 * Directive ready event handle.
                 */
                $timeout(function() {

                    $scope.rules = $FeatureTranslateRule;

                    /**
                     * Initialization the a chart directive with rules.
                     */
                    (function initChart() {

                        if($scope.rules.length > 0) {
                            $scope.chart.config({labelRules: $scope.rules});
                        }

                        $scope.chart.setColorGenerator(function(times, data, type) {
                            try {
                                if(data[times].originalLabel == "male") {
                                    return "101,166,222";
                                }
                                else if(data[times].originalLabel == "female"){
                                    return "201,70,100";
                                }
                                else if(data[times].originalLabel == "all") {
                                    return "255,204,51";
                                }
                                else {
                                    return "99,99,99";
                                }
                            }
                            catch(e) {
                                return "99,99,99";
                            }
                        });

                    }());
                });

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
                        $GenderHourlyCollection.$find({
                            params: params,
                            useCache: true
                        }).$onSuccess(function() {
                            var result = $GenderHourlyCollection.$toJson();
                            $scope.chart.load(result);
                        });
                    },

                    /**
                     * Handling error event.
                     * @param handler
                     */
                    onError: function(handler) {
                        $scope.emitError = handler;
                    }
                };
            }
        };
    });
});