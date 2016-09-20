/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], 
	function (angular, app, createController, configs) {

	return app.controller("SungirlDownloadController",
		createController(function ($scope, $timeout, $http, $location ,$routeParams) {
            $scope.productCoverImage = [];

            var url = configs.api.sungirl + "/download/getByid/" + $routeParams.id ;
            var req = {
                method: 'GET',
                url: url,
                headers: configs.api.headers
            };
            $http(req).success(function(data, status, headers, config) {
                $scope.title = data.title;
                $scope.home_state = data.home_state;
                $scope.ready_time.setdate(data.ready_time);
                $scope.productCoverImage.push({'fileName' : data.banner_name});
                $scope.pcImg1.push({'fileName' : data.pc_img1});
                $scope.pcImg2.push({'fileName' : data.pc_img2});
                $scope.pcImg3.push({'fileName' : data.pc_img3});
                $scope.pcImg4.push({'fileName' : data.pc_img4});
                $scope.mobileImg1.push({'fileName' : data.mobile_img1});
                $scope.mobileImg2.push({'fileName' : data.mobile_img2});
                $scope.mobileImg3.push({'fileName' : data.mobile_img3});
                $scope.mobileImg4.push({'fileName' : data.mobile_img4});

            }).error(function(data, status, headers, config) {
                alert("找不到資料");
            });

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
                $scope.pcImg1 = [];
                $scope.pcImg2 = [];
                $scope.pcImg3 = [];
                $scope.pcImg4 = [];
                $scope.mobileImg1 = [];
                $scope.mobileImg2 = [];
                $scope.mobileImg3 = [];
                $scope.mobileImg4 = [];
            };

			$scope.cancel();


            $scope.deleteImage = function( file, type ){
                var files = file.fileName.split(".");
                var url = configs.api.sungirl + "/download/deleteImg/" + files[0] + "/" + files[1];
                var req = {
                    method: 'DELETE',
                    url: url,
                    headers: configs.api.headers
                };
                $http(req).success(function(serverResult) {
                    $scope[type] = [];
                }).error(function(error) {
                    alert("Delete product image error");
                });

            };

            $timeout(function(){
                UploadInstanceSetting(
                    "productCoverImageUpload",
                    configs.api.photoUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.productCoverImage = [];
                        var file = data.file;
                        $scope.productCoverImage.push(file);
                    }
                );

                UploadInstanceSetting(
                    "pcImg1Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.pcImg1 = [];
                        var file = data.file;
                        $scope.pcImg1.push(file);
                    }
                );

                UploadInstanceSetting(
                    "pcImg2Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.pcImg2 = [];
                        var file = data.file;
                        $scope.pcImg2.push(file);
                    }
                );

                UploadInstanceSetting(
                    "pcImg3Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.pcImg3 = [];
                        var file = data.file;
                        $scope.pcImg3.push(file);
                    }
                );

                UploadInstanceSetting(
                    "pcImg4Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.pcImg4 = [];
                        var file = data.file;
                        $scope.pcImg4.push(file);
                    }
                );

                UploadInstanceSetting(
                    "mobileImg1Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.mobileImg1 = [];
                        var file = data.file;
                        $scope.mobileImg1.push(file);
                    }
                );

                UploadInstanceSetting(
                    "mobileImg2Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.mobileImg2 = [];
                        var file = data.file;
                        $scope.mobileImg2.push(file);
                    }
                );

                UploadInstanceSetting(
                    "mobileImg3Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.mobileImg3 = [];
                        var file = data.file;
                        $scope.mobileImg3.push(file);
                    }
                );

                UploadInstanceSetting(
                    "mobileImg4Upload",
                    configs.api.downloadUpload,
                    "上傳",
                    false,
                    function(data, status, headers, config){
                        $scope.mobileImg4 = [];
                        var file = data.file;
                        $scope.mobileImg4.push(file);
                    }
                );

            },200);

            function IsFillInForm(){

                if( $scope.title == "" )
                {
                    return { isOk:false, msg:"影音標題" };
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


			function sungirl_update( formData ){
				var url = configs.api.sungirl + "/update/download/" + $routeParams.id;
				var req = {
				    method: 'PUT',
				    url: url,
				    headers: configs.api.headers,
				    data: formData
				};
				$http(req).success(function(result) {
					location.href = "#!/downloadList";
				}).error(function(error) {
					Message("建立相簿發生問題請重新嘗試");
				});
			}

			function getDataForm(){
				var formData = {
                    title 			: 	$scope.title,
                    banner_name  	: 	$scope.productCoverImage[0].fileName,
                    pc_img1         :   $scope.pcImg1[0].fileName,
                    pc_img2         :   $scope.pcImg2[0].fileName,
                    pc_img3         :   $scope.pcImg3[0].fileName,
                    pc_img4         :   $scope.pcImg4[0].fileName,
                    mobile_img1     :   $scope.mobileImg1[0].fileName,
                    mobile_img2     :   $scope.mobileImg2[0].fileName,
                    mobile_img3     :   $scope.mobileImg3[0].fileName,
                    mobile_img4     :   $scope.mobileImg4[0].fileName,
                    ready_time      : $scope.ready_time.getdate()
				};
				return formData;
			}



			//create flow
			$scope.update = function(){
				var result = IsFillInForm();
				
				if( result.isOk )
				{
					var formData = getDataForm();
                    sungirl_update(formData);
				}
				else
				{
					var text = "請確認 ["+ result.msg +"] 來完成表單!";
					Message( text );
				}
			};

	}));
	
});