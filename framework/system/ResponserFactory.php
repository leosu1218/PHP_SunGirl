<?php
/**
*	Responser Factory code.
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/responser/Encoder.php' );
require_once( FRAMEWORK_PATH . 'system/responser/JsonEncoder.php' );
require_once( FRAMEWORK_PATH . 'system/responser/HtmlEncoder.php' );

require_once( FRAMEWORK_PATH . 'system/responser/Responser.php' );
require_once( FRAMEWORK_PATH . 'system/responser/RestResponser.php' );
require_once( FRAMEWORK_PATH . 'system/responser/SmartyResponser.php' );

require_once( FRAMEWORK_PATH . 'system/responser/SynatureResponserFactory.php' );

/**
*	ResponserFactory 生產各式各樣的回應器的工廠
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
class ResponserFactory extends SynatureResponserFactory {

	public function create( $name ) {

		$ClassName = $name . 'Responser';
		return new $ClassName();
	}
}








?>