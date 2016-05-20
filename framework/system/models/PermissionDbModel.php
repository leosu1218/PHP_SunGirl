<?php
/**
*	Permission DataBase Model 可驗證授權的資料來源存取
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once(dirname(__FILE__) . '/DbModel.php');
require_once(dirname(__FILE__) . '/../PermissionDbActor.php');
require_once(dirname(__FILE__) . '/../PermissionDbEntity.php');
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );

/**
*	PermissionDbModel 存取DataBase的資料
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
abstract class PermissionDbModel extends DbModel implements PermissionDbEntity {
	
	protected $actor = null;
	protected $permissionDao = null;

	/**
	*	Construct the class.
	*
	*	@param $id int The entity's id in table.
	*/
	public function __construct($id, DbHero &$dao=null) {
      	parent::__construct($id, $dao);
   	}

   	/* Model interface methods. */
   	/**
   	*	Get a attribute value from the model.
   	*
   	*	@param string $name The name of the attribute.
   	*	@return mixed attribute value.
   	*/
   	public function getAttribute($name) {
   		if($this->hasPermission("read")) {
			return parent::getAttribute($name);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}   		
   	}

   	/**
	*	將model物件資料轉換成Array的方法
	*
	*	@param array $options 轉換的參數
	*	@return array 物件的資料
	*/
	public function toRecord( $options = array() ) {		
		if($this->hasPermission("read")) {
			return parent::toRecord($options);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}   		
	}

	/**
	*	Get relations model by forienkey.
	*
	*	@param $attributes The attributes forienkey.
	*	@return Model The reference model
	*/
	public function reference($attribute) {		
		return parent::reference($attribute);
	}

	/**
	*	設定之後要更新的資料內容
	*
	*	@param array $attributes 要更新的資料內容
	*/
	public function set($attributes) {
		parent::set($attributes);
	}

    /**
     * Increase attributes's value
     * @param array $attributes {"atr1"=>inc1, "atr2"=>inc2 ... }
     * @return int
     */
    public function increaseAttributes($attributes=array()) {
        if($this->hasPermission("update")) {
            return parent::increaseAttributes($attributes);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);
        }
    }

	/**
	*	執行更新的動作
	*
	*	@param array $attributes 要更新的資料內容
	*	@return boolean 成功回傳true
	*/
	public function update($attributes) {		
		if($this->hasPermission("update")) {
			return parent::update($attributes);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}
	}

	/**
	*	執行銷毀的動作
	*
	*	@return boolean 成功回傳true
	*/
	public function destroy() {		
		if($this->hasPermission("delete")) {
			return parent::destroy();
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}
	}

	/* 	Method of interface PermissionActor. */	
	/**
   	*	Setting permission actor that will validate 
   	*	the actor if has permission to action.
   	*	
   	*	@param $actor PermissionDbActor The person that do something action.   
   	*	@return  PermissionDbEntity It self.	
   	*/
   	public function setActor(PermissionDbActor $actor) {
   		$this->actor = $actor;   		  
   		return $this; 	
   	}

   	/**
   	*	Check actor has permission do the action.
   	*
   	*	@param $action string The action that actor do for the collection/model.
   	*	@return bool If has permission return true. 
   	*				 And always return true when not set actor.
   	*/
   	public function hasPermission($action = "") {
   		if(is_null($this->actor)) {
   			return true;
   		}
   		else {
   			$hasGroupPermission = $this->hasPermissionByProvider($action, "group");
   			$hasUserPermission = $this->hasPermissionByProvider($action, "user");
   			return ($hasGroupPermission || $hasUserPermission);
   		}   		
   	}

    /**
     * Get used actor
     * @return PermissionDbActor
     */
    public function getActor() {
        return $this->actor;
    }

   	/**
   	*	Check actor has group's permission do the action.
   	*
   	*	@param $provider string The Provider of permission.
   	*	@return bool If has permission return true.    	
   	*/
   	public function hasPermissionByProvider($action, $privider) {
   		$this->permissionDao = $this->actor->getPermissionDao($privider);
		$this->permissionDao->appendWhere(array(
			"and",
			"p.entity=:entity",
			"p.entity_id=:entityId",
			"p.action=:action",
		), array(
			":entity" => $this->getTable(),
			":entityId" => 0,
			":action" => $action
		));

		$record = $this->permissionDao->query();
		return !empty($record);
   	}
}



?>