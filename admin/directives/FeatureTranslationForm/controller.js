/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/FeatureTranslationForm/view.html'], function (angular, app, view) {

    app.directive("featureTranslationForm", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
            },

            controller: function ($scope, $FeatureTranslateRule) {
                $scope.rules = $FeatureTranslateRule;

                /**
                 * Clear all rules.
                 */
                $scope.clearRule = function() {
                    for(var index in $scope.rules) {
                        $scope.rules[index].$remove();
                    }
                };
            }
        };
    });
});