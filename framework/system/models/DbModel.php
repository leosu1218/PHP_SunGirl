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
require_once( FRAMEWORK_PATH . 'extends/DbHero/Db.php' );
require_once( FRAMEWORK_PATH . 'system/exception/InvalidAccessParamsException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/DataAccessResultException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/DbOperationException.php' );

/**
*	DbModel 存取DataBase的資料
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
abstract class DbModel implements Model {

	public $dao;
	public $id;
	private $attributes = array();

	/**
	*	Construct the class.
	*
	*	@param $id int The entity's id in table.
	*/
	public function __construct($id, DbHero &$dao=null) {
        if(is_null($dao)) {
            $this->dao = new Db(DB_NAME);
        }
        else {
            $this->dao = $dao;
        }

      	$this->id = $id;
   	}

   	/**
   	*	Retrun this entity id.
   	*
   	*	@return mixed
   	*/
   	public function getId() {
   		return $this->id;
   	}

   	/**
   	*	Retrun this entity relation tables key.
   	*
   	*	@return mixed
   	*/
   	public function getReference() {
   		return array();
   	}

   	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	abstract function getTable();

   	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	abstract function validAttributes($attributes);

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	abstract function getPrimaryAttribute();

   	/* Model interface methods. */

   	/**
   	*	Get a attribute value from the model.
   	*
   	*	@param string $name The name of the attribute.
   	*	@return mixed attribute value.
   	*/
   	public function getAttribute($name) {
   		$record = $this->toRecord();

   		if(!empty($record)) {
   			if(array_key_exists($name, $record)) {
   				return $record[$name];
   			}
   		}
   		else {
   			throw new Exception("Model record not exsits", 1);   			
   		}
   	}

   	/**
	*	將model物件資料轉換成Array的方法
	*
	*	@param array $options 轉換的參數
	*	@return array 物件的資料
	*/
	public function toRecord( $options = array() ) {

        $primaryKey = $this->getPrimaryAttribute();

        $where = "$primaryKey=:$primaryKey";
        $params = array(":$primaryKey" => $this->getId());

        $this->dao->fresh();
        $this->dao->from($this->getTable());
        $this->dao->where($where, $params);

        $this->attributes = $this->dao->query();
		
		return $this->attributes;
	}

	/**
	*	Get relations model by forienkey.
	*
	*	@param $attributes The attributes forienkey.
	*	@return Model The reference model
	*/
	public function reference($attribute) {		

		$referenceTables = $this->getReference();
		
		if(array_key_exists($attribute, $referenceTables)) {
			$record = $this->toRecord();
			$reference = $referenceTables[$attribute];
			$id = $record[ $reference[0] ];
			$className = $reference[1];
			return new $className( $id );
		}
		else {
			throw new Exception("Invalid reference attribute", 1);	
		}	
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
     * Increase attributes's value
     * @param array $attributes {"atr1"=>inc1, "atr2"=>inc2 ... }
     * @return int
     */
    public function increaseAttributes($attributes=array()) {

        $table = $this->getTable();
        $primaryKey = $this->getPrimaryAttribute();

        $set = "";
        $params = array(":id" => $this->getId());
        foreach($attributes as $attribute => $increase) {
            $set .= "$attribute=$attribute+:$attribute,";
            $params[":$attribute"] = $increase;
        }
        $set = substr($set, 0, -1);

        $sql = "UPDATE $table SET $set WHERE $primaryKey=:id;";
        $rowCount = $this->dao->runExecute($sql, $params);
        return $rowCount;
    }

	/**
	*	執行更新的動作
	*
	*	@param array $attributes 要更新的資料內容
	*	@return boolean 成功回傳true
	*/
	public function update($attributes) {
		
		$this->dao->fresh();

		$primaryKey = $this->getPrimaryAttribute();
		$where 		= "$primaryKey=:$primaryKey";
		$params 	= array(":$primaryKey" => $this->getId());

		if($this->validAttributes($attributes)) {
			$rowNumber = $this->dao->update($this->getTable(), $attributes, $where, $params);
			if( $rowNumber==1 ){
				return true;
			}
			else {
				return false;
			}		
		}
		else {
			throw new Exception("Invalid reference attribute", 1);
		}
	}

	/**
	*	刪除資料到資料集
	*	
	* 	@return int 成功新增的資料數量  
	*/
	public function destroy(){
	   	$this->dao->fresh();
	   	$primaryKey= $this->getPrimaryAttribute();
	   	$where = "$primaryKey=:id";
	   	$param = array(":id" => $this->getId());

	   	return $this->dao->delete($this->getTable(), $where, $param);
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
}



?>