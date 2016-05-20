/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/AppFooter/view.html'], function (angular, app, view) {

    app.directive("appFooter", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
            },
            controller:  function($scope) {
                // Nothing to do.
            }
        };
    });
});