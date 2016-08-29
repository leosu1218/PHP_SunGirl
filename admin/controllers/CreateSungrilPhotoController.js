/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], 
	function (angular, app, createController, configs) {

	return app.controller("CreateSungrilPhotoController",
		createController(function ($scope, $timeout, $http, $location) {	
			//message tool
			function Message( msg )
			{
				$scope.message = {
        			isShow : true,
        			text : msg
		    	};	
			}

			//table tools
			function TableLoad( table_instance, records, pageNo, pageSize )
        	{
 				var count  = records.length;

 				$scope[table_instance].load({
					records: records,
					recordCount: (count||0),
					totalPage: Math.ceil((count||0)/pageSize),
					pageNo: pageNo,
					pageSize: pageSize,
				});
        	}

        	//upload instance setting tool
        	function UploadInstanceSetting( upload_instance, api, label, isMutiple, successCallback )
        	{
        		$scope[ upload_instance ].api( api );
				$scope[ upload_instance ].label(label);
				$scope[ upload_instance ].mutiple(isMutiple);
				$scope[ upload_instance ].success(function(data, status, headers, config){
					successCallback(data, status, headers, config);
				});
        	}

        	/**
			*	Format datetime object to string (Y:m:d H:i:s)
			*
			*/
			function DateTimeFormat (date, time)
			{
				time = time || date;
			  	var year = "" + date.getFullYear();
			  	var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
			  	var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
			  	var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
			  	var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
			  	var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
			  	return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
			}

			$scope.$watch("productGroupTree", function(tree){
				if(tree){
					tree.bindMutipleSelectedApplyHandler(function(node){
						return false;
					});
					tree.bindSingleSelectedApplyHandler(function(node){
						if( node.type && node.type.value == 2 ){
							return true;
						}
						return false;
					});
					tree.isApplyAppend(false);
					tree.isApplyDelete(false);
				}
			});

			$scope.cancel = function(){
				$scope.name = "";

				$scope.wholesale_price = 0;
				$scope.suggested_price = 0;
				$scope.lowest_end_price = 0;
				$scope.cost_price = 0;
				$scope.propose_price = 0;

				$scope.tag = "";
				$scope.weight = 0;
				$scope.productLength = 0;
				$scope.productWidth = 0;
				$scope.productHeight = 0;

				$scope.cuftNumber = 0.0000353;
				$scope.productCubicFeet = $scope.productLength * $scope.productWidth * $scope.productHeight * $scope.cuftNumber;
				$scope.CBMNumber = 35.315;
				$scope.productCubicMeter = ($scope.productCubicFeet/$scope.CBMNumber);

				//spec data in specTable
				$scope.spec_records = [];

				$scope.productCoverImage = [];
				$scope.productImages = [];
				$scope.productExplainImages = [];

				$scope.product_text = "";

				$scope.youtube_url = "";
				$scope.explain_text = "";

				$scope.is_open_groupbuying = false;
				$scope.groupbuying_maxmun_day = 999999;
				$scope.groupbuying_minimun_day = 0;

			};

			$scope.cancel();

        	function deleteImageUi( file, type ){
				if(type=="productCoverImage"){
					$scope.productCoverImage = [];
				}else{
					var index = $scope[type].indexOf(file);
					$scope[ type ].splice(index, 1);
				}
			}

			function getSourceText( type ){
				if(type=="productImages"){
					return "product";
				}else if(type=="productExplainImages"){
					return "explain";
				}else{
					return "undefined";
				}
			}

			$scope.deleteImage = function( file, type ){
				if( type=="productCoverImage" ){
					deleteImageUi( file, type );
				}else{
					var source = getSourceText(type);
					var filetype = file.type.split("/");
					var url = configs.api.product + "/materials/wholesale/" + source + "/" + filetype[0] + "/" + file.fileName + "/" + filetype[1];
					var req = {
					    method: 'DELETE',
					    url: url,
					    headers: configs.api.headers
					};
					$http(req).success(function(serverResult) {
						deleteImageUi( file, type );
					}).error(function(error) {
					    alert("Delete product image error");
					});
				}
			};

			function SpecSave(  data ){
        		var isFillin = (data.name!=""&&data.serial!="");
				if( isFillin )
				{
					$scope.spec_records.push({
						name:data.name, 
						serial:data.serial, 
						can_sale_inventory:data.can_sale_inventory, 
						safe_inventory:data.safe_inventory
					});
					TableLoad(
	            		"specTable",
	            		$scope.spec_records,
	            		1,
	            		1000
	            	);
				}
				else
				{
					alert("請填完整的規格。");
				}
        	}

        	$scope.spec_add = function(){
        		$scope.modal.config({
					controls:[
						{
							position:"header",
							type:"text",
							label:"新增規格",
						},
						{
							position 		:"body",
							type 			:"input",
							attribute		:"",
							attributeName	:"serial",
							label			:"品號(鉅盛)"
						},
						{
							position 		:"body",
							type 			:"input",
							attribute		:"",
							attributeName	:"name",
							label 			:"規格名稱"
						},
						{
							position 		:"body",
							type 			:"number",
							attribute		:"",
							attributeName	:"can_sale_inventory",
							label 			:"庫存量"
						},
						{
							position 		:"body",
							type 			:"number",
							attribute		:"",
							attributeName	:"safe_inventory",
							label 			:"安全庫存"
						},
						{
							position:"footer",
							type:"button",
							label:"確定",
							target:function( data ){
								SpecSave(data);
							}
						}
					]
				});

				$scope.modal.show();
        	}

			$scope.spec_table_setting = function(){

				$scope.specTable.configField(
            		[
						{
							attribute:"serial",
							name:"品號(鉅盛)"
						},
						{
							attribute:"name", 
							name:"規格名稱"
						},
						{
							attribute:"can_sale_inventory",
							name:"庫存量"
						},
						{
							attribute:"safe_inventory",
							name:"安全庫存"
						},		
						{				
							attribute:"control", 
							name: "控制",
							controls: [
								{
									type: "button",
									icon: "fa-trash-o",
									click: function(row, attribute) {
										var index = $scope.spec_records.indexOf(row);
										$scope.spec_records.splice(index, 1);
										TableLoad(
						            		"specTable",
						            		$scope.spec_records,
						            		1,
						            		1000
						            	);
									}
								},
								
							]
						},
					]
				);

            	TableLoad(
            		"specTable",
            		$scope.spec_records,
            		1,
            		1000
            	);
				
			};

			$timeout(function(){

				$scope.getFareTable();
				$scope.productCoverImage = [];
				var cover_image_api = configs.api.materialUpload + "/wholesale";
				var cover_image_label = "上傳";
				var cover_image_isMutiple = false;
				UploadInstanceSetting(
					"productCoverImageUpload",
					cover_image_api,
					cover_image_label,
					cover_image_isMutiple,
					function(data, status, headers, config){
						$scope.productCoverImage = [];
						var file = data.file;
						$scope.productCoverImage.push(file);
					}
				);

				$scope.productImages = [];
				var image_api = configs.api.materialUpload + "/wholesale";
				var image_label = "上傳";
				var image_isMutiple = true;
				UploadInstanceSetting(
					"productImageUpload",
					image_api,
					image_label,
					image_isMutiple,
					function(data, status, headers, config){
						var file = data.file;
						file['style'] = "";
						$scope.productImages.push(file);
					}
				);

				$scope.spec_table_setting();
			},200);

			function IsFillInForm(){

				if( $scope.name == "" )
				{
					return { isOk:false, msg:"商品名稱" };
				}

				if( $scope.wholesale_price == 0 )
				{
					return { isOk:false, msg:"批發價" };
				}

				if( $scope.suggested_price == 0 )
				{
					return { isOk:false, msg:"定價" };
				}

				if( $scope.lowest_end_price == 0 )
				{
					return { isOk:false, msg:"最低售價" };
				}

				if( $scope.cost_price == 0 )
				{
					return { isOk:false, msg:"成本價" };
				}

				if( $scope.propose_price == 0 )
				{
					return { isOk:false, msg:"建議售價" };
				}

				if( $scope.tag == "" )
				{
					return { isOk:false, msg:"標籤" };
				}

				if( $scope.fareList.getField().length == 0 )
				{
					return { isOk:false, msg:"物流方案" };
				}

				if( $scope.spec_records.length == 0 )
				{
					return { isOk:false, msg:"產品規格" };	
				}

				if( $scope.product_text == "" )
				{
					return { isOk:false, msg:"商品描述 - 文字" };
				}

                if( $scope.sbEditor.getData().length == 0 )
                {
                    return { isOk:false, msg:"商品說明(次頁)" };
                }

				if( $scope.productCoverImageUpload.length == 0 )
				{
					return { isOk:false, msg:"商品封面 照片" };
				}

				if( $scope.isUseImage && $scope.productImages.length == 0 )
				{
					return { isOk:false, msg:"商品描述 照片" };	
				}

				if( !$scope.isUseImage && $scope.youtube_url == "" )
				{
					return { isOk:false, msg:"商品描述 youtube url" };	
				}


				if( !$scope.productGroupTree.getSingleSelectedId() )
				{
					return { isOk:false, msg:"商品群组" };
				}

				if( $scope.is_open_groupbuying )
				{
					if( $scope.groupbuying_maxmun_day == 0 || $scope.groupbuying_minimun_day == 0 ){
						return { isOk:false, msg:"團購主開團天數限制" };
					}
				}

				return { isOk:true };
			}

			function Delete_product( id ){
				var req = {
				    method: 'DELETE',
				    url: configs.api.product + "/wholesale/" + id,
				    headers: {
				        'Content-Type': 'application/json'
				    }
				};

				$http(req).success(function(result) {
					//do somthing
				}).error(function(error, status) {
					if( status == 409 )
					{
						alert("活動進行中無法刪除此產品.");
					}
					else
					{
						alert("刪除產品錯誤.");

					}
				});
			}


			function Product_create( formData ){
				var url = configs.api.product + "/wholesale";
				var req = {
				    method: 'POST',
				    url: url,
				    headers: configs.api.headers,
				    data: formData
				};
				$http(req).success(function(result) {
					location.href = "#!/product/wholesale/list/1/100";
				}).error(function(error) {
					Message("建立產品發生問題請重新嘗試");
				});
			}

			function GetIds( fields ){
				var result = [];
				for(var index in fields){
					if( fields[index]['global']==0 ){
						result.push(fields[index]["id"]);
					}
				}
				return result;
			}

			function getDataForm(){
				var formData = {
						name 				: 	$scope.name,
						
						wholesale_price  	: 	$scope.wholesale_price,
						end_price 			: 	$scope.lowest_end_price,
						suggest_price 		: 	$scope.suggested_price,
						cost_price  		: 	$scope.cost_price,
						propose_price 		: 	$scope.propose_price,

						tag 				: 	$scope.tag,
						weight 				: 	$scope.weight,
						product_length 		: 	$scope.productLength,
						product_width 		: 	$scope.productWidth,
						product_height 		: 	$scope.productHeight,

						detail 				: 	$scope.product_text,
						product_group_id	: 	$scope.productGroupTree.getSingleSelectedId(),
						explain_text 		: 	$scope.sbEditor.getData(),

						deliverys 				:   GetIds($scope.fareList.getField()),
						
						active_groupbuying 	: 	$scope.is_open_groupbuying,
						active_maximum		: 	$scope.groupbuying_maxmun_day,
						active_minimum		: 	$scope.groupbuying_minimun_day,

						spec 		 		:	$scope.spec_records,
						
						cover_photo_img  	: 	$scope.productCoverImage[0].fileName,
						explain_images 		: 	$scope.productExplainImages

				};
				for(var key in formData.deliverys){
					formData.deliverys[key] = $scope.returnId(formData.deliverys[key]);
				}

				if( $scope.isUseImage ){
					formData[ 'media_type' ] = '0';
					formData[ "product_images" ] = $scope.productImages;
					formData[ "youtube_url" ] = 'NULL';
				}else{
					formData[ 'media_type' ] = '1';
					formData[ "youtube_url" ] = $scope.youtube_url;
				}
				return formData;
			}

			$scope.returnId = function(value){
                var item = {};
                for(var key in $scope.fareItem) {                   
                    item = $scope.fareItem[key];
                    if(item.type == value) {
                        value = item.id;
                    }
                }
                return value;
            }

			$scope.getFareTable = function() {
                var api = configs.api.systemConfig +"/fare/list/1/100";
                var request = {
                    method: 'GET',
                    url: api,
                    headers: configs.api.headers
                };
                $http(request).success(function(data, status, headers, config) {
                    $scope.fareItem = data.records;
                }).error(function(data, status, headers, config){
                });
            };

			//create flow
			$scope.create = function(){

				var result = IsFillInForm();
				
				if( result.isOk )
				{
					var formData = getDataForm();
					Product_create(formData);
				}
				else
				{
					var text = "請確認 ["+ result.msg +"] 來完成表單!";
					Message( text );
				}

			};

	}));
	
});