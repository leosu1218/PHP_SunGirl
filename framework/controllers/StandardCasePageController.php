<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/StandardCasePageCollection.php' );
require_once( FRAMEWORK_PATH . 'models/StandardCasePage.php' );


/**
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Ares <ares@synctech-infinity.com>
 * @copyright 2016 synctech.com
 */
class StandardCasePageController extends RestController {

    private $collection;

    public function __construct(&$dao=null) {
        parent::__construct();
        $this->collection = new StandardCasePageCollection($dao);
    }

    /**
     *  Get condition for search product method from http request querysting.
     *  There will filter querystring key, values.
     *
     *  @return
     */
    public function getCondition() {

        $condition = array();
        $this->getQueryString("id", $condition);
        $this->getQueryString("classification", $condition);
        return $condition;
    }
    
    /**
     * GET:     /standard/case/get/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",        "StandardCasePageController", "searchByClient(<pageNo>,<pageSize>,<querystring>)
     * @param $pageNo
     * @param $pageSize
     * @param $querystring
     * @return mixed
     */
    public function searchByClient($pageNo, $pageSize, $querystring) {
        $condition  = $this->getCondition();
        $records    = $this->collection->searchRecords($pageNo, $pageSize, $condition);
        return $records;
    }






}




?>
