<?php
/**
*	Session Collection 資料來源的集合
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Collection
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once(dirname(__FILE__) . '/Collection.php');
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );

/**
*	SessionCollection 存取Session的資料集
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Collection
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
abstract class SessionCollection implements Collection {	

	public function __construct() {		
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
		throw new Exception("Not impletement the method.");
	}

	/**
	*	藉由屬性取得一個Model物件
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@return Model 取得到的Model物件
	*/
	public function get($attributes) {
		throw new Exception("Not impletement the method.");
	}
	
	/**
	*	借由條件取得一個Model物件
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return Model 取得到的Model物件
	*/
	public function getByCondition($conditions, $paramters) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	Get default record data structure.
	*
	*	@return array()
	*/
	private function getDefaultRecords($pageNo, $pageSize) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	藉由屬性取得一個資料集
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集, 如果找不到回傳array( ... "totalPage" => 0);
	*/
	public function getRecords($attributes, $pageNo=1, $pageSize=1000) {
		throw new Exception("Not impletement the method.");
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
	public function getRecordsByCondition($conditions, $paramters, $pageNo, $pageSize) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	借由id取得一筆資料
	*
	*	@param array  $id 
	*	@return array 資料
	*/
	public function getRecordById($id) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	借由條件取得一筆資料
	*
	*	@param array $attributes 資料要符合該屬性	
	*	@return array 資料
	*/
	public function getRecord($attributes) {		
		throw new Exception("Not impletement the method.");
	}

	/**
	*	借由屬性取得一筆資料
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return array 資料
	*/
	public function getRecordByCondition($conditions, $paramters) {
		throw new Exception("Not impletement the method.");
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

			return $this->dao->insert($this->getTable(), $attributes);	
		}
		else {			
			return array();
		}		
	}

}



?>