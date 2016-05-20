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

require_once(dirname(__FILE__) . '/Collection.php');
require_once( FRAMEWORK_PATH . 'extends/DbHero/Db.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/DataAccessResultException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/DbOperationException.php' );

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
abstract class DbCollection implements Collection {

	public $dao;

	public function __construct(DbHero &$dao=NULL) {
        if(is_null($dao)) {
            $this->dao = new Db(DB_NAME);
        }
        else {
            $this->dao = $dao;
        }
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	abstract function validAttributes($attributes);

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	abstract function getTable();

	/**
	*	Get the entity model object name.
	*
	*	@return string
	*/
	abstract function getModelName();	

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	abstract function getPrimaryAttribute();

	/* Collection interface methods. */

	/**
	*	借由ID取得一個Model物件
	*
	*	@param mixed $id Model的ID
	*	@return Model 取得到的Model物件
	*/
	public function getById($id) {
		$model = $this->getModelName();
		return new $model($id, $this->dao);
	}

	/**
	*	藉由屬性取得一個Model物件
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@return Model 取得到的Model物件
	*/
	public function get($attributes) {
		$record = $this->getRecord($attributes);
		$primaryKey = $this->getPrimaryAttribute();

		if(array_key_exists($primaryKey, $record)) {
			$id = $record[$primaryKey];
		}
		else {
			$id = NULL;
		}

		return $this->getById($id);
	}
	
	/**
	*	借由條件取得一個Model物件
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return Model 取得到的Model物件
	*/
	public function getByCondition($conditions, $paramters) {		
		$record = $this->getRecordByCondition($conditions, $paramters);
		$primaryKey = $this->getPrimaryAttribute();		

		if(array_key_exists($primaryKey, $record)) {
			$id = $record[$primaryKey];
		}
		else {
			$id = NULL;
		}

		return $this->getById($record[$primaryKey]);
	}

	/**
	*	Get default record data structure.
	*
	*	@return array()
	*/
	protected function getDefaultRecords($pageNo, $pageSize) {
		$records = array(
			"records" => array(),
			"pageNo" => $pageNo,
			"pageSize" => $pageSize,
			"totalPage" => 0,
			"recordCount" => 0
		);

		return $records;
	}

	/**
	*	Get condition from id list that for query or update sql.
	*
	*	@param $ids array Id list.
	*	@return array Array('where'=> <where condition>, 
	*						'param' => <condition's value>)
	*/
	private function getIdsListCondition($ids=array()) {
		if(count($ids) == 0) {
			throw new InvalidAccessParamsException("Parameter ids should has a id at last.", 1);
		}
		
		$primary    = $this->getPrimaryAttribute();
		$where = array("or");
		$param = array();

		foreach($ids as $index => $id) {
			$key = ":id" . $index;
			array_push($where, $primary . "=" . $key);
			$param[$key] = $id;			
		}

		return array('where' => $where, 'param' => $param);
	}

	/**
	*	Get records by a id list.
	*
	*	@param $ids array Id list. like $array = [1,2,3,4,5 ... ]
	*	@return array 
	*/
	public function getRecordsByIds($ids=array()) {
		$this->dao->fresh();

		$condition 	= $this->getIdsListCondition($ids);
		$where 		= $condition['where'];
		$param 		= $condition['param'];

		$this->dao->from($this->getTable());
		$this->dao->where($where, $param);

		return $this->dao->queryAll();
	}

	/**
	*	藉由屬性取得一個資料集
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集, 如果找不到回傳array( ... "totalPage" => 0);
	*/
	public function getRecords($attributes=array(), $pageNo=1, $pageSize=1000, $filter=array()) {

		if(empty($attributes)) {
			$attributes = array("1" => 1);
		}

		$where = array('and');
		$param = array(); 

		foreach($attributes as $name => $attribute) {
			array_push($where, "$name=:$name");
			$param[":$name"] = $attribute;
		}
		return $this->innerRecordsByCondition($where, $param, $pageNo, $pageSize, $filter);
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
	private function innerRecordsByCondition($conditions, $paramters, $pageNo, $pageSize, $filter=array()) {
		$result = $this->getDefaultRecords($pageNo, $pageSize);	
		$this->dao->fresh();

		if(count($filter) > 0) {
			$this->dao->select($filter);			
		}

		if(!empty($conditions) && !empty($paramters)) {			
			$this->dao->from($this->getTable());
			$this->dao->where($conditions, $paramters);

			$result["recordCount"] = $this->dao->queryCount();
			$result["totalPage"] = intval(ceil($result["recordCount"] / $pageSize));

			$this->dao->paging($pageNo, $pageSize);
			$result["records"] = $this->dao->queryAll();
		}

		return $result; 
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
		return $this->innerRecordsByCondition($conditions, $paramters, $pageNo, $pageSize, $filter);
	}

	/**
	*	借由id取得一筆資料
	*
	*	@param array  $id 
	*	@return array 資料
	*/
	public function getRecordById($id) {
		$primaryKey = $this->getPrimaryAttribute();
		$attributes = array("$primaryKey" => $id);

		return $this->getRecord($attributes);
	}

	/**
	*	借由條件取得一筆資料
	*
	*	@param array $attributes 資料要符合該屬性	
	*	@return array 資料
	*/
	public function getRecord($attributes) {		

		if(!empty($attributes)) {
			$where = array('and');
			$param = array(); 

			foreach($attributes as $name => $attribute) {
				array_push($where, "$name=:$name");
				$param[":$name"] = $attribute;
			}

			$result = $this->getRecordByCondition($where, $param);	
		}

		return $result; 
	}

	/**
	*	借由屬性取得一筆資料
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return array 資料
	*/
	public function getRecordByCondition($conditions, $paramters) {
		$record = array();

		if(!empty($conditions) && !empty($paramters)) {			
			$this->dao->fresh();
			$this->dao->from($this->getTable());
			$this->dao->where($conditions, $paramters);
						
			$record = $this->dao->query();
		}

		return $record; 
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
        $this->dao->fresh();

        if(!empty($conditions) && !empty($parameters)) {
            $this->dao->from($this->getTable());
            $this->dao->where($conditions, $parameters);
            return $this->dao->queryCount();
        }
        else {
            throw new DbOperationException("Can not use empty condition or parameters for count.");
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
        $this->dao->fresh();

        if(empty($attributes)) {
            $attributes = array("1" => 1);
        }

        $where = array('and');
        $param = array();

        foreach($attributes as $name => $attribute) {
            array_push($where, "$name=:$name");
            $param[":$name"] = $attribute;
        }

        if(!empty($where) && !empty($param)) {
            $this->dao->from($this->getTable());
            $this->dao->where($where, $param);
            return $this->dao->queryCount();
        }
        else {
            throw new DbOperationException("Can not use empty $where or $param for count.");
        }
    }
	
	/**
	*	新增一筆一資料到資料集
	*
	*	@param array $attributes 要新增的屬性參及值
	*	@return int 成功新增的資料數量 	
	*/
	public function create($attributes) {
		if($this->validAttributes($attributes)) {
			$this->dao->fresh();
			$effectRow = $this->dao->insert($this->getTable(), $attributes);
			return $effectRow;
		}
		else {			
			throw new InvalidAccessParamsException("Creating invalid attributes model to collection " . $this->getTable(), 1);
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
		// TODO check attributes		
		$table = $this->getTable();
		$effectRows = $this->dao->multipleInsert($table, $attributes, $values);
		return $effectRows;
	}

	/**
	*	Get last created model.
	*
	*	@return Model If model not found throw exception.
	*/
	public function lastCreated() {
		$id = $this->dao->lastInsertId();		
		if($id > 0) {
			$model = $this->getModelName();
			return new $model($id, $this->dao);
		}
		else {
			throw new DbOperationException("Last created model not found.", 1);			
		}
	}

	/**	
	*	Destory models by id list
	*
	*	@param $ids The id list want to destory.
	*	@return int The counter of effect row.
	*/
	public function multipleDestroyById($ids) {
		$this->dao->fresh();

		$table 	= $this->getTable();
		$where 	= array("or");
		$params = array();
		$primaryKey = $this->getPrimaryAttribute();

		foreach($ids as $index => $id) {
			array_push($where, "$primaryKey=:id$index");
			$params[":id$index"] = $id;
		}

		return $this->dao->delete($table, $where, $params);
	}

    /**
     * Update attribute by id list.
     *
     * @param $ids array The id list want to update. e.g. array(1,2,3,4...)
     * @param $attribute array New attribute want to update. e.g. array('name' => 'new name', ...)
     * @return int The counter of effect rows.
     */
    public function multipleUpdateById($ids=array(), $attribute=array()) {
        $this->dao->fresh();

        $table = $this->getTable();
        $where = array("or");
        $params = array();
        $primaryKey = $this->getPrimaryAttribute();

        foreach($ids as $index => $id) {
            array_push($where, "$primaryKey=:id$index");
            $params[":id$index"] = $id;
        }

        return $this->dao->update($table, $attribute, $where, $params);
    }
}



?>