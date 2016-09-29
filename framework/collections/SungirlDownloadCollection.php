<?php
/**
*	HomePageImageCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package HomePageImage
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/SungirlDownload.php' );

/**
*	HomePageImageCollection Access HomePageImage entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package HomePageImage
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class SungirlDownloadCollection extends PermissionDbCollection {
	
	/* PermissionDbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "sungirl_download";
	}

	public function getModelName() {
		return "SungirlDownload";
	}

    public function searchRecords($pageNo, $pageSize, $search=array() , $order) {

    $result = $this->getDefaultRecords($pageNo, $pageSize);
    $table = $this->getTable();
    $conditions = array('and','1=1');
    $params = array();

    $this->dao->fresh();
    $this->dao->select(array(
        'sd.*'
    ));


    $this->dao->from("$table sd");
    $this->dao->group('sd.id');
    $this->dao->order($order);

    array_push($conditions, 'sd.ready_time <= :ready_time');
    $params[':ready_time'] = $search['ready_time'];

    if(array_key_exists("id", $search)) {
        array_push($conditions, 'sd.id <= :id');
        $params[':id'] = $search['id'];
    }

    $this->dao->where($conditions,$params);
    $result['recordCount'] = intval($this->dao->queryCount());
    $result['totalRecord'] = $result['recordCount'];
    $result["totalPage"] = intval(ceil($result['totalRecord'] / $pageSize));
    $this->dao->paging($pageNo, $pageSize);
    $result["records"] = $this->dao->queryAll();

    return $result;
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
