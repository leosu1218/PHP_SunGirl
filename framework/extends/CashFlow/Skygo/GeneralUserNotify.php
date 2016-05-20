<?php
require_once( FRAMEWORK_PATH . 'extends/MailHelper.php' );
require_once(dirname(__FILE__) . '/../CashFlowUserNotify.php');

/**
 * Class GeneralUserNotify

 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */

class GeneralUserNotify implements CashFlowUserNotify {

    const RETURN_URL = 'skygo.109life.com/gb.html#!/helper/';

    /**
     *	Notify user that order trade result.
     *
     *	@param $info array The info for user notify
     */
    public function send($info=array()) {

        throw new ECException("Not implement.");
//
//        $returnUrl 		= self::RETURN_URL;
//        $activityName 	= $info["activity_name"];
//        $activityId 	= $info["activity_id"];
//        $orderId 		= $info["id"];
//        $mail 			= $info["buyer_email"];
//        $name 			= $info["buyer_name"];
//        $date 			= $info["create_datetime"];
//        $serial 		= $info["serial"];
//        $specs	        = $info["specs"];
//
//
//        $subject 	= "您的團購活動【" . $activityName . "】通知";
//        $mailTo 	= $mail;
//        $mailToName = $name;
//
//        $text = "親愛的" . $name . "先生/小姐 您好:<br>";
//        $text .= "<br>";
//        $text .= "您已成功參加【" . $activityName . "】活動，您此次的訂購明細如下，感謝您的支持。<br>";
//        $text .= "活動編號：" . $activityId . "<br>";
//        $text .= "訂單編號：" . $serial . "<br>";
//        $text .= "訂購日期：" . $date . "<br>";
//
//        foreach($specs["records"] as $index => $spec) {
//            $text .= "訂購商品：" . $spec["spec_name"] . "(" . $spec["spec_serial"] .")<br>";
//            $text .= "訂購數量：" . $spec["spec_amount"] . "<br>";
//            $text .= "<br>";
//        }
//
//        $text .= "若是您對此次活動或是貨品有任何問題，可以從此得到協助：";
//        $text .= "<a href='" . $returnUrl . $serial . "'>活動協助</a>";
//
//        $mailHelper = new MailHelper();
//        $mailHelper->sendByRestApi($subject, $mail, $name, $text);
    }
}



?>