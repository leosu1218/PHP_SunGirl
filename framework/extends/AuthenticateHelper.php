<?php
/**
*  AuthenticateHelper code.
*
*  PHP version 5.3
*
*  @category AuthenticateHelper
*  @package authenticate
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

class AuthenticateHelper {

	/**
	*	Create a salt for hash.
	*
	*	@param $length 		string 	Salt string's length.	
	*	@return string  	Salt string.
	*/
	public function generateSalt($length = 5) {

	    $words = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $wordsLength = strlen($words);
	    $saltString 		= '';

	    for ($i = 0; $i < $length; $i++) {
	        $saltString .= $words[rand(0, $wordsLength - 1)];
	    }

	    return $saltString;
	}

	/**
	*	Verify password by hash with salt.
	*
	*	@param $password string
	*	@param $hash 	 string
	*	@param $salt 	 string
	*	@return bool 	 Retrun true when password is correct.
	*/
	public function passwordVerify($password, $hash, $salt) {
		try {
			return ($this->hash($password , $salt) == $hash);
		}
		catch(Exception $e) {
			return false;
		}
	}

	/**
	*	hash password
	*
	*	@param $password 	string 
	*	@param $salt  		string
	*	@return string hash code
	*/
	public function hash( $password, $salt ){
		return sha1($password . $salt);
	}
}




?>