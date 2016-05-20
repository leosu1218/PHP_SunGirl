<?php
/**
*	Smarty Controller code.
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Controller
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/ResponserFactory.php' );


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
abstract class SmartyController {
	
	protected $responser;
	protected $reciever;
	protected $factory;

	public function __construct() {

      	$this->responser 	= $this->createResponser( "Smarty" );
      	$this->reciever 	= $this->getRequestData();
    }

    /**
    * 	建立一個回應器的方法
    * 
    *	@param string 要製造的回應器名字
    * 	@return Responser 被製造出來的回應器
    */
    private function createResponser( $name ) {

	     $this->factory = new ResponserFactory();
	     return $this->factory->create( $name );
    }

    /**
    * 取得由瀏覽器要求的Request Body Data.
    * 
    * @return array Request Body Data.
    */
    private function getRequestData() {

    	$data = file_get_contents( 'php://input' );
	    $data = json_decode( $data, true );
	    return $data;  
    }
	
}



?>