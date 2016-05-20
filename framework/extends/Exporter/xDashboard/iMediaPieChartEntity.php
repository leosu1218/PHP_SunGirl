<?php

/**
*   iMediaPieChartEntity code
*
*   PHP version 5.3
*
*   @category Entity
*   @package Export
*   @author Rex Chen <rexchen@synctech-infinity.com>, Jai Chien <jaichien@synctech-infinity.com>
*   @copyright 2015 synctech.com
*/
require_once( dirname(__FILE__) . '/../ExportEntity.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFileCollection.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFile/SearchUserId.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFile/JoinIMediaEventRawCounter.php' );


class iMediaPieChartEntity implements ExportEntity {

	private $records = array();
    private $actor = null;

    public function setActor( $actor = null ){
        $this->actor = $actor;
    }

    /**
    *   ExportEntity set Resource data.
    *
    *   @param $data
    */
    public function setResource( $data ) {
        $collection = new RawFileCollection();
        $collection->setActor($this->actor);
        
        $pageNo             = 1;
        $pageSize           = 9999;
        $joinStatement      = array(new JoinIMediaEventRawCounter());
        $searchConditions   = array(new SearchUserId());
        $search = $data;

        $result = $collection->executeGetRecords($pageNo, $pageSize, $search, $joinStatement, $searchConditions);

        $this->records = $result['records'];
    }

    /**
    *   ExportEntity get records.
    *
    *   @return $records
    */
    public function getRecords() {
        return $this->records;
    }

}


?>