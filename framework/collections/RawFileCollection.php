<?php
/**
*	RawFileCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Permission
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/RawFile.php' );

require_once( dirname(__FILE__) . "/RawFile/SearchUserId.php" );
require_once( dirname(__FILE__) . "/RawFile/JoinIMediaEventRawCounter.php" );
require_once( dirname(__FILE__) . "/RawFile/JoinIMediaEventRawTimeInterval.php" );


/**
*	RawFileCollection entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package Permission
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class RawFileCollection extends PermissionDbCollection {


	public function __construct(&$dao=null) {
        parent::__construct($dao);
    }

    /**
     *	Destory models by id list
     *
     *	@param $ids The id list want to destory.
     *	@return int The counter of effect row.
     */
    public function destroyByUserId($id) {
        $this->dao->fresh();

        $table 	= $this->getTable();
        $where = "user_id=:id";
        $params = array();
        $params[":id"] = $id;

        return $this->dao->delete($table, $where, $params);
    }

	/**
     * Append search condition's statement for search records sql.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function appendStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search, &$sqlStatements) {
        foreach ($sqlStatements as $key => $statement) {
            $statement->append($dao, $params, $conditions, $select, $search);
        }
    }

	/**
     * @param $pageNo
     * @param $pageSize
     * @param array $search
     * @param $joinStatement
     * @param $searchConditions
     * @return array
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    public function executeGetRecords( $pageNo, $pageSize, $search=array(), &$joinStatement, &$searchConditions ) {
        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array();

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $searchConditions);

        $this->dao->from("$table rf");
        $this->dao->where($conditions,$params);
        $this->dao->select($select);

        $result['recordCount'] = intval($this->dao->queryCount());
        $result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();

        return $result;
    }

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "raw_file";
	}

	public function getModelName() {
		return "RawFile";
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
}



?>
