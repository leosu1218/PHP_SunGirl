/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbPagination/view.html'], function (angular, app, view) {

	app.directive("sbPagination", function () {
		return {
			restrict: "E",			
			template: view,
			controller:  function($scope) {

				$scope.length 				= 5;
				$scope.recordCount 			= 0;
				$scope.recordStart 			= 0;
				$scope.recordEnd 			= 0;
				$scope.showPreviousChapter 	= true;
				$scope.showNextChapter 		= true;
				$scope.pages 				= [];
				$scope.pageClickHandler 	= function(){};
				$scope.previousClickHandler = function(){};
				$scope.nextClickHandler 	= function(){};

				/**
				* Be used for general page data like 
				*			<< 11 12 13 [14] 15 16 17 >> or 
				*				   1 2 3 [4] 5 6 7 >> 
				*
				*/
				var generalGenerator = {
					getHeadLength: function() {
						return getHalfLength();
					},
					
					getFootLength: function() {					
						return getHalfLength();
					},

					canShowPrevious: function() {					
						return (($scope.pageNo - 1)  > getHalfLength());						
					},

					canShowNext: function() {
						return true;
					}
				}

				/**
				*	Be used for litle page that is total page less than pagination's length.
				*				   1 2 3 [4] 5 6 7  (total page 7, length 10)
				*
				*/
				var litleDataGenerator = {

					getOffset: function() {
						return ((getHalfLength() + 1) - $scope.pageNo);
					},

					getHeadLength: function() {
						return ($scope.pageNo - 1);
					},
					
					getFootLength: function() {										
						return ($scope.totalPage - $scope.pageNo);
					},

					canShowPrevious: function() {
						return false;
					},

					canShowNext: function() {
						return false;						
					}
				}

				/**
				*	Be used for pageNo neer by length head index.
				*				   1 2 3 [4] 5 6 7  8 9 10 >> (total page 20, length 10, number 4)
				*
				*/
				var headGenerator = {

					getOffset: function() {
						return ((getHalfLength() + 1) - $scope.pageNo);
					},

					getHeadLength: function() {
						return (getHalfLength() - this.getOffset());
					},
					
					getFootLength: function() {										
						return (getHalfLength() + this.getOffset());
					},

					canShowPrevious: function() {
						return false;
					},

					canShowNext: function() {						
						return true;
					}
				}

				/**
				*	Be used for pageNo neer by length foot index.
				*				   << 11 12 13 14 15 16 17 [18] 19 20  (total page 20, length 10, number 18)
				*
				*/
				var footGenerator = {
					getOffset: function() {
						return ((getHalfLength() + $scope.pageNo) - $scope.totalPage);
					},

					getHeadLength: function() {						
						return (getHalfLength() + this.getOffset());
					},
					
					getFootLength: function() {												
						return (getHalfLength() - this.getOffset());
					},

					canShowPrevious: function() {						
						return true;				
					},

					canShowNext: function() {
						return false;
					}
				}

				function getHalfLength() {	
					if($scope.totalPage < $scope.length) {	
						if(isEven($scope.totalPage)) {
							return ($scope.totalPage / 2) - 1;
						}
						else {
							return ($scope.totalPage - 1) / 2;
						}
					} 
					else {
						return ($scope.length - 1) / 2;	
					}					
				}

				/**
				*	Check number is even
				*
				*	@param n int The number want to check.
				*	@return bool Return when number is even.s
				*/
				function isEven(n) {
				  	return n == parseFloat(n)? !(n%2) : void 0;
				}

				/**
				*	Create pagination ui generator by simple factory.
				*	There will create by page info. 
				*
				*	@params pageInfo json {
				*				recordCount: int,
				*				totalPage: int,
				*				pageSize: int,
				*				pageNo: int
				*			}
				*/
				function createGenerator(pageInfo) {

					var halfLength = getHalfLength();
					if($scope.totalPage <= $scope.length) {
						return litleDataGenerator;
					}
					else if(pageInfo.pageNo <= halfLength) {
						return headGenerator;
					}
					else if((pageInfo.pageNo + halfLength) >= pageInfo.totalPage) {
						return footGenerator;
					}
					else {
						return generalGenerator;	
					}			
				}

				$scope.outerInstance = {
					
					/**
					*	load a new pages info, would change ui.
					*
					*	@params pageInfo json {
					*				recordCount: int,
					*				totalPage: int,
					*				pageSize: int,
					*				pageNo: int
					*			}
					*/
					load: function(pageInfo) {
						
						$scope.pageNo 		= pageInfo.pageNo;
						$scope.totalPage 	= pageInfo.totalPage;
						$scope.recordCount 	= pageInfo.recordCount;

						var generator 		= createGenerator(pageInfo);					
						var pageNo 			= pageInfo.pageNo;					
						var pages 			= [];
						var i 				= 0;
						var headLength 		= generator.getHeadLength();
						var footLength 		= generator.getFootLength();

						for(i = headLength; i > 0; i--) {
							pages.push({
								number: (pageNo - i)
							})
						}

						pages.push({
							number: pageNo,
							active: "active"
						})

						for(i = 1; i <= footLength; i++) {
							pages.push({
								number: (pageNo + i)
							})	
						}

						$scope.pages 				= pages;
						$scope.showPreviousChapter 	= generator.canShowPrevious();
						$scope.showNextChapter 		= generator.canShowNext();	
						$scope.recordStart 			= ((pageInfo.pageNo - 1) * pageInfo.pageSize) + 1;
						$scope.recordEnd 			= (pageInfo.pageNo * pageInfo.pageSize);
						$scope.recordEnd 			= ($scope.pageNo < pageInfo.totalPage) ? $scope.recordEnd : pageInfo.recordCount;
					},

					/**
					*	Set pagination's display length.
					*
					*	@params number int The display length, should be odd.
					*/
					setLength: function(number) {
						if( isEven(number) ) {
							throw "Invalid number for pagination set length. Number should be odd.";
						}
						else {
							$scope.length = number;
						}
					},

					/**
					*	Set on page click event handler.
					*
					*/
					onPageClick: function(handler) {
						$scope.pageClickHandler = handler;
					},

					onPreviousClick: function(handler) {
						$scope.previousClickHandler = handler;
					},

					onNextClick: function(handler) {
						$scope.nextClickHandler = handler;
					},	
				}
			},
			scope: {								
				outerInstance: "=?instance",				
			},
		};
	});

	// app.controller("SbFooterController", function ($scope, $location) {		
        

	// });	
});