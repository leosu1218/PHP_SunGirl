/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/AppHeader/view.html'], function (angular, app, view) {

    app.directive("appHeader", function () {
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