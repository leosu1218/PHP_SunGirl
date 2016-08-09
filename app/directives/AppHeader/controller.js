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

                //右上角m版icon開關
                $(".icon-share").on("click",function(){
                    $(".th-share ul").slideToggle(300);
                    return false;
                });
            }
        };
    });
});