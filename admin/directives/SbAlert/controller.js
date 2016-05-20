/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbAlert/view.html'], function (angular, app, view) {

	app.directive("sbAlert", function () {
		return {
			restrict: "E",			
			template: view,
			scope: {				
				instance: '=?instance',
			},
			controller: function ($scope) {
				
				$scope.id = makeid();				

				$scope.instance = {

                    /**
                     * Simply show message
                     * @param msg
                     * @param callback
                     * @param title
                     * @param buttonText1
                     */
                    show: function(msg, callback, title, buttonText1) {

                        $scope.title = title || "訊息";
                        $scope.buttonText1 = buttonText1 || "確定";
                        $scope.showButton2 = false;
                        $scope.message = msg;
                        $scope.htmlMessage = "";

                        $('#' + $scope.id + '-Modal').unbind();

                        if(typeof(callback) != 'function') {
                            callback = function() {};
                        }

                        $scope.button1Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }


                        $('#' + $scope.id + '-Modal').modal();
                    },

                    /**
                     * Show html message
                     * @param html
                     * @param callback
                     * @param title
                     * @param buttonText1
                     */
                    showHtml: function(html, callback, title, buttonText1, width) {

                        $scope.title = title || "訊息";
                        $scope.buttonText1 = buttonText1 || "確定";
                        $scope.showButton2 = false;
                        $scope.message = "";
                        $scope.htmlMessage = html;

                        if(width) {
                            var windowBoundle = $(window).width() - 40;
                            if(width > windowBoundle) {
                                width = windowBoundle;
                            }
                            $scope.style = {width:width + "px"};
                        }

                        $('#' + $scope.id + '-Modal').unbind();

                        if(typeof(callback) != 'function') {
                            callback = function() {};
                        }

                        $scope.button1Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }


                        $('#' + $scope.id + '-Modal').modal();
                    },

                    /**
                     * Simply show confirm box.
                     * @param msg
                     * @param callback1
                     * @param title
                     * @param buttonText1
                     * @param callback2
                     * @param buttonText2
                     */
                    confirm: function(msg, callback1, title, buttonText1, callback2, buttonText2, width) {

                        $scope.title = title || "訊息";
                        $scope.buttonText1 = buttonText1 || "確定";
                        $scope.buttonText2 = buttonText2 || "取消";
                        $scope.showButton2 = true;
                        $scope.message = msg;
                        $scope.htmlMessage = "";

                        if(width) {
                            var windowBoundle = $(window).width() - 40;
                            if(width > windowBoundle) {
                                width = windowBoundle;
                            }
                            $scope.style = {width:width + "px"};
                        }

                        $('#' + $scope.id + '-Modal').unbind();

                        if(typeof(callback1) != 'function') {
                            callback1 = function() {};
                        }

                        if(typeof(callback2) != 'function') {
                            callback2 = function() {};
                        }

                        $scope.button1Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback1();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }

                        $scope.button2Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback2();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }

                        $('#' + $scope.id + '-Modal').modal();
                    },

                    /**
                     * Show html confirm box
                     * @param msg
                     * @param callback1
                     * @param title
                     * @param buttonText1
                     * @param callback2
                     * @param buttonText2
                     */
                    confirmHtml: function(html, callback1, title, buttonText1, callback2, buttonText2) {

                        $scope.title = title || "訊息";
                        $scope.buttonText1 = buttonText1 || "確定";
                        $scope.buttonText2 = buttonText2 || "取消";
                        $scope.showButton2 = true;
                        $scope.message = "";
                        $scope.htmlMessage = html;

                        $('#' + $scope.id + '-Modal').unbind();

                        if(typeof(callback1) != 'function') {
                            callback1 = function() {};
                        }

                        if(typeof(callback2) != 'function') {
                            callback2 = function() {};
                        }

                        $scope.button1Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback1();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }

                        $scope.button2Click = function() {
                            $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                                callback2();
                            })
                            $('#' + $scope.id + '-Modal').modal('hide');
                        }

                        $('#' + $scope.id + '-Modal').modal();
                    }
				}

				function makeid() {
				    var text = "";
				    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				    for( var i=0; i < 5; i++ )
				        text += possible.charAt(Math.floor(Math.random() * possible.length));
				    return text;
				}

			}
		};
	});
});