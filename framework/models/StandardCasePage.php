<?php
/**
*	StandaedCasePage code.
*
*	PHP version 5.3
*
*	@category Model
*	@package StandaedCasePage
*	@author Ares <ares@synctech.ebiz.tw>
*	@copyright 2016 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

/**
*	StandaedCasePage code.
*
*	PHP version 5.3
*
*	@category Model
*	@package StandaedCasePage
*	@author Ares <ares@synctech.ebiz.tw>
*	@copyright 2016 synctech.com
*/

class StandaedCasePage extends PermissionDbModel {

	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "standaed_case_page";
	}

   	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		return true;
	}

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}
	
}

?>