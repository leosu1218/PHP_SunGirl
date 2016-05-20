<?php
/**
*	DataBase Model 資料來源
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once(dirname(__FILE__) . '/Model.php');
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );


abstract class SessionModel implements Model {

	private $session;

	/**
	*	Construct the class.
	*
	*	@param $id int The entity's id in table.
	*/
	public function __construct($id) {      	
      	$this->session = GeneralSession::getInstance();
   	}

	/**
   	*	Get a attribute value from the model.
   	*
   	*	@param string $name The name of the attribute.
   	*	@return mixed attribute value.
   	*/
   	public function getAttribute($name) {
   		throw new Exception("Not impletement the method.");
   	}

	/**
	*	將model物件資料轉換成Array的方法
	*
	*	@param array $options 轉換的參數
	*	@return array 物件的資料
	*/
	public function toRecord( $options = array() ) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	設定之後要更新的資料內容
	*
	*	@param array $attributes 要更新的資料內容
	*/
	public function set($attributes) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	執行更新的動作
	*
	*	@param array $attributes 要更新的資料內容
	*	@return boolean 成功回傳true
	*/
	public function update($attributes) {
		throw new Exception("Not impletement the method.");
	}

	/**
	*	執行銷毀的動作
	*
	*	@return boolean 成功回傳true
	*/
	public function destroy() {
		throw new Exception("Not impletement the method.");
	}

}


?>