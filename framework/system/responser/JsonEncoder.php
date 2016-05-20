<?php 
/**
*	可以轉換json編碼的編碼器
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

class JsonEncoder implements Encoder {

	public function process( $data ) {
		
		return json_encode( $data );
	}
}

?>