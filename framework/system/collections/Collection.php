<?php
/**
*	Collection 資料來源的集合
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Collection
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

interface Collection {

	/**
	*	借由ID取得一個Model物件
	*
	*	@param mixed $id Model的ID
	*	@return Model 取得到的Model物件
	*/
	public function getById($id);

	/**
	*	藉由屬性取得一個Model物件
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@return Model 取得到的Model物件
	*/
	public function get($attributes);
	
	/**
	*	借由條件取得一個Model物件
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return Model 取得到的Model物件
	*/
	public function getByCondition($conditions, $paramters);

	/**
	*	Get records by a id list.
	*
	*	@param $ids array Id list.
	*	@return array
	*/
	public function getRecordsByIds($ids);


	/**
	*	藉由屬性取得一個資料集
	*
	*	@param mixed $attributes 資料要符合該屬性
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集
	*/
	public function getRecords($attributes, $pageNo, $pageSize);

	/**
	*	借由條件取得一個資料集
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters  過濾出物件的參數
	*	@param int 	 $pageNo     限制資料分頁的頁碼
	*	@param int   $pageSize   限制資料分頁的大小
	*	@return array 資料集
	*/
	public function getRecordsByCondition($conditions, $paramters, $pageNo, $pageSize);

	/**
	*	借由id取得一筆資料
	*
	*	@param array  $id 
	*	@return array 資料
	*/
	public function getRecordById($id);

	/**
	*	借由條件取得一筆資料
	*
	*	@param array $attributes 資料要符合該屬性	
	*	@return array 資料
	*/
	public function getRecord($attributes);

	/**
	*	借由屬性取得一筆資料
	*
	*	@param mixed $conditions 過濾出物件的條件
	*	@param mixed $paramters 過濾出物件的參數
	*	@return array 資料
	*/
	public function getRecordByCondition($conditions, $paramters);

    /**
     * Count by conditions.
     * @param array $conditions
     * @param array $parameters
     * @return int
     */
    public function countByCondition($conditions=array(), $parameters=array());

    /**
     * Count by attributes
     * @param array $attributes
     * @return int
     */
    public function count($attributes=array());
	
	/**
	*	新增一筆一資料到資料集
	*
	*	@param mixed $options 要新增的資料參數
	*	@return int 成功新增的資料數量
	*/
	public function create($options);

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
	public function multipleCreate($attributes=array(), $values=array());

	/**
	*	Get last created model.
	*
	*	@return Model If model not found throw exception.
	*/
	public function lastCreated();

	/**	
	*	Destroy models by id list
	*
	*	@param $ids array The id list want to destroy.
	*	@return int The counter of effect rows.
	*/
	public function multipleDestroyById($ids);

    /**
     * Update attribute by id list.
     *
     * @param $ids array The id list want to update.
     * @param $attribute array New attribute want to update.
     * @return int The counter of effect rows.
     */
    public function multipleUpdateById($ids, $attribute=array());
}



?>