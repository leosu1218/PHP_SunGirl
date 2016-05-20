<?php
/**
*  GeneralSession code.
*
*  PHP version 5.3
*
*  @category GeneralSession
*  @package Session
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2014 synctech.com
*/

class GeneralSession {

	private $id;
	private static $instance;

   	private function __construct() {
   		if(!($this->isStarted())) {
   			session_start();   		   			
   		}   
   		$this->id = session_id();			
   	}

   	/**
   	*	Clear session variables.
   	*
   	*	@param $name string The key of session variables.
   	*/
   	public function clear($name = null) {
   		if($this->isStarted()) {
   			if(is_null($name)) {
   				session_unset();
   			}
   			else {
   				if(isset($_SESSION[$name])) {
   					unset($_SESSION[$name]);
   				}
   			}   			
   		}
   	}

   	/**
   	*	Check session is started.
   	*
   	*/
   	private function isStarted() {
	    if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === '' ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	}


   	/**
   	*	Get instance method. (Singleton Pattern)
   	*
   	*/
   	public static function getInstance() {
   		if(!isset(self::$instance)) {
   			self::$instance = new GeneralSession();     			
   		}

   		return self::$instance;
   	}

   	/**
   	*	Get the session attribute
   	*
   	*/
   	public function __get($name) {
   		if(isset($_SESSION[$name])) {   			
   			return $_SESSION[$name];
   		}
   		else {
   			return null;
   		}
   	}

   	/**
   	*	Set the session attribute and value.
   	*
   	*/
   	public function __set($name, $value) {
   		$_SESSION[$name] = $value;   		   		
   	}
}




?>