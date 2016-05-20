/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/SbUpload/view.html',
    'ngFileUpload',
    'ngFileUploadShim'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("sbUpload", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, Upload, $timeout, $interval, $http) {
                $scope.files 		= [];
                $scope.isMutiple 	= false;
                $scope.uploadApi = "";
                $scope.acceptType = "*/*";

                $scope.upload_label = {
                    single:{
                        text:"Click to upload"
                    },
                    mutiple:{
                        text:"Drop or click to upload",
                        not_supported:"File Drag/Drop is not supported for this browser"
                    }
                };

                $scope.upload = function( files ){
                    if( $scope.uploadApi != "" )
                    {
                        var UploadStart = Upload.upload;
                        for(var index in files)
                        {
                            UploadStart({
                                url: $scope.uploadApi,
                                file: files[index]
                            }).progress(function(evt) {
                                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                                $scope.uploadProgress( progressPercentage );
                            }).success(function(data, status, headers, config) {
                                $timeout(function() {
                                    $scope.uploadSuccess(data, status, headers, config);
                                },200);
                            }).error(function(error, status, headers, config){
                                $scope.uploadFail(error);
                            });
                        }
                    }
                    else
                    {
                        var error = "Upload api is not define."
                        $scope.uploadFail( error );
                    }
                };

                $scope.uploadSuccess = function(data, status, headers, config){
                    //console.log("Upload success, result:",data);
                };

                $scope.uploadProgress = function( progressPercentage ){
                    //console.log("Upload files progress percentage:",progressPercentage);
                };

                $scope.uploadFail = function(error){
                    alert("Upload file fail.");
                    //console.log("Upload file fail.",error, status, headers, config);
                };

                $scope.$watch('file', function ( files ) {
                    try {
                        if( files.length>0 ) {
                            $scope.upload( files );
                        }

                    }
                    catch(error) {
                        // console.log(error);
                    }
                });

                $scope.instance = {
                    label: function( text ) {
                        $scope.upload_label.mutiple.text = text;
                        $scope.upload_label.single.text = text;
                    },
                    api: function( url ) {
                        $scope.uploadApi = url;
                    },
                    mutiple: function( isMutiple ){
                        $scope.isMutiple = isMutiple;
                    },
                    acceptType: function( type ){
                        $scope.acceptType = type;
                    },
                    success: function( successFunction ){
                        $scope.uploadSuccess = successFunction;
                    },
                    fail: function( failFunction ){
                        $scope.uploadFail = failFunction;
                    }
                };
            },
            scope: {
                instance: '=?instance',
            }
        }
    });
});