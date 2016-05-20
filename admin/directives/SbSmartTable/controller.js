/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbSmartTable/view.html'], function (angular, app, view) {

	app.directive("sbSmartTable", function () {
		return {
			restrict: "E",			
			template: view,
			controller:  function($scope, $http, $timeout) {

				// Define default evnet
				$scope.tooltipFilter 	= {type:'button'};
				$scope.rowClickHandler 	= function(row, field, instance) {};
				$scope.headClickHandler = function(column, iterator) {};
				$scope.apiurl			= '';
				$scope.pageNo 			= 0;
				$scope.pageSize 		= 0;
				$scope.paginationLength = 5;
				$scope.loadUrlSuccess 	= function(){};
				$scope.loadUrlError 	= function(){};

				/**
				*	Set use for costumer field or default field configuration.
				*
				*	@param records array The table's  data records.
				*	@return json Field configuration.
				*/
				function currenctFieldFilter(records) {

					if( records && records.length > 0 && !($scope.fieldFilter)) {
						var defaultFilter = [];
						
						for(var key in records) {
							defaultFilter.push({attribute: key, name: key});
						}

						$scope.fieldFilter = defaultFilter;	
					}
				}

				$scope.onHeadClick = function(attributeName, fieldName) {
					
					var column = {
						attribute: attributeName, 
						name: fieldName
					};					
					
					var iterator = function(callback) {
						for(var index in $scope.records) {
							var cell = {
								value: function(newValue) {
									newValue = newValue || $scope.records[index][attributeName];
									return $scope.records[index][attributeName] = newValue;
								},
								rowIndex: index,
							};
							callback(cell);
						}
					};

					$scope.headClickHandler(column, iterator);
				};

				$scope.defaultRowCss = {'background-color':'#AAAAAA'};
				$scope.onRowClick = function(record, attribute) {

					var instance = {
						selected:function( css ){

							$scope.onRowClickCss = css;

							if(record.selected == null){
								record.selected = false;
							}

							if(!record.selected)
							{
								css = (css||$scope.defaultRowCss);
								record.selected = true;
							}
							else
							{
								css = {};
								record.selected = false;
							}
							record.style = css;
						}
					};

					$scope.rowClickHandler( record, attribute, instance );
				};

				/**
				*	Setting pagination's event
				*
				*/
				$timeout(function() {

					$scope.pagination.onPageClick(function(page) {
						$scope.pageNo = page.number;
						$scope.loadFromServer();
					})

					$scope.pagination.onPreviousClick(function(pageNo) {
						$scope.pageNo--;
						$scope.loadFromServer();
					})

					$scope.pagination.onNextClick(function(pageNo) {
						$scope.pageNo++;
						$scope.loadFromServer();
					})

				}, 100);				

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
					currenctFieldFilter(data.records);
					$scope.records = data.records;						
					$scope.pagination.setLength($scope.paginationLength);
					$scope.pagination.load({
						recordCount: parseInt(data.recordCount, 10),
						totalPage: parseInt(data.totalPage, 10),
						pageSize: parseInt(data.pageSize, 10),
						pageNo: parseInt(data.pageNo, 10)
					});
				}

				$scope.loadFromServer = function() {					
					
					var url = $scope.apiurl + '/' + $scope.pageNo + '/' + $scope.pageSize + $scope.suffix;

					var request = {
						method: 'GET',
					 	url: url,
					 	headers: {'Content-Type': 'application/json'},
					 	params: $scope.params
					}

					$http(request).success(function(data, status, headers, config) {
						$scope.loadFromData(data);
						$scope.loadUrlSuccess(data, status, headers, config);
					}).error(function(data, status, headers, config) {
						$scope.loadUrlError(data, status, headers, config);
					});
				};

				$scope.instance = {

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
					load: function(data) {
						$scope.loadFromData(data);
					},

					/**
                     *	Load table's records from rest api url.
                     *
                     *  @param params json The param for query string.
                     */
					loadByUrl: function(api, pageNo, pageSize, success, error, params) {	
						$scope.suffix 			= (params) ? '/' : '';				
						$scope.apiurl 			= api;
						$scope.pageNo 			= pageNo;
						$scope.pageSize 		= pageSize;
						$scope.params 			= params;						
						$scope.loadUrlSuccess 	= success;
						$scope.loadUrlError 	= error;
						$scope.loadFromServer();
					},

					/**
					*	Set handler for row click event.
					*
					*/
					onRowClick: function(handler) {
						$scope.rowClickHandler = handler;
					},

					/**
					*	Set handler for row click event.
					*
					*
					*/
					onHeadClick: function(handler) {
						$scope.headClickHandler = handler;
					},

					/**
					*	Configuration for field attribute and events.
					*
					*/
					configField: function(config) {
						$scope.fieldFilter = config;
					},

					/**
					*	get field attribute.
					*
					*/
					getField: function(){
						return $scope.records;
					},

					/**
					*	get selected field attribute.
					*
					*/
					getSelectedField: function(){
						var result = [];
						var records = $scope.records;
						for(var index in records){
							if( records[index].selected ){
								result.push( records[index] );
							}
						}
						return result;
					},

					/**
					*	selected all field attribute.
					*
					*/
					selectedAllField: function(){
						for(var index in $scope.records){
							$scope.records[index].selected = true;
							$scope.records[index].style = ($scope.onRowClickCss||$scope.defaultRowCss);
						}
					},

					selectedCancelAllField:function(){
						for(var index in $scope.records){
							$scope.records[index].selected = false;
							$scope.records[index].style = "";
						}
					},

					/**
					*	selected all field attribute.
					*
					*/
					rowClickCss: function( css ){
						$scope.defaultRowCss = css;
					}
				}
			},
			scope: {				
				instance: '=?instance',
			},
		};
	});

	// app.controller("SbFooterController", function ($scope, $location) {		
        

	// });	
});