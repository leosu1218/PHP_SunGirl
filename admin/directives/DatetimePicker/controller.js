/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/DatetimePicker/view.html',
    'jquery'
], function (
    angular,
    app,
    configs,
    view,
    $)
{
    app.directive("datetimePicker", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.onDateInputChanged = function(){};
                $timeout(function (){
                    $scope.$watch("idName", function(idName) {
                        if(idName){
                            if($scope.dateType == "date"){
                                $("#" + idName).datepicker ({
                                    dateFormat: 'yy-mm-dd'
                                });
                            }else{
                               $("#" + idName).datetimepicker({
                                    dateFormat: 'yy-mm-dd',
                                    timeFormat: 'HH:mm:ss'
                                });
                            }
                        }
                    });

                    $("#" + $scope.idName).on('change',function(){
                        $scope.dateTimes = $("#" + $scope.idName).val();
                        $scope.onDateInputChanged();
                    });
                }, 100);

                $scope.instance = {
                    getdate:function(){
                        return $scope.dateTimes;
                    },
                    setdate:function(date){
                         $scope.dateTimes = date;
                        $("#" + $scope.idName).val(date);
                    },

                    /**
                     * change date do something
                     * @param handler
                     */
                    onDateInputChanged: function(handler) {
                        $scope.onDateInputChanged = handler;
                    }
                }
                
            },
            scope: {
                instance: '=?instance',
                idName: '=?idname',
                dateType: '=?datetype'
            }
        }
    });
});