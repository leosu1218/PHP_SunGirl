/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("SungirlbbDownloadListController", createController(function ( $scope , $routeParams, $http, $timeout ) {

            $scope.pageSize = 10;
            $scope.search = {};
            $scope.search.keyword = null;
            $scope.search.order = "DESC";
            $scope.enableSelect = $scope.enableSelect || false;
            $scope.api = configs.api.sungirlDownload + "/list";


            /**
             * Reload list
             */
            $scope.reloadList = function() {
                $scope.table.loadByUrl( $scope.api, 1, $scope.pageSize,
                    function(data, status, headers, config) {
                        // Handle reload table success;
                    },
                    function(data, status, headers, config) {
                        $scope.alert.show("無法搜尋到資料");
                    },
                    $scope.search
                );
            };

            //table
            $timeout(function(){

                //main table for admin to using.
                $scope.table.configField([
                    {attribute: "id",               name: "ID"},
                    {attribute: "title",            name: "標題"},
                    {attribute: "banner_name",       name: "主圖",     htmlFilter:displayCoverPhoto},
                    {attribute: "ready_time",        name: "上架時間"},
                    {attribute: "create_time",     name: "建立時間"},
                    {attribute: "control",          name: "控制",
                        controls: [
                            {type: "button", icon: "fa-search", click: viewDetail },
                            {type: "button", icon: "fa-times", click: removePhoto }
                        ]
                    }
                ]);

                $scope.reloadList();
                $scope.table.rowClickCss({'background-color':'#FFDDAA'});
                $scope.table.onRowClick(function(row, field, instance) {
                    if($scope.enableSelect) {
                        if(field != 'control') {
                            instance.selected();
                        }
                    }
                });
                $scope.instance = $scope.table;
            }, 100);


            /**
             * Display cover photo field.
             *
             * @param value
             * @param row
             * @returns {string}
             */
            function displayCoverPhoto(value, row) {
                return '<img src="' + configs.path.upload + 'photo/' + value + '"  height="50" />';
            }

            /**
             * view the item's details.
             */
            function viewDetail(row, value) {
                location.href = "#!/videoList/view/"+row.id;
            }

            function removePhoto(row, value) {
                var api = configs.api.sungirl +"/delete/" + row.id;
                var request = {
                    method: 'DELETE',
                    url: api,
                    headers: configs.api.headers
                };

                $http(request).success(function(data, status, headers, config) {
                    $scope.reloadList();
                }).error(function(data, status, headers, config){
                    $scope.alert.show("圖片刪除有誤，請再次嘗試。");
                });
            }

		})
	);
	
});