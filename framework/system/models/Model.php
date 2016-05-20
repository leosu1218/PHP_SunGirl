<?php
/**
*	Model 資料來源
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

interface Model {

    /**
     *	Get this model id.
     *
     *	@return mixed
     */
    public function getId();

	/**
   	*	Get a attribute value from the model.
   	*
   	*	@param string $name The name of the attribute.
   	*	@return mixed attribute value.
   	*/
   	public function getAttribute($name);

	/**
	*	將model物件資料轉換成Array的方法
	*
	*	@param array $options 轉換的參數
	*	@return array 物件的資料
	*/
	public function toRecord( $options = array() );

	/**
	*	設定之後要更新的資料內容
	*
	*	@param array $attributes 要更新的資料內容
	*/
	public function set($attributes);

    /**
     * Increase attributes's value
     * @param array $attributes {"atr1"=>inc1, "atr2"=>inc2 ... }
     * @return int
     */
    public function increaseAttributes($attributes=array());

	/**
	*	執行更新的動作
	*
	*	@param array $attributes 要更新的資料內容
	*	@return boolean 成功回傳true
	*/
	public function update($attributes);

	/**
	*	執行銷毀的動作
	*
	*	@return boolean 成功回傳true
	*/
	public function destroy();
}



?>