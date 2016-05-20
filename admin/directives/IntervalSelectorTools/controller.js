/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/IntervalSelectorTools/view.html', 'configs', 'EventHero'], function (angular, app, view, configs, EventHero) {

    app.directive("intervalSelectorTools", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope) {

                var isReady = false;

                /**
                 * Defined params.
                 * @type {{...}}
                 */
                $scope.selectors = {};
                $scope.readyHandler = function() {};
                $scope.exportReport = function() {};

                /**
                 * Initialization the directive with selectors.
                 */
                ((function initDirective() {
                    var event = EventHero.create(function(date, device) {
                        isReady = true;
                        $scope.selectors.date = date;
                        $scope.selectors.device = device;
                        $scope.readyHandler($scope.instance);
                    });

                    var params = event.getParams();
                    $scope.$watch("date", event.listen(params.date));
                    $scope.$watch("device", event.listen(params.device));
                })());

                /**
                 * Invoker for outside of directive.
                 * @type {{}}
                 */
                $scope.instance = {
                    /**
                     * Get child selector
                     * @param name
                     * @returns {*}
                     */
                    getSelector: function(name) {
                        return $scope.selectors[name];
                    },

                    /**
                     * Get all params with each selectors
                     * @returns {{}}
                     */
                    getAll: function() {
                        var params = {};

                        if(isReady) {
                            for(var name in $scope.selectors) {
                                params = angular.extend(params, $scope.selectors[name].getAll());
                            }
                        }

                        return params;
                    },

                    /**
                     * Set value into selector.
                     * @param name
                     * @param value
                     */
                    set: function(name, value) {
                        throw {message: "Not implement"};
                    },

                    /**
                     * Handling directive ready event.
                     * @param readyHandler
                     */
                    ready: function(readyHandler) {
                        $scope.readyHandler = readyHandler;
                    },

                    onExportChart: function(handler) {
                        $scope.exportReport = handler;
                    }
                };
            }
        };
    });
});