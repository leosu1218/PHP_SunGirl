/*global define*/
'use strict';

define([
    'angular',
    'app',
    'configs',
    'text!directives/AdminImageList/view.html'
], function (
    angular,
    app,
    configs,
    view)
{
    app.directive("adminImageList", function () {
        return {
            restrict: "E",
            template: view,
            controller: function ($scope, $http, $timeout) {
                $scope.pageSize = 10;

                $scope.$watch("table", function(table) {
                    if(table) {
                        $scope.table.configField([
                            {attribute: "filename",       name: "圖片",     htmlFilter:displayCoverPhoto},
                            {attribute: "control",          name: "控制",
                                controls: [
                                    {type: "button", icon: "fa-times", click: removePhoto }
                                ]
                            }
                        ]);
                        onload();
                    }
                });

                $scope.$watch("homePageUpload", function(homePageUpload) {
                    if(homePageUpload) {
                        homePageUpload.api($scope.uploadApi);
                        homePageUpload.label("選擇要新增的圖片");
                        homePageUpload.mutiple(true);
                        homePageUpload.success(function(data, status, headers, config){
                            onload();
                        });
                        homePageUpload.fail(function(data, status, headers, config) {
                            $scope.alert.show("您上傳的檔案中包含曾經上傳過的檔案，或是不允許的檔案格式，系統將自動忽略這些檔案，並確保正常檔案匯入。");
                        });
                    }
                });

                /**
                 * Display cover photo field.
                 *
                 * @param value
                 * @param row
                 * @returns {string}
                 */
                function displayCoverPhoto(value, row) {
                    $scope.imgUrl = row.image_url;
                    $scope.imgListId = row.id;
                    $scope.getImgUrl = $scope.getImgApi;
                    return '<img src="' + configs.path.upload + $scope.imagePath + "/"  + value + '"  height="200" />'
                    +'<url-input instance="urlmodel" bannerid="'+ $scope.imgListId +'" bannerimg="\'' + $scope.imgUrl + '\'" get-img-api="\''+ $scope.getImgUrl +'\'"></url-input>';
                }

                /**
                 * remove photo field.
                 * @param row
                 * @param value
                 */
                function removePhoto(row, value) {
                    var api = $scope.deleteApi + row.id;
                    var request = {
                        method: 'DELETE',
                        url: api,
                        headers: configs.api.headers
                    };

                    $http(request).success(function(data, status, headers, config) {
                        onload();
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("圖片刪除有誤，請再次嘗試。");
                    });
                }

                function onload(){
                    $scope.table.loadByUrl( $scope.getListApi, 1, $scope.pageSize,
                        function(data, status, headers, config) {
                        },
                        function(data, status, headers, config) {
                            $scope.alert.show("無法搜尋到資料");
                        }
                    );
                }

            },
            scope: {
                uploadApi: '=uploadApi',
                deleteApi: '=deleteApi',
                getListApi: '=getListApi',
                imagePath: '=imagePath',
                getImgApi: '=getImgApi'
            }
        }
    });
});