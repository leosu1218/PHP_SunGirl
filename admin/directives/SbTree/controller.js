/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbTree/view.html'], 
function (angular, app, view) {

	app.directive("sbTree", function () {
		return {
			restrict: "E",			
			template: view,
			controller: function ($scope, $http) {

				$scope.onSelected = function(){};

				$scope.toggle = function (scope) {
					scope.toggle();
				};

				$scope.selected = function (node) {
					$scope.onSelected(node);
				};

				$scope.treeData = [];
				var tree = [];
				function traversalIsChildHas( nodes, inputNode ){
					var isInput = false;
					for( var key in nodes ){
						if( nodes[key]['id'] == inputNode['parent_group_id'] ){
							nodes[key]['nodes'] = [];
							nodes[key]['nodes'].push(inputNode);
							isInput = true;
						}
						if( !isInput && nodes[key]['nodes'].length!=0 ){
							isInput = traversalIsChildHas(nodes[key]['nodes'], inputNode);
						}
					}
					return isInput;
				}

				function traversalOutputTree( inputNode ){
					var isInput = false;
					for(var index in tree){
						
						if( !isInput && tree[index]['id'] == inputNode['parent_group_id'] ){
							inputNode['nodes'] = [];
							tree[index]['nodes'].push(inputNode);
							isInput = true;
						}

						if( !isInput && tree[index]['nodes'].length!=0 ){
							isInput = traversalIsChildHas(tree[index]['nodes'], inputNode);
						}
					}
					return isInput;
				}

				function recordsInputTree( records ){

					for(var index in records){

						if(!traversalOutputTree( records[index] ))
						{
							var newNode = records[index];
							newNode['nodes'] = [];
							tree.push( newNode );
						}

					}
					return tree;

				}

				$scope.loadFromData = function(data) {
					$scope.treeData = recordsInputTree( data.records );
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
					getTree:function(){
						return $scope.treeData;
					},
					onSelected:function( handler ){
						$scope.onSelected = handler;
					}
				};

		    },
			scope: {				
				instance: '=?instance',
			},
		};
	});
});