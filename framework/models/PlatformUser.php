<?php
/**
*	User code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );
require_once( FRAMEWORK_PATH . 'system/PermissionDbActor.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserHasGroupPermissionCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserHasPermissionCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );

/**
*	User code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class PlatformUser extends PermissionDbModel implements PermissionDbActor {

	/**
	*	Get permission list that depend on user and group.
	*
	*	@return array Array(
	*					""	
	*				  )
	*/
	public function getPermisson() {		
		throw new Exception("Not Implement.", 1);		
	}

	/**
	*	Get permission list that depend user.
	*	@return array Array(
	*					Array(
	*						"id" => <userId>,
	*						"permission_id" => <permission_id>,
	*						"entity" => <entity>,
	*						"entity_id" => <entity_id>,
	*						"action" => <action>,
	*						"permission_name" => <permission_name>,
	*					),
	*					Array(
	*						"id" => <userId>,
	*						"permission_id" => <permission_id>,
	*						"entity" => <entity>,
	*						"entity_id" => <entity_id>,
	*						"action" => <action>,
	*						"permission_name" => <permission_name>,
	*					),
	*					....	
	*				  )
	*/
	public function getUserPermission() {
		$this->setUserPermissionQueryStatement();
		return $this->dao->queryAll();
	}

	

	/**
	*	Get permission list that depend user's group.
	*
	*	@return array Array(
	*					Array(
	*						"id" => <userId>,
	*						"permission_id" => <permission_id>,
	*						"entity" => <entity>,
	*						"entity_id" => <entity_id>,
	*						"action" => <action>,
	*						"permission_name" => <permission_name>,
	*					),
	*					Array(
	*						"id" => <userId>,
	*						"permission_id" => <permission_id>,
	*						"entity" => <entity>,
	*						"entity_id" => <entity_id>,
	*						"action" => <action>,
	*						"permission_name" => <permission_name>,
	*					),
	*					....	
	*				  )
	*/
	public function getGroupPermission() {		
		$this->setGroupPermissionQueryStatement();
		return $this->dao->queryAll();
	}

	/**
	*	Set sql statement for query permission for provide by user.
	*
	*/
	private function setUserPermissionQueryStatement() {
		$this->setPermissionQueryStatement();
		
		$this->dao->join("platform_user_has_permission uhp", "uhp.platform_user_id=u.id");
		$this->dao->join("permission p", "p.id=uhp.permission_id");		

		$primaryKey = $this->getPrimaryAttribute();
		$where = "u.id=:$primaryKey";
		$params = array(":$primaryKey" => $this->getId());
		$this->dao->where($where, $params);
	}

	/**
	*	Set sql statement for query permission for provide by group.
	*
	*/
	private function setGroupPermissionQueryStatement() {
		$this->setPermissionQueryStatement();

		$this->dao->join("platform_user_group g", "g.id=u.group_id");		
		$this->dao->join("platform_user_group_has_permission ghp", "ghp.platform_user_group_id=g.id");
		$this->dao->join("permission p", "p.id=ghp.permission_id");	
		$this->dao->join("platform_user_has_group_permission uhgp", "uhgp.platform_group_permission_id=ghp.id");	

		$primaryKey = $this->getPrimaryAttribute();
		$where = array("and", "u.id=:$primaryKey", "uhgp.platform_user_id=u.id");
		$params = array(":$primaryKey" => $this->getId());
		$this->dao->where($where, $params);
	}

	/**
	*	Set required sql statement for query permission.
	*
	*/
	public function setPermissionQueryStatement() {
		$this->dao->fresh();
		$this->dao->select(array(
			"u.id",
			"p.id permission_id",
			"p.entity",
			"p.entity_id",
			"p.action",
			"p.name permission_name",
		));

		$this->dao->from($this->getTable() . " u");
	}

	/* 	Method of interface PermissionActor. */	
	/**
	*	Get permission query dao.
	*	
	*	@param $provider string The Provider of permission.
	*	@return dao The dao for query permission records.
	*/
	public function getPermissionDao($provider = "") {
		if($provider == "user") {
			$this->setUserPermissionQueryStatement();
		}
		else if($provider == "group") {
			$this->setGroupPermissionQueryStatement();	
		}
		else {
			throw new Exception("Undefined permission provider.", 1);			
		}
		return $this->dao;
	}

	/* 	Method of interface DbModel. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user";
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

	/**
	*	Instance model by session variable.
	*
	*	@return PlatformUser Model id by session variable.
	*/
	public static function instanceBySession() {
		$record = self::getRecordBySession();
		return new PlatformUser($record["id"]);
	}	

	/**
   	*	Get user info from session variable.
   	*
   	*	@return array User record Array(
	*								"account" 		=> ["account"],
    *    							"domain_name"	=> ["domain_name"],
    *    							"group_id"		=> ["group_id"],
    *    							"id"			=> ["id"],
    *    							"name"			=> ["name"]
   	*							  ).
   	*/
   	public static function getRecordBySession() {

   		$userInfo = GeneralSession::getInstance()->user;

   		if(is_null($userInfo) || empty($userInfo)) {
			throw new AuthorizationException("Unauthenticated session.", 1);
		}

   		return $userInfo;
   	}

   	/**
   	*	Clear all of session variables of user info.
   	*
   	*/
   	public static function clearSession() {
   		$session = GeneralSession::getInstance();
   		$session->clear("user");
   	}

   	/**
   	*	Saving user info into session variable.
   	*
   	*	@param $userInfo array user info. Array(
   	*									"account" 		=> ["account"],
    *	    							"domain_name"	=> ["domain_name"],
    *   	 							"group_id"		=> ["group_id"],
    *    								"id"			=> ["id"],
    *    								"name"			=> ["name"]
   	*							  	).
   	*	@return bool Return true when save success.
   	*/
   	public static function saveToSession($userInfo) {
   		$session = GeneralSession::getInstance();
		$session->user = $userInfo;	
   	}

   	/**
	*	TODO db transaction.
	*	Append group permission to the user.
	*
	*	@param $permissions array Group permission id list. Array(1,2,4,7,34,75)
	*	@return int The number of effect rows.
	*/
	public function appendGroupPermissions($permissions=array()) {		
		if($this->hasPermission("create")) {
			$params = array();
			foreach($permissions as $index => $permissionId) {
				array_push($params, array($this->id, $permissionId));
			}		

			$collection = new PlatformUserHasGroupPermissionCollection();
			$attributes = array('platform_user_id', 'platform_group_permission_id');
			$effectRows = $collection->multipleCreate($attributes, $params);

			return $effectRows;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to assign groug permission in " . $this->getTable(), 1);		
		}
	}

	/**
	*	TODO db transaction.
	*	Append permission to the user.
	*
	*	@param $permissions array permission id list. Array(1,2,4,7,34,75)
	*	@return int The number of effect rows.
	*/
	public function appendPersonPermissions($permissions=array()) {		
		if($this->hasPermission("create")) {
			$params = array();
			foreach($permissions as $index => $permissionId) {
				array_push($params, array($this->id, $permissionId));
			}		

			$collection = new PlatformUserHasPermissionCollection();
			$attributes = array('platform_user_id', 'permission_id');
			$effectRows = $collection->multipleCreate($attributes, $params);

			return $effectRows;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to assign groug permission in " . $this->getTable(), 1);		
		}
	}

}

?>