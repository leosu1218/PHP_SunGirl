<?php
/**
*	StandardCasePageCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package StandardCasePage
*	@author Ares <ares@synctech.ebiz.tw>
*	@copyright 2016 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/StandardCasePage.php' );

require_once( dirname(__FILE__) . "/StandardCasePage/SearchStandardCasePageClassification.php" );

/**
*	StandardCasePageCollection Access StandardCasePage entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package StandardCasePage
*	@author Ares <ares@synctech.ebiz.tw>
*	@copyright 2016 synctech.com
*/
class StandardCasePageCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	public function __construct(&$dao=null) {
        parent::__construct($dao);

        $this->searchConditions = array(
            new SearchStandardCasePageClassification(),
        );

    }

	/**
     * Search records.
     *
     * @param int $pageNo Result's page number.
     * @param int $pageSize Result's page size.
     * @param array $search Search condition's params.
     * @return array The result records.
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    public function searchRecords($pageNo, $pageSize, $search=array(),$blacklist = array()) {
        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array(
            's.id id',
            's.create_datetime create_datetime',
            's.title title',        
            's.pdf_file_name pdf_file_name',
            's.classification classification'
        );

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $this->searchConditions);

        $this->dao->from("$table s");
        $this->dao->group('s.id');
        $this->dao->order('s.id DESC');
        $this->dao->where($conditions,$params);
        $this->dao->select($select);

        $result['recordCount'] = intval($this->dao->queryCount());
        $result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();
        return $result;
    }

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "standard_case_page";
	}

	public function getModelName() {
		return "StandardCasePage";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {


		if(array_key_exists("id", $attributes) ){
        	throw new Exception("Error cannot has param [id]", 1);
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
