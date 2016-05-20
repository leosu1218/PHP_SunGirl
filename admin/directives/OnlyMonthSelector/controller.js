/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/OnlyMonthSelector/view.html', 'configs'], function (angular, app, view) {

    app.directive("onlyMonthSelector", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=instance'
            },

            controller: function ($scope) {

                /**
                 * Defined params.
                 * @type {{startDate: string, endDate: string}}
                 */
                $scope.params = {};

                /**
                 * On date input change.
                 */
                $scope.onDateChanged = function() {
                    if($scope.startDate){
                        $scope.params.startDate = dateTimeFormat($scope.startDate);
                    }
                };

                /**
                 * Format datetime object to string (Y:m:d H:i:s)
                 *
                 * @param date
                 * @param time
                 * @returns {string}
                 */
                function dateTimeFormat (date, time) {
                    time = time || date;
                    var year = "" + date.getFullYear();
                    var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
                    var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
                    var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
                    var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
                    var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
                    return year + "-" + month;
                }

                /**
                 * Invoker for outside of directive.
                 * @type {{}}
                 */
                $scope.instance = {
                    get: function(name) {
                        return $scope.params[name];
                    },

                    getAll: function() {
                        return $scope.params;
                    },

                    set: function() {
                        throw {message: "Not implement"};
                    }
                };
            }
        };
    });
});