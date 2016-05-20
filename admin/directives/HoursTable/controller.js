/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/HoursTable/view.html'], function (angular, app, view) {

    app.directive("hoursTable", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http) {

                $scope.isLoading           = false;

                /**
                 * Get records that total of index.
                 * @param records JsonArray
                 * @returns {json}
                 */
                $scope.getTotalOfIndex = function(records) {
                    var totalOfIndex = [];
                    var length = 0;
                    var sum = 0;

                    if(records.length > 0) {
                        length = records[0].values.length;
                    }

                    for(var i = 0; i < length; i++) {
                        sum = 0;
                        for(var index in records) {
                            if(records[index].values[i]) {
                                sum += parseInt(records[index].values[i], 10);
                            }
                        }

                        totalOfIndex.push(sum);
                    }

                    return totalOfIndex;
                };

                /**
                 * Get item's total counter.
                 * @param item array
                 */
                $scope.getTotal = function(item) {
                    var total = 0;
                    var index;

                    for(index in item) {
                        if(item[index]) {
                            total += parseInt(item[index], 10);
                        }
                    }

                    return total;
                };

                /**
                 * Get table's fields name.
                 * @param index int
                 * @param items array
                 * @returns {string}
                 */
                $scope.getFieldName = function(index, items) {
                    if(index >= (items.length - 1)) {
                        var nextItem = items[0];
                    }
                    else {
                        var nextItem = items[index + 1];
                    }

                    return items[index] + " ~ " + nextItem;
                };


                /**
                 *	Load table's records from static data.
                 *
                 *	@param data JSON The table's records.
                 *	 					{
				*							records: [ {...}, {...}, ... ],
				*							recordCount: int,
				*							totalPage: int,
				*							pageNo: int,
				*							pageSize: int,
				*						}
                 */
                $scope.loadFromData = function(data) {

                    var rule;
                    $scope.configs = $scope.configs || {};
                    $scope.configs.labelRules = $scope.configs.labelRules || {};
                    for(var key in $scope.configs.labelRules) {
                        rule = $scope.configs.labelRules[key];
                        if(rule) {
                            for(var index in data.records) {
                                if(data.records[index].label == rule.type) {
                                    data.records[index].label = rule.name;
                                }
                            }
                        }
                    }

                    $scope.data = data;
                    $scope.totalOfIndex = $scope.getTotalOfIndex(data.records);
                };

                $scope.loadFromServer = function() {
                    $scope.isLoading = true;
                    var url = $scope.apiurl;
                    if($scope.pageNo) {
                        url += '/' + $scope.pageNo;
                    }
                    if($scope.pageSize) {
                        url += '/' + $scope.pageSize;
                    }
                    if($scope.suffix) {
                        url += $scope.suffix;
                    }

                    var request = {
                        method: 'GET',
                        url: url,
                        headers: {'Content-Type': 'application/json'},
                        params: $scope.params
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.isLoading = false;
                        $scope.loadFromData(data);
                        $scope.loadUrlSuccess(data, status, headers, config);
                    }).error(function(data, status, headers, config) {
                        $scope.isLoading = false;
                        $scope.loadUrlError(data, status, headers, config);
                    });
                };

                $scope.instance = {

                    config: function(configs) {
                        $scope.configs = configs;
                    },

                    /**
                     *	Load table's records from rest api url.
                     *
                     *  @param params json The param for query string.
                     */
                    loadByUrl: function(api, pageNo, pageSize, success, error, params) {
                        $scope.suffix 		   = (params) ? '/' : '';
                        $scope.apiurl 		   = api;
                        $scope.pageNo 		   = pageNo;
                        $scope.pageSize         = pageSize;
                        $scope.params 		   = params;
                        $scope.loadUrlSuccess   = success || $scope.loadUrlSuccess;
                        $scope.loadUrlError 	   = error || $scope.loadUrlError;
                        $scope.loadFromServer();
                    },

                    /**
                     * Load table's records from static data.
                     * @param data JSON The table's records.
                     *                      {
                     *							records: [ {...}, {...}, ... ],
                     *							recordCount: int,
					 *							totalPage: int,
					 *							pageNo: int,
					 *							pageSize: int,
					 *						}
                     */
                    load: function(data) {
                        $scope.loadFromData(data);
                    },

                    /**
                     * Get options settings.
                     * @returns {{responsive: boolean, segmentShowStroke: boolean, segmentStrokeColor: string, segmentStrokeWidth: number, percentageInnerCutout: number, animationSteps: number, animationEasing: string, animateRotate: boolean, animateScale: boolean, legendTemplate: string}|*}
                     */
                    getOptions: function() {
                        return $scope.options;
                    },

                    /**
                     * Set color generator for chart
                     * @param generator function
                     */
                    setColorGenerator: function(generator) {
                        if(typeof(generator) == 'function') {

                        }
                        else {
                            throw "Color generator should be a function.";
                        }
                    }
                }

            },
            scope: {
                instance: "=?instance"
            }
        };
    });
});