<?php
/**
*  ValidatorHelper code.
*
*  PHP version 5.3
*
*  @category ValidatorHelper
*  @package validator
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );

class ValidatorHelper {
	/**
	*	Checking require attrubute is exsits.
	*	Throw exception when missing key found.
	*
	*	@param $key string attribute key.
	*	@param $attributes The array want to checked.
	*/
	public function requireAttribute($key, $attributes, $throw=true) {
		if(!array_key_exists($key, $attributes)) {
			throw new InvalidAccessParamsException("Missing required parameter [$key].");
		}
	}

	/**
	*
	*
	*/
	public function requireNumeric($key, $attributes) {
		if(!is_numeric($attributes[$key])) {
			throw new InvalidAccessParamsException("Invalid parameter [$key] should be number.");
		}
	}

	/**
	*	Checking datetime is greater than other.	
	*
	*	@param $greaterDatetime string	
	*	@param $datetime string 
	*	@return bool
	*/
	public function isDateGreaterThan($greaterDatetime, $datetime) {

		if( (new DateTime($greaterDatetime)) >= (new DateTime($datetime)) ) {
			return true;		
		}
		else {
			return false;	
		}		
	}

	/**
	*	Checking datetime is greate than other.
	*	Throw exception when date time is less thant other
	*
	*	@param $greaterDatetime array 
	*	@param $greater string 	
	*	@param $datetime array 
	*	@param $name string 
	*/
	public function requireDateGreaterThan($greaterDatetime, $greater, $datetime, $name, $throw=true) {

		$this->validateDatetime($greaterDatetime, $greater);
		$this->validateDatetime($datetime, $name);

		if( !((new DateTime($greaterDatetime[$greater])) >= (new DateTime($datetime[$name]))) ) {
            if($throw) {
                throw new InvalidAccessParamsException("Invalid parameter [$greater] should be greater than [$name].", 1);
            }
            else {
                return false;
            }
		}

        return true;
	}

	public function validateDatetime($datetime, $name) {

		$date = $datetime[$name];
	    $d = DateTime::createFromFormat("Y-m-d H:i:s", $date);
	    if(! ($d && $d->format("Y-m-d H:i:s") == $date)) {	    	
	    	throw new InvalidAccessParamsException("Invalid parameter [$name] should be datetime string.", 1);
	    }
	}

	/**
	*	Checking datetime is less than other.
	*	Throw exception when date time is greate thant other
	*
	*	@param $lessDatetime array 
	*	@param $less string 	
	*	@param $datetime array 
	*	@param $name string  
	*/
	public function requireDateLessThan($lessDatetime, $less, $datetime, $name) {
		$this->validateDatetime($lessDatetime, $less);
		$this->validateDatetime($datetime, $name);

		if( !((new DateTime($lessDatetime[$less])) <= (new DateTime($datetime[$name]))) ) {
			throw new InvalidAccessParamsException("Invalid parameter [$less] should be less than [$name].", 1);			
		}
	}

	/**
	*	Check the during days less than $days.
	*	Throw exception when during days is greater thant other
	*
	*	@param $datetimeStart array 
	*	@param $start string 	
	*	@param $datetimeEnd array
	*	@param $end string 	
	*	@param $days numeric 
	*/
	public function requireDateDuring($datetimeStart, $start, $datetimeEnd, $end, $days) {

		$this->validateDatetime($datetimeStart, $start);
		$this->validateDatetime($datetimeEnd, $end);

		$startSec 	= strtotime($datetimeStart[$start]);
		$endSec 	= strtotime($datetimeEnd[$end]);
		$daySec 	= $days * 24 * 60 * 60;

		if( ($endSec - $startSec) > $daySec ) {
			throw new InvalidAccessParamsException("Invalid parameter during [$start] to [$end] should be less than $days days.", 1);			
		}
	}

	/**
	*	Check the during days less than $days.
	*	Throw exception when during days is greater thant other
	*
	*	@param $datetimeStart string 
	*	@param $datetimeEnd string 
	*	@param $days numeric 
	*/
	public function requireDateOver($datetimeStart, $start, $datetimeEnd, $end, $days) {
		$this->validateDatetime($datetimeStart, $start);
		$this->validateDatetime($datetimeEnd, $end);

		$startSec 	= strtotime($datetimeStart[$start]);
		$endSec 	= strtotime($datetimeEnd[$end]);
		$daySec 	= $days * 24 * 60 * 60;

		if( ($endSec - $startSec) < $daySec ) {
			throw new InvalidAccessParamsException("Invalid parameter during [$start] to [$end] should be greater than $days days.", 1);
		}
	}

	/**
	*	Check the numeric is greater than other
	*	Throw exception when numeric is less thant other
	*
	*	@param $greaterNum numeric 
	*	@param $num numeric 	
	*/
	public function requireNumericGreaterThan($greaterNum, $greater, $num, $key) {
		if($greaterNum[$greater] < $num[$key]) {
			throw new InvalidAccessParamsException("Invalid parameter [$greater] should be greater than  [$key].", 1);			
		}
	}

	/**
	*	Check the numeric is less than other
	*	Throw exception when numeric is greater thant other
	*
	*	@param $lessNum numeric 
	*	@param $num numeric 	
	*/
	public function requireNumericLessThan($lessNum, $less, $num, $key) {
		if($lessNum[$less] > $num[$key]) {
			throw new InvalidAccessParamsException("Invalid parameter [$less] should be less than  [$key].", 1);			
		}
	}
	
}


?>