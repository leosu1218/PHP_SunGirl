<?php
/**
*   Rest Controller code.
*
*   PHP version 5.3
*
*   @category NeteXss
*   @package Controller
*   @author Rex Chen <rexchen@synctech-infinity.com>
*   @copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/ResponserFactory.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );

/**
*   RestController 支援使用RestAPI樣板模組來組合參數與樣板合成的View
*
*   PHP version 5.3
*
*   @category NeteXss
*   @package Controller
*   @author Rex Chen <rexchen@synctech-infinity.com>
*   @copyright 2014 synctech.com
*/
abstract class RestController {

	public $responser;
    public $receiver;
    protected $factory;

	public function __construct() {
      	$this->responser = $this->createResponser( "Rest" );
      	$this->receiver = $this->getRequestData();      	
    }

    /**
    *	Setting receiver's params.
    *
    *	@param $param array The params of receiver.
    */
    public function setReceiver($params=array()) {
    	$this->receiver = $params;
    }

    /**
     *	Get query string value safely.
     *
     *	@return mixed
     */
    public  function getQueryString($name, &$arrayRef) {
        if(array_key_exists($name, $_GET)) {
            $arrayRef[$name] = $_GET[$name];
        }
        else {
            return false;
        }
    }

    /**
    *	Get params from http request.
    *
    *	@param $key string The key of request params.
    *	@param $throw bool Throw exception when the flag is true.
    */
    public function params($key='', $throw=true) {
    	if(is_array($this->receiver)) {
    		if(array_key_exists($key, $this->receiver)) {
	    		return $this->receiver[$key];
	    	}
	    	else {
	    		if($throw) {
	    			throw new InvalidAccessParamsException("Request params should has [$key].", 1);
	    		}
	    		else {
	    			return NULL;	
	    		}    		
	    	}
    	}
    	else {
    		return NULL;
    	}    	
    }

    /**
    *   建立一個回應器的方法
    * 
    *   @param string 要製造的回應器名字
    *   @return Responser 被製造出來的回應器
    */
    private function createResponser( $name ) {

	     $this->factory  = new ResponserFactory();
	     return $this->factory->create( $name );
    }

    /**
    * 取得由瀏覽器要求的Request Body Data.
    * 
    * @return array Request Body Data.
    */
    private function getRequestData() {
    	//$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
    	$data = file_get_contents( 'php://input' );
	    $data = json_decode( $data, true );
	    return $data;  
    }

    /**
     * Get router response handler configs.
     * @return array
     */
    public function getExceptions() {
        return array();
    }
	
}




?>