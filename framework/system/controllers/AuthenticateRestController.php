<?php
/**
*	SmartyController 支援使用Smarty樣板模組來組合參數與樣板合成的View
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Controller
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'system/exception/NoPmsException.php' );
require_once( FRAMEWORK_PATH . 'models/User.php' );

/**
*	SmartyController 支援使用Smarty樣板模組來組合參數與樣板合成的View
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Controller
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
abstract class AuthenticateRestController extends RestController {

	public $authorize;
	
	public function __construct() {

		parent::__construct();		
		$this->authorize  = new User();
	}
}
?>