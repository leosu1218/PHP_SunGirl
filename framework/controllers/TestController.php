<?php
/**
*  Test Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/DbHero/Db.php' );

class TestController extends RestController {

    /**
     * Test SbPieChart directive loadByUrl method.
     * @return array
     */
    public function getPieChart() {
        return array(
            "records" => array(
                array("value" => 50, "label" => "type1"),
                array("value" => 70, "label" => "type2"),
                array("value" => 90, "label" => "type3"),
                array("value" => 30, "label" => "type4"),
                array("value" => 40, "label" => "type5"),
            )
        );
    }

    /**
     * Test SbBarChart directive loadByUrl method.
     * @return array
     */
    public function getBarChart() {
        return array(
            "records" => array(
                array("value" => 50, "label" => "type1"),
                array("value" => 70, "label" => "type2"),
                array("value" => 90, "label" => "type3"),
                array("value" => 30, "label" => "type4"),
                array("value" => 40, "label" => "type5"),
            )
        );
    }

    /**
     * Test SbCurveChart directive loadByUrl method.
     * @return array
     */
    public function getCurveChart() {
        return array(
            "labels" => array(
                '00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
                '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
                '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
                '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
            ),
            "records" => array(
                array(
                    "values" => array(10,15,30,34,44,49,12,42,66,21,34,29,45,21,46,72,12,58,49,12,30,44,27,46),
                    "label" => "type1"
                ),
                array(
                    "values" => array(12,35,77,23,46,12,34,56,23,45,56,12,56,44,67,23,45,67,12,84,35,78,23,46),
                    "label" => "type2"
                ),
                array(
                    "values" => array(90,85,32,34,56,23,67,23,67,85,32,45,46,77,54,57,23,12,33,56,78,56,69,34),
                    "label" => "type3"
                ),
            )
        );
    }
}




?>