<?php
/**
*  Default Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss-API
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );


/**
*  DefaultController 當其他Router路徑找不到時使用此預設的Controller進行回應
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2014 synctech.com
*/
class DefaultController extends RestController {

      public function getNotFound() {
            $data['message'] = '不存在此路徑, 請重新進入頁面';
            $this->responser->send( $data, $this->responser->NotFound() );
      }
}


?>