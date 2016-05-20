<?php
/**
*	PermissionCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Permission
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/Permission.php' );

/**
*	PermissionCollection entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Permission
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PermissionCollection extends PermissionDbCollection {

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "permission";
	}

	public function getModelName() {
		return "Permission";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		
		if(array_key_exists("id", $attributes)) {
			throw new Exception("Can't write the attribute 'id'.");
		}

		// if(!array_key_exists("name", $attributes)) {
		// 	throw new Exception("Attributes should be has 'domain_name'.", 1);
		// }

		// if(!array_key_exists("parent_group_id", $attributes)) {
		// 	throw new Exception("Attributes should be has 'group_id'.", 1);
		// }

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
