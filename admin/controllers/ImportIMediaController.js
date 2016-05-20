/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

    return app.controller("ImportIMediaController", function ($scope,$http) {

        $scope.url = '/api/rawfile/imediaevent/list';
        $scope.pageNo = 1;
        $scope.pageSize = 10;

        /**
         * User click clear all data button.
         */
        $scope.clearAll = function() {
            var message     = "刪除後將無法復原，您確定要清除所有資料嗎? ";
            var title       = "請再次確認此向操作";
            var buttonText1 = "確定";
            var buttonText2 = "取消";
            var callback2   = function() {};

            var callback1 = function() {
                rawFileRemoveAll()
            };

            $scope.alert.confirm(message, callback1, title, buttonText1, callback2, buttonText2);
        };


        /**
         * On user selected upload files.
         */
        $scope.$watch("upload", function(upload) {
            if(upload) {
                upload.api( "/api/rawfile/imediaevent" );
                upload.label("選擇要新增的CSV檔案");
                upload.mutiple(true);
                upload.success(function(data, status, headers, config){
                    //location.reload();
                    loadTable();
                });
                upload.fail(function(data, status, headers, config) {
                    $scope.alert.show("您上傳的檔案中包含曾經上傳過的檔案，或是不允許的檔案格式，系統將自動忽略這些檔案，並確保正常檔案匯入。");
                });
            }
        });

        /**
         * On user delete a file.
         * @param id
         * @param row
         */
        function rawFileRemoveById(id,row){
            var url = configs.api.rawfile + '/' + id;
            var request = {
                method: 'DELETE',
                url: url,
                headers: configs.api.headers
            };
            $http(request).success(function(data, status, headers, config) {
                var records = $scope.table.getField();
                var index = records.indexOf(row);
                records.splice(index, 1);

            }).error(function(data, status, headers, config){               
                $scope.alert.show("刪除檔案失敗");
            });
        }

        /**
         * Remove all raw file by api.
         */
        function rawFileRemoveAll(){
            var url = configs.api.rawfile + '/self-all';
            var request = {
                method: 'DELETE',
                url: url,
                headers: configs.api.headers
            };
            $http(request).success(function(data, status, headers, config) {
                loadTable();
            }).error(function(data, status, headers, config){
                $scope.alert.show("刪除檔案失敗");
            });
        }

        /**
         * Load raw file list.
         */
        function loadTable() {
            $scope.table.loadByUrl($scope.url, $scope.pageNo, $scope.pageSize,
                function(data, status, headers, config) {
                    // Handle reload table success;
                },
                function(data, status, headers, config) {
                    if(status != 404) {
                        $scope.alert.show("取得資料失敗");
                    }
                }
            );
        }

        $scope.$watch("table", function(table) {
            if(table) {
                //main table for admin to using.
                table.configField([
                    {attribute: "id", name: "ID"},
                    {attribute: "name", name: "檔名"},
                    {attribute: "create_datetime", name:"建立時間"},
                    {               
                        attribute:"control", 
                        name: "控制",
                        controls: [
                            {
                                type: "button",
                                icon: "fa-trash-o",
                                click: function(row, attribute) {
                                    
                                    var msg = "是否刪除檔案?" ;
                                    var buttonText1 = "確定刪除" ;
                                    var callback1 = function(){
                                        rawFileRemoveById(row.id,row);
                                    }
                                    var title = "刪除檔案";
                                    var buttonText2 = "取消";
                                    var callback2 = function(){

                                    };
                                    $scope.alert.confirm (msg, callback1, title, buttonText1, callback2, buttonText2);
                                }
                            },
                            
                        ]
                    },
                ]);

                loadTable();

                //table.loadByUrl($scope.url, $scope.pageNo, $scope.pageSize,
                //    function(data, status, headers, config) {
                //        // Handle reload table success;
                //    },
                //    function(data, status, headers, config) {
                //        if(status != 404) {
                //            $scope.alert.show("取得資料失敗");
                //        }
                //    }
                //);
            }
        });

    });
});