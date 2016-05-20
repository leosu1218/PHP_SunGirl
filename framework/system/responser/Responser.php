<?php
/**
*	Responser 定義一個回應器的的抽象實體
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/


abstract class Responser {
	
	abstract public function send( $data, $options );
}

?>