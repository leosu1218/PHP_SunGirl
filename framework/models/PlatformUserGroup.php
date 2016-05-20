<?php
/**
*	UserGruop code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserGroupHasPermissionCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );

/**
*	UserGruop code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class PlatformUserGroup extends PermissionDbModel {
	
	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user_group";
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
	*	Append permission to the group.
	*
	*	@param $permissions array Permission id list. Array(1,2,4,7,34,75)
	*	@return int The number of effect rows.
	*/
	public function appendPermissions($permissions=array()) {		
		if($this->hasPermission("append_permission")) {
			$params = array();
			foreach($permissions as $index => $permissionId) {
				array_push($params, array($this->id, $permissionId));
			}		

			$collection = new PlatformUserGroupHasPermissionCollection();
			$attributes = array('platform_user_group_id', 'permission_id');
			$effectRows = $collection->multipleCreate($attributes, $params);

			return $effectRows;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to assign groug permission in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Get permission records that the model has.
	*
	*	@param $pageNo int The record page number.
	*	@param $pageSize int The record page size.
	*	@return array The query results. Struct Array(
	*
	*
	*									)
	*/
	public function getPermissions($pageNo, $pageSize) {

		if($this->hasPermission("list_permission")) {
			$this->dao->fresh();
			$this->dao->select(array(
				"g.id",
				"p.id permission_id",
				"p.entity",
				"p.entity_id",
				"p.action",
				"p.name permission_name",
				"ghp.id group_permission_id"
			));

			$this->dao->from("platform_user_group g");		
			$this->dao->join("platform_user_group_has_permission ghp", "ghp.platform_user_group_id=g.id");
			$this->dao->join("permission p", "p.id=ghp.permission_id");				

			$primaryKey = $this->getPrimaryAttribute();
			$where 		= "g.id=:$primaryKey";
			$params 	= array(":$primaryKey" => $this->getId());
			$this->dao->where($where, $params);
			$this->dao->paging($pageNo, $pageSize);

			$result["records"] 		= $this->dao->queryAll();
			$result["totalPage"] 	= intval(ceil($this->dao->queryCount() / $pageSize));
			$result["pageSize"]		= $pageSize;
			$result["pageNo"]		= $pageNo;

			return $result;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to list groug permission in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Get user records that the model has.
	*
	*	@param $pageNo int The record page number.
	*	@param $pageSize int The record page size.
	*	@return array The query results. Struct Array(
	*
	*
	*									)
	*/
	public function getUsers($pageNo, $pageSize) {
		if($this->hasPermission("read")) {
			$this->dao->fresh();
			$this->dao->select(array(
				"u.id id",
				"u.name name",
				"u.account account",
				"u.email email",
				"g.parent_group_id parent_group_id",
				"g.id group_id",
				"g.name group_name",
			));

			$this->dao->from("platform_user_group g");		
			$this->dao->join("platform_user u", "u.group_id=g.id");			

			$primaryKey = $this->getPrimaryAttribute();
			$where 		= "g.id=:$primaryKey";
			$params 	= array(":$primaryKey" => $this->getId());
			$this->dao->where($where, $params);
			$this->dao->paging($pageNo, $pageSize);

			$result["records"] 		= $this->dao->queryAll();
			$result["totalPage"] 	= intval(ceil($this->dao->queryCount() / $pageSize));
			$result["pageSize"]		= $pageSize;
			$result["pageNo"]		= $pageNo;

			return $result;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to list groug permission in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Get user model.
	*
	*	@param $id int The user id	
	*	@return model The model of user
	*/
	public function getUserModel($id) {
		if($this->hasPermission("read")) {
			$this->dao->fresh();
			$this->dao->select(array(
				"u.id id",
				"u.name name",
				"u.account account",
				"u.email email",
				"g.parent_group_id parent_group_id",
				"g.id group_id",
				"g.name group_name",
			));

			$this->dao->from("platform_user_group g");		
			$this->dao->join("platform_user u", "u.group_id=g.id");			

			$primaryKey = $this->getPrimaryAttribute();
			$where 		= array("and", "g.id=:$primaryKey", "u.id=:userid");
			$params 	= array(":$primaryKey" => $this->getId(), ":userid" => $id);
			$this->dao->where($where, $params);			
			$record 	= $this->dao->query();
			
			if(empty($record)) {
				throw new DbOperationException("User not exist in the group.", 1);
			}
			else {
				return (new PlatformUserCollection())->getById($id);
			}
		}
		else {
			throw new AuthorizationException("Actor haven't permission to list groug permission in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Remove a user Permission that is in the group.
	*
	*	@param $id int User ID.	
	*/
	public function removeUserPermission($id) {
		if($this->hasPermission("admin_user")) {
			$user = $this->getUserModel($id);			
			$this->enforceRemoveUserPermission($id);		
		}
		else {
			throw new AuthorizationException("Actor haven't permission to remove user from group permission in " . $this->getTable(), 1);		
		}	
	}

	/**
	*	Enforce revmove user permission not matter user in the group or not.
	*
	*	@param $id int User ID
	*/
	private function enforceRemoveUserPermission($id) {
		$this->dao->fresh();
		$table = "platform_user_has_group_permission";
		$where = "platform_user_id=:id";
		$params = array(":id" => $id);
	
		$this->dao->delete($table, $where, $params);
	}

	/**
	*	Remove a user that is in the group.
	*
	*	@param $id int User ID.	
	*/
	public function removeUser($id) {
		if($this->hasPermission("admin_user")) {

			$user = $this->getUserModel($id);
			$rowCount = $user->update(array(
				"group_id" => $this->getId()
			));
			if($rowCount <= 0) {
				throw new DbOperationException("Remove user from group fail.", 1);				
			}			

			$this->enforceRemoveUserPermission($id);			
		}
		else {
			throw new AuthorizationException("Actor haven't permission to remove user from group permission in " . $this->getTable(), 1);		
		}	
	}

	/**
	*	Append a user into the group.
	*
	*	@param $id int User ID.	
	*/
	public function appendUser($id) {
		if($this->hasPermission("admin_user")) {
		
			$user = (new PlatformUserCollection())->getById($id);
			$rowCount = $user->update(array(
				"group_id" => $this->getId()
			));

			if($rowCount <= 0) {
				throw new DbOperationException("Append user from group fail.", 1);				
			}			
		}
		else {
			throw new AuthorizationException("Actor haven't permission to append user from groug permission in " . $this->getTable(), 1);		
		}	
	}
}

?>