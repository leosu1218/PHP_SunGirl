/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

    return app.controller("UserListController", function ($scope, $timeout) {

        $scope.$watch("table", function(table){
            if(table) {

                table.configField([
                    {attribute:"id",            name:"#"},
                    {attribute:"account",       name:"帳號"},
                    {attribute:"email",         name:"信箱"},
                    {attribute:"name",          name:"名字"},
                    {               
                        attribute:"control", 
                        name: "控制",
                        controls: [
                            {
                                type: "button",
                                icon: "fa-wrench",
                                click: function(row, attribute) {                                    
                                    var html    = "<change-password userid="+row.id+"></change-password>";
                                    var handler = function(){};
                                    var title = "修改自己的密碼";
                                    var buttonText = "關閉視窗";

                                    $scope.alert.showHtml(html, handler, title, buttonText);                                  
                                }
                            },
                            
                        ]
                    }
                ]);

                var  url = configs.api.user + "/list";
                table.loadByUrl(url, 1, 10,
                    function(data, status, headers, config) {
                        // Do nothings.
                    },
                    function(data, status, headers, config) {
                        // Do nothings.
                    }
                );
            }
        })

        $scope.create = function () {
            var html    = "<user-register-form></user-register-form>";
            var handler = function(){};
            var title = "新增使用者";
            var buttonText = "關閉視窗";

            $scope.alert.showHtml(html, handler, title, buttonText);
        }

    });
});