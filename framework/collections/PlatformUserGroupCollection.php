<?php
/**
*	PlatformUserGroupCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUserGroup.php' );

/**
*	UserGruopCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PlatformUserGroupCollection extends PermissionDbCollection {

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user_group";
	}

	public function getModelName() {
		return "PlatformUserGroup";
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

		if(!array_key_exists("name", $attributes)) {
			throw new Exception("Attributes should be has 'domain_name'.", 1);
		}

		if(!array_key_exists("parent_group_id", $attributes)) {
			throw new Exception("Attributes should be has 'group_id'.", 1);
		}

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

	/**
	*	TODO db transaction.
	*	Create PlatformUserGroup model with permissions.	
	*
	*	@param $attrubutes array Model's attributes.
	*	@param $permissions array Permission's id.
	*	@return model The model of PlatformUserGroup.
	*/
	public function createWithPermission($attributes, $permissions) {
		
		$isCreated = (count($this->create($attributes)) > 0);

		if($isCreated) {
			// TODO $model->referenceCollection("permission")->createRelation(...)
			$group = $this->lastCreated();	

			if(!is_null($this->actor)) {
				$group->setActor($this->actor);
			}

			$effectRows = $group->appendPermissions($permissions);
			if($effectRows == count($permissions)) {
				return $group;
			}
			else if($effectRows == 0) {
				throw new Exception("Assign permission fail.", 1);
			}
			else {
				throw new Exception("Assign permission has some error.", 1);				
			}
		}
		else {
			throw new Exception("Create group fail.", 1);			
		}
	}

}



?>
