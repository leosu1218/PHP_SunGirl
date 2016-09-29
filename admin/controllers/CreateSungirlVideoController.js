/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], 
	function (angular, app, createController, configs) {

	return app.controller("CreateSungirlVideoController",
		createController(function ($scope, $timeout, $http, $location) {


			//message tool
			function Message( msg )
			{
				$scope.message = {
        			isShow : true,
        			text : msg
		    	};	
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

			$scope.cancel = function(){
				$scope.title = "";
				$scope.productCoverImage = [];
				$scope.productImages = [];
				$scope.youtube_url = "";
                $scope.home_state = '0';

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


			$scope.deleteImage = function( file, type ){
                   var files = file.fileName.split(".");
					var url = configs.api.sungirl + "/photo/delete/" + files[0] + "/" + files[1];
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

			};



			$timeout(function(){
				var cover_image_api = configs.api.photoUpload;
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

			},200);

			function IsFillInForm(){

				if( $scope.title == "" )
				{
					return { isOk:false, msg:"商品名稱" };
				}

                if(!$scope.ready_time.getdate()) {
                    throw {message: "上架日期"};
                }

				if( $scope.productCoverImageUpload.length == 0 )
				{
					return { isOk:false, msg:"影音主圖  照片" };
				}
				return { isOk:true };
			}


			function sungirl_create( formData ){
				var url = configs.api.sungirl + "/video/create";
				var req = {
				    method: 'POST',
				    url: url,
				    headers: configs.api.headers,
				    data: formData
				};
				$http(req).success(function(result) {
					location.href = "#!/videoList";
				}).error(function(error) {
					Message("建立相簿發生問題請重新嘗試");
				});
			}

			function getDataForm(){
				var formData = {
                    title 			: 	$scope.title,
                    banner_name  	: 	$scope.productCoverImage[0].fileName,
                    video_url      :   $scope.video,
                    home_state : $scope.home_state,
                    ready_time: $scope.ready_time.getdate(),
                    click_sum : $scope.click_sum
				};
				return formData;
			}

			//create flow
			$scope.create = function(){

				var result = IsFillInForm();
				
				if( result.isOk )
				{
					var formData = getDataForm();
					sungirl_create(formData);
				}
				else
				{
					var text = "請確認 ["+ result.msg +"] 來完成表單!";
					Message( text );
				}

			};

	}));
	
});