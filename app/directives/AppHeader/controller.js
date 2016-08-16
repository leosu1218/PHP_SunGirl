/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/AppHeader/view.html'], function (angular, app, view) {

    app.directive("appHeader", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
            },
            controller:  function($scope,$timeout) {

                $timeout(function() {
                    //右上角m版icon開關
                    $("li").on("click", function () {
                        $("li").removeClass("on");
                        $(this).addClass("on");
                    });

                    $(".icon-share").on("click", function () {
                        $(".th-share ul").slideToggle(300);
                        return false;
                    });
                },500);
            }
        };
    });
});