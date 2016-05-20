/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbLineChart2/view.html'], function (angular, app, view) {

    app.directive("sbLineChart2", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http) {

                var Chart = window.Chart;
                $scope.canvasId = makeId();

                /**
                 * Make canvas elements ID.
                 * @returns {string}
                 */
                function makeId() {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                    for( var i=0; i < 10; i++ )
                        text += possible.charAt(Math.floor(Math.random() * possible.length));

                    return text;
                }

                $scope.isLoading           = false;
                $scope.apiurl			   = '';
                $scope.pageNo 			   = 0;
                $scope.pageSize 		       = 0;
                $scope.paginationLength     = 5;
                $scope.loadUrlSuccess 	   = function(){};
                $scope.loadUrlError 	       = function(){};

                // Default options.
                $scope.options =  {
                    // Sets the chart to be responsive
                    responsive: true,

                    ///Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines : true,

                    //String - Colour of the grid lines
                    scaleGridLineColor : "rgba(0,0,0,.05)",

                    //Number - Width of the grid lines
                    scaleGridLineWidth : 1,

                    //Boolean - Whether the line is curved between points
                    bezierCurve : true,

                    //Number - Tension of the bezier curve between points
                    bezierCurveTension : 0.4,

                    //Boolean - Whether to show a dot for each point
                    pointDot : true,

                    //Number - Radius of each point dot in pixels
                    pointDotRadius : 4,

                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth : 1,

                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius : 20,

                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke : true,

                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth : 2,

                    //Boolean - Whether to fill the dataset with a colour
                    datasetFill : true,

                    // Function - on animation progress
                    onAnimationProgress: function(){},

                    // Function - on animation complete
                    onAnimationComplete: function(){},

                    legend: {
                        position: 'bottom',
                    },
                };

                /**
                 * Get a random color code
                 * @returns {string} e.g. FF0096
                 */
                function getRandomColor() {
                    var text = "";

                    var c1 = Math.floor((Math.random() * 254) + 1);
                    var c2 = Math.floor((Math.random() * 254) + 1);
                    var c3 = Math.floor((Math.random() * 254) + 1);

                    return text + c1 + "," + c2 + "," + c3;
                }

                $(document).foundation();

                /**
                 * Get records default json format.
                 * @returns {{labels: Array, datasets: {label: string, fillColor: string, data: Array}[]}}
                 */
                function getDefaultRecords() {
                    return  {
                        labels: [],
                        datasets: []
                    };
                }

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
                    var records = getDefaultRecords();
                    var color;

                    var rule;
                    $scope.configs = $scope.configs || {};
                    $scope.configs.labelRules = $scope.configs.labelRules || {};

                    for(var index in data.records) {
                        data.records[index].originalLabel = data.records[index].label;
                    }

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

                    records.labels = data.labels;

                    for(var i = 0; i < data.records.length; i++) {
                        color = getRandomColor(i, data.records, "sb-line-chart2");
                        records.datasets.push({
                            type: data.records[i].type || "line",
                            label: data.records[i].label,
                            originalLabel: data.records[i].originalLabel,
                            borderColor: "rgba(" + color + ", 1)",
                            backgroundColor: "rgba(" + color + ", 0.5)",
                            pointBorderColor: "rgba(" + color + ", 0.5)",
                            pointBackgroundColor: '#fff',
                            pointBorderWidth: 1,
                            fill: false,
                            data: data.records[i].values
                        });
                    }

                    $scope.records = records;

                    var config = {
                        type: "bar",
                        data: $scope.records,
                        options: $scope.options,
                    };

                    if(!$scope.chart) {
                        var ctx = document.getElementById($scope.canvasId).getContext("2d");
                        $scope.chart = new Chart(ctx, config);
                    }
                    else {
                        $scope.chart.config = config;
                        $scope.chart.update();
                    }
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
                            getRandomColor = generator;
                        }
                        else {
                            throw "Color generator should be a function.";
                        }
                    }
                }

            },
            scope: {
                instance: "=?instance",
            },
        };
    });
});