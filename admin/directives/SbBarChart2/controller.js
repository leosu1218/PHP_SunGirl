/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbBarChart2/view.html', 'Chart2'], function (angular, app, view) {

    app.directive("sbBarChart2", function () {
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
                    //Boolean - Whether we should show a stroke on each segment
                    segmentShowStroke : true,
                    //String - The colour of each segment stroke
                    segmentStrokeColor : '#fff',
                    //Number - The width of each segment stroke
                    segmentStrokeWidth : 2,
                    //Number - The percentage of the chart that we cut out of the middle
                    percentageInnerCutout : 0, // This is 0 for Pie charts
                    //Number - Amount of animation steps
                    animationSteps : 100,
                    //String - Animation easing effect
                    animationEasing : 'easeOutBounce',
                    //Boolean - Whether we animate the rotation of the Doughnut
                    animateRotate : true,
                    //Boolean - Whether we animate scaling the Doughnut from the centre
                    animateScale : false,

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
                    var color = getRandomColor();
                    return {
                        labels: [""],
                        datasets: [
                        ]
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

                    for(var i = 0; i < data.records.length; i++) {
                        color = getRandomColor(i, data.records, "sb-bar-chart2");
                        records.datasets[i] = {
                            label: data.records[i].label,
                            data: []
                        };

                        if(data.records[i].type) {
                            records.datasets[i].type = data.records[i].type;
                        }

                        records.datasets[i].data.push(data.records[i].value);
                        records.datasets[i].borderColor = "rgba(" + color + ", 0.5)";;
                        records.datasets[i].backgroundColor = "rgba(" + color + ", 0.8)";;
                        records.datasets[i].pointBorderColor = "rgba(" + color + ", 0.75)";;
                        records.datasets[i].pointBackgroundColor = "rgba(" + color + ", 1)";
                        records.datasets[i].pointBorderWidth = 1;
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
                        for(var index in data['records']){
                            data['records'][index]['value'] = parseInt( data['records'][index]['value'] );
                        }

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