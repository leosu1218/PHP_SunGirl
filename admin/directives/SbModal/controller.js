 
/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	/**
	*	Sb Smart Table Directive directive.
	*
	*
	*/
	app.directive("sbModal", function () {
		return {
			restrict: "E",
			templateUrl: app.applicationPath + "/directives/SbModal/view.html",
			controller:  function($scope, $http, $timeout) {

                $scope.id = makeid();

                function makeid() {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for( var i=0; i < 5; i++ )
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    return text;
                }

                //default
				$scope.modalName = '#' + $scope.id +  '-sbModal';

				var defaultSetting = function(){
					$scope.isShow = false;
					$scope.headerText = [];
					$scope.footerButton = [];

					$scope.bodyTextarea = [];
					$scope.bodyText = []; 
					$scope.bodyInput = [];
					$scope.bodySelect = [];
					$scope.bodyInputNumber = [];
					$scope.bodyDate = [];
					$scope.bodyTime = [];

					$scope.bodyUpload = [];
					$scope.bodyImage = [];

					$scope.uploadIsShow = false;

					$($scope.modalName).on('hidden.bs.modal', function () {
					    $scope.isShow = false;
					})
					$($scope.modalName).on('shown.bs.modal', function () {
					    $scope.isShow = true;
					})

				};

				defaultSetting();

				//render doing something
				$scope.renders = {
					header:function( config ){
						if( config.type == "text" ){
							$scope.headerText.push({ text:config.label });
						}
					},
					body:function( config ){
						if( config.type == "input" )
						{
							$scope.bodyInput.push({ attributeName:config.attributeName, type:"text", label:config.label, attribute:(config.attribute||"") });
						}
						if( config.type == "textarea" )
						{
							$scope.bodyTextarea.push({ attributeName:config.attributeName, label:config.label, attribute:(config.attribute||"") });
						}
						if( config.type == "select" )
						{
							var attributeObjetct = config.attribute;
							attributeObjetct["click"] = function( item, index ){
								for( var key in $scope.bodySelect ){
									if( $scope.bodySelect[key].attributeName == config.attributeName )
									{
										$scope.bodySelect[key].attribute.selected = item;
									}
								}
							};
							$scope.bodySelect.push({
								attributeName: 	config.attributeName, 
								label: 			config.label, 
								attribute: 		attributeObjetct
							});
						}
						if( config.type == "number" )
						{
							$scope.bodyInputNumber.push({ attributeName:config.attributeName, type:"number", min:config.min, label:config.label, attribute:(config.attribute*1||0) });
						}
						if( config.type == "date" )
						{
							$scope.bodyDate.push({ attributeName:config.attributeName, type:"date", label:config.label, attribute:(config.attribute||"") });
						}
						if( config.type == "time" )
						{
							$scope.bodyTime.push({ attributeName:config.attributeName, type:"time", label:config.label, attribute:(config.attribute||"") });
						}
						if( config.type == "text" )
						{
							$scope.bodyText.push({ attributeName:config.attributeName, attribute:config.attribute, label:config.label });
						}
						if( config.type == "upload" )
						{
							config.exe({
								api: function( url ){
									$scope.upload.api(url);
								},
								label: function( text ){
									$scope.upload.label( text );
								},
								success:function( callback ){
									$scope.upload.success(function(data, status, headers, config){
										$scope.bodyImage = [{attribute:"upload/image/"+data.file.fileName}];
										callback(data, status, headers, config);
									});
								}
							});
							$scope.uploadIsShow = true;
						}

					},
					footer:function( config ){
						if( config.type == "button" ){
							$scope.footerButton.push({ 
								label:config.label, 
								target:function(){
									config.target( result() );
									defaultSetting();
									$($scope.modalName).modal('hide');
								}
							});
						}
					}
				};

				var renderFlow = function(){
					defaultSetting();
					var controls = $scope.configs.controls;
					for(var index in controls){
						$scope.renders[ controls[index].position ]( controls[index] );
					}
				};

				var result = function(){
					var result = {};
					for(var index in $scope.bodyInput){
						result[ $scope.bodyInput[index].attributeName ] = $scope.bodyInput[index].attribute;
					}
					for(var index in $scope.bodyTextarea){						
						result[ $scope.bodyTextarea[index].attributeName ] = $scope.bodyTextarea[index].attribute;
					}
					for(var index in $scope.bodySelect){
						result[ $scope.bodySelect[index].attributeName ] = $scope.bodySelect[index].attribute.selected;
					}
					for(var index in $scope.bodyInputNumber){
						result[ $scope.bodyInputNumber[index].attributeName ] = $scope.bodyInputNumber[index].attribute;
					}
					for(var index in $scope.bodyDate){
						result[ $scope.bodyDate[index].attributeName ] = $scope.bodyDate[index].attribute;
					}
					for(var index in $scope.bodyTime){
						result[ $scope.bodyTime[index].attributeName ] = $scope.bodyTime[index].attribute;
					}
					return result;
				};

				$scope.instance = {
					/**
					*	@param json {
					*		controls:[
					*			{
					*				position:"header",
					*				label:"修改欄位",
					*				type:"text",
					*			},
					*			{
					*				position:"body"
					*				attributeName:"note",
					*				type:"input",
					*				label:"備註"
					*			},
					*			{
					*				position:"footer",
					*				type:"button",
					*				label:"確定",
					*				target:function( item ){
					*					row.note = item.note;
					*				}
					*			}
					*		]
					*	}
					*
					*/
					config:function( configs ){
						$scope.configs = configs;
						renderFlow();
					},
					show:function(){
						$($scope.modalName).modal('show');
						$scope.isShow = true;
					},
					hide:function(){
						$($scope.modalName).modal('hide');
						$scope.isShow = false;
					},
					isShow:function(){
						return $scope.isShow;
					}
				};

			},
			scope: {				
				instance: '=?instance',
			},
		};
	});
});