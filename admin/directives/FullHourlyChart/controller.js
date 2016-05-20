/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/FullHourlyChart/view.html', 'configs'], function (angular, app, view, configs) {

    app.directive("fullHourlyChart", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope, $FeatureTranslateRule, $timeout, $FullHourlyCollection) {

                $scope.emitError = function() {};

                /**
                 * Check label is female.
                 * @returns {boolean}
                 */
                function isFemale(label) {
                    var reg = /y\:\d, g\:2/;
                    return reg.test(label);
                    return true;
                }

                /**
                 * Check label is male.
                 * @returns {boolean}
                 */
                function isMale(label) {
                    var reg = /y\:\d, g\:1/;
                    return reg.test(label);
                    return true;
                }

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
                                if(data[times].originalLabel == "y:1, g:2") {
                                    return "225,203,191";
                                }
                                else if(data[times].originalLabel == "y:2, g:2"){
                                    return "255,204,51";
                                }
                                else if(data[times].originalLabel == "y:3, g:2"){
                                    return "201,70,100";
                                }
                                else if(data[times].originalLabel == "y:4, g:2"){
                                    return "73,36,77";
                                }
                                else if(data[times].originalLabel == "y:1, g:1"){
                                    return "212,233,250";
                                }
                                else if(data[times].originalLabel == "y:2, g:1"){
                                    return "101,166,222";
                                }
                                else if(data[times].originalLabel == "y:3, g:1"){
                                    return "159,194,56";
                                }
                                else if(data[times].originalLabel == "y:4, g:1"){
                                    return "26,70,78";
                                }
                                else {
                                    if(isFemale(data[times].originalLabel)) {
                                        return "147,19,20";
                                    }
                                    else if(isMale(data[times].originalLabel)) {
                                        return "86,163,133";
                                    }
                                    else {
                                        return "99,99,99";
                                    }
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
                        $FullHourlyCollection.$find({
                            params: params,
                            useCache: true
                        }).$onSuccess(function() {
                            var result = $FullHourlyCollection.$toJson();

                            $scope.chart.load(result);
                            $scope.table.load(result);
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