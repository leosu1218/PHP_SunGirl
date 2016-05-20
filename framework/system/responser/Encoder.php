<?php 
/**
*	Encoder 定義一個編碼器的界面
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

interface Encoder {
	
	public function process( $data );
}


?>