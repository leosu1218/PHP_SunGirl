/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbCalendar/view.html'], function (angular, app, view) {

    app.directive("sbCalendar", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=?instance'
            },
            controller: function ($scope ,$compile) {
                var date = new Date();

                function createCalendar(whichDiv,params)
                {
                    var strCalendar = "";

                    // 每月日數陣列
                    var monthDays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

                    // 閏年判斷
                    var mYear = date.getFullYear();
                    if ( ( (mYear % 4 == 0) && (mYear % 100 != 0) ) || (mYear % 400 == 0) ) monthDays[1] = 29;

                    // 設定日期為該月第一天
                    date.setDate(1);

                    // 該月第一天的星期
                    var day = date.getDay();

                    // 計算秀出時需要的格數
                    var total = monthDays[date.getMonth()] + day;
                    var totalCells = total + ( total%7 ? 7 - total%7 : 0  );

                    strCalendar ='<TABLE cellSpacing="1" cellPadding="0" align="center" width="100%" bgcolor="#888888">';
                    strCalendar += '<TR>';
                    strCalendar += '<TH></TH>';
                    strCalendar += "<TH>日</TH>";
                    strCalendar += '<TH>一</TH>';
                    strCalendar += '<TH>二</TH>';
                    strCalendar += '<TH>三</TH>';
                    strCalendar += '<TH>四</TH>';
                    strCalendar += '<TH>五</TH>';
                    strCalendar += '<TH>六</TH>';
                    strCalendar += '</TR>';

                    for (var i=0;i<totalCells;i++)
                    {
                        if ( i%7 == 0 )
                            strCalendar+="<TR><TD>&nbsp;</TD>";


                        if ( i >= day && i < total )
                        {
                            if ( i >= day )
                            {
                                date.setDate((i-day)+1);
                                strCalendar += "<TD>";
                                //strCalendar += "<input id='Identity' type='hidden' value='" + whichDate + "'>";
                               // console.log('<gender-monthly-chart  start-date="'+ dateTimeFormat(date) +'" box-id="\'' + params.boxId + '\'" cam-id="\''+ params.camId +'\'"></gender-monthly-chart>');
                                strCalendar += '<gender-monthly-chart  start-date="\''+ dateTimeFormat(date) +'\'" box-id="\'' + params.boxId + '\'" cam-id="\''+ params.camId +'\'"></gender-monthly-chart>';
                            }
                        }
                        else
                        {
                            strCalendar += "<TD>&nbsp;";
                        }

                        strCalendar += "</TD>";


                        if ( i%7 == 6 )
                            strCalendar += "</TR>";
                    }
                    console.log(strCalendar);
                        $scope.htmlcode = strCalendar;
                    //document.getElementById(whichDiv).innerHTML = strCalendar;
                }

                /**
                 * Format datetime object to string (Y:m:d H:i:s)
                 *
                 * @param date
                 * @param time
                 * @returns {string}
                 */
                function dateTimeFormat (date, time) {
                    time = time || date;
                    var year = "" + date.getFullYear();
                    var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
                    var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
                    var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
                    var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
                    var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
                    return year + "-" + month + "-" + day;
                }

                $scope.instance = {
                    setYearMonth: function(year , month ,params) {
                        date.setFullYear(year);
                        date.setMonth(month-1);
                        createCalendar('myDiv' ,params);
                    }
                };
            }
        };
    });
});