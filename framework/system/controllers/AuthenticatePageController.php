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

require_once( FRAMEWORK_PATH . 'system/controllers/SmartyController.php' );
require_once( FRAMEWORK_PATH . 'components/SmartyComponents.php' );
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
abstract class AuthenticatePageController extends SmartyController {

	public $mainFrame;
	public $components;
	public $authorize;
	
	public function __construct() {

		parent::__construct();

		$this->components = new SmartyComponents();
		$this->authorize  = new User();

		$this->mainFrame = $this->components->create('MainFrame', $this->authorize);
	}
}
?>