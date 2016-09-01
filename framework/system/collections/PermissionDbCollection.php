<?php
/**
*	DataBase Collection 資料來源的集合
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Collection
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once(dirname(__FILE__) . '/DbCollection.php');
require_once(dirname(__FILE__) . '/../PermissionDbActor.php');
require_once(dirname(__FILE__) . '/../PermissionDbEntity.php');
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );

/**
*	DbCollection 存取DataBase的資料集
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Collection
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
abstract class PermissionDbCollection extends DbCollection implements PermissionDbEntity {

	protected $actor = null;
	protected $permissionDao = null;

	public function __construct(DbHero &$dao=NULL) {
		parent::__construct($dao);
	}

	/**
	*	取得parent's parent的關鍵字function方法
	*
	*	how to use:
	*	$this->superTest( "method_name", ararry( "arg"=>arg_value, ... ) );
	*	
	*	@param string $name 
	*	@param string $args
	*	@return parent's parent function result
	*/
	public function __call($name, $args) {
  
	    $prefix = substr($name, 0, 5); 
	      
	    if($prefix == "super") {
	    	
	    	$superName = substr($name, 5); 
	    	return call_user_func_array("parent::$superName", $args);
	    
	    }
	    else {
	    	throw new Exception("Undefined method $name");
	    }
	}

	/* Override	Method of class DbCollection. */	
	/**
	*	借由ID取得一個Model物件
	*
	*	@param mixed $id Model的ID
	*	@return Model 取得到的Model物件
	*/
	public function getById($id) {
		if($this->hasPermission("read")) {
			return parent::getById($id);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}

	/**
	*	藉由屬性取得一個Model物件
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@return Model 取得到的Model物件
	*/
	public function get($attributes) {			
		if($this->hasPermission("read")) {
			return parent::get($attributes);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}
	
	/**
	*	借由條件取得一個Model物件
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return Model 取得到的Model物件
	*/
	public function getByCondition($conditions, $paramters) {		
		if($this->hasPermission("read")) {
			return parent::getByCondition($conditions, $paramters);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}	

	/**
	*	Get records by a id list.
	*
	*	@param $ids array Id list.
	*	@return array
	*/
	public function getRecordsByIds($ids=array()) { 
		if($this->hasPermission("list")) {
			return parent::getRecordsByIds($ids);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);		
		}	
	}

	/**
	*	藉由屬性取得一個資料集
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集, 如果找不到回傳array( ... "totalPage" => 0);
	*/
	public function getRecords($attributes=array(), $pageNo=1, $pageSize=1000, $filter=array() , $order='') {
		if($this->hasPermission("list")) {
			return parent::getRecords($attributes, $pageNo, $pageSize, $filter, $order);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to list model in " . $this->getTable(), 1);		
		}		
	}

	/**
	*	藉由屬性取得一個資料集不需要權限的驗證
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集, 如果找不到回傳array( ... "totalPage" => 0);
	*/
	public function getRecordsWithoutPermission($attributes=array(), $pageNo=1, $pageSize=1000) {
		return parent::getRecords($attributes, $pageNo, $pageSize);
	}

	/**
	*	借由條件取得一個資料集
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters  過濾出物件的參數
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集, 如果找不到回傳array( ... "totalPage" => 0);
	*/
	public function getRecordsByCondition($conditions, $paramters, $pageNo, $pageSize, $filter=array()) {		
		if($this->hasPermission("list")) {
			return parent::getRecordsByCondition($conditions, $paramters, $pageNo, $pageSize, $filter);	
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}

	/**
	*	借由id取得一筆資料
	*
	*	@param array  $id 
	*	@return array 資料
	*/
	public function getRecordById($id) {		
		if($this->hasPermission("read")) {
			return parent::getRecordById($id);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}

	/**
	*	借由條件取得一筆資料
	*
	*	@param array $attributes 資料要符合該屬性	
	*	@return array 資料
	*/
	public function getRecord($attributes) {		
		if($this->hasPermission("read")) {
			return parent::getRecord($attributes);
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);		
		}		
	}

    /**
     *	借由屬性取得一筆資料
     *
     *	@param mixed $conditions 過濾出物件的條件
     *	@param mixed $paramters 過濾出物件的參數
     *	@return array 資料
     */
    public function getRecordByCondition($conditions, $paramters) {
        if($this->hasPermission("read")) {
            return parent::getRecordByCondition($conditions, $paramters);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to read model in " . $this->getTable(), 1);
        }
    }

    /**
     * Count by conditions.
     * @param array $conditions
     * @param array $parameters
     * @throws DbOperationException
     * @throws Exception
     * @return int
     */
    public function countByCondition($conditions=array(), $parameters=array()) {
        if($this->hasPermission("read")) {
            return parent::countByCondition($conditions, $parameters);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to read count in " . $this->getTable(), 1);
        }
    }

    /**
     * Count by attributes
     * @param array $attributes
     * @throws DbOperationException
     * @throws Exception
     * @return int
     */
    public function count($attributes=array()) {
        if($this->hasPermission("read")) {
            return parent::count($attributes);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to read count in " . $this->getTable(), 1);
        }
    }
	
	/**
	*	新增一筆一資料到資料集
	*
	*	@param array $attributes 要新增的屬性參及值
	*	@return int 成功新增的資料數量
	*/
	public function create($attributes) {
		if($this->hasPermission("create")) {
			return parent::create($attributes);	
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to create model in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Create multi record into collection.
	*
	*	@param array $attributes Attributes of model. Array(
	*								attribute1(string),
	*								attribute2(string),
	*								....
	*							)
	*	@param array $values 	 Values mapped $attributes 
	*							 array that want to create.
	*							 Array(
	*								Array(value1-attr1, value1-attr2), ...
	*								Array(value2-attr1, value2-attr2), ...
	*								Array(value3-attr1, value3-attr2), ...
	*								Array(value4-attr1, value4-attr2), ...
	*							)
	*
	*	@return int 成功新增的資料數量 	
	*/
	public function multipleCreate($attributes=array(), $values=array()) {
		if($this->hasPermission("create")) {
			return parent::multipleCreate($attributes, $values);	
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to create model in " . $this->getTable(), 1);		
		}
	}

	/**
	*	Get last created model.
	*
	*	@return Model If model not found throw exception.
	*/
	public function lastCreated() {
		if($this->hasPermission("create")) {
			return parent::lastCreated();	
		}		
		else {
			throw new AuthorizationException("Actor haven't permission to create model in " . $this->getTable(), 1);		
		}
	}

    /**
     *	Destory models by id list
     *
     *	@param $ids The id list want to destory.
     *	@return int The counter of effect row.
     */
    public function multipleDestroyById($ids) {
        if($this->hasPermission("delete")) {
            return parent::multipleDestroyById($ids);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to destroy model in " . $this->getTable(), 1);
        }
    }

    public function multipleDestroyByCondition($condition) {
        if($this->hasPermission("delete")) {
            return parent::multipleDestroyByCondition($condition);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to destroy model in " . $this->getTable(), 1);
        }
    }

    /**
     * Update attribute by id list.
     *
     * @param $ids array The id list want to update. e.g. array(1,2,3,4...)
     * @param $attribute array New attribute want to update. e.g. array('name' => 'new name', ...)
     * @return int The counter of effect rows.
     */
    public function multipleUpdateById($ids=array(), $attribute=array()) {
        if($this->hasPermission("update")) {
            return parent::multipleUpdateById($ids, $attribute);
        }
        else {
            throw new AuthorizationException("Actor haven't permission to update model in " . $this->getTable(), 1);
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