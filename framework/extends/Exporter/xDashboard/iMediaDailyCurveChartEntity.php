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
require_once( dirname(__FILE__) . '/CurveChartEntity.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFileCollection.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFile/SearchUserId.php' );
require_once( FRAMEWORK_PATH . '/collections/RawFile/JoinIMediaEventRawDailyInterval.php' );


class iMediaDailyCurveChartEntity extends CurveChartEntity
{
    private $actor              = null;
    private $searchStatements   = array();
    private $joinStatement      = array();

    /**
     * Construct the object.
     */
    public function __construct() {
        $this->exportRecords    = array();
        $this->data             = array();
        $this->searchStatements = array(new SearchUserId());
        $this->joinStatement    = array(new JoinIMediaEventRawDailyInterval());
        $this->collection       = new RawFileCollection();
    }

    /**
     * Set actor for permission control.
     * @param null $actor
     */
    public function setActor( $actor = null ){
        $this->actor = $actor;
        $this->collection->setActor($this->actor);
    }

    /**
     *   ExportEntity set Resource data.
     *
     *   @param $data
     */
    public function setResource($data=array()) {
        $this->data = $data;
    }

    /**
     * Check resource's params startDate, endDate
     * @throws Exception
     */
    private function checkDate() {
        if(!array_key_exists("startDate", $this->data)) {
            throw new Exception("Missing requirement params for resource [startDate].");
        }

        if(!array_key_exists("endDate", $this->data)) {
            throw new Exception("Missing requirement params for resource [endDate].");
        }

        $startDate  = strtotime($this->data['startDate']);
        $endDate    = strtotime($this->data['endDate']);

        if(!$startDate) {
            throw new Exception("Invalid datetime string format for resource [startDate]: " . $this->data['startDate']);
        }

        if(!$endDate) {
            throw new Exception("Invalid datetime string format for resource [endDate]: " . $this->data['endDate']);
        }

        if($startDate > $endDate) {
            throw new Exception("Invalid params value for resource [startDate] should less than [endDate].");
        }
    }

    /**
     * Get date labels structure.
     * @return array e.g. array('2015-01-02', '2015-01-03', '2015-01-04', .... );
     */
    private function getLabels() {
        $this->checkDate();

        $labels     = array();
        $startDate  = strtotime($this->data['startDate']);
        $endDate    = strtotime($this->data['endDate']);

        do {
            array_push($labels, date("Y-m-d", $startDate));
            $startDate = strtotime("+1 day", $startDate);
        } while($startDate <= $endDate);

        return $labels;
    }

    /**
     * Query records from db by collection.
     * @return array Collection's records array.
     */
    private function queryRecords() {
        $pageNo     = 1;
        $pageSize   = 999999;
        $search     = $this->data;
        return $this->collection->executeGetRecords($pageNo, $pageSize, $search, $this->joinStatement, $this->searchStatements);
    }


    /**
     * Create records .
     * @param array $labels e.g. array('2015-01-02', '2015-01-03', '2015-01-04', .... );
     * @return array e.g. array(
     *                  array(
     *                      'label' => 'all',
     *                      'values' => array(5, 5, 8)
     *                  ),
     *                  array(
     *                      'label' => 'male',
     *                      'values' => array(5, 5, 8)
     *                  ),
     *                  array(
     *                      'label' => 'female',
     *                      'values' => array(5, 5, 8)
     *                  )
     *              );
     */
    private function createRecords($labels=array()) {
        $this->initialExportRecords(count($labels));
        $result = $this->queryRecords();

        foreach($result['records'] as $key => $resultItem) {
            $index      = array_search($resultItem['start_datetime'], $labels);
            $genderCode = $resultItem['gender'];
            $value      = $resultItem['count'];
            $this->setExportRecordsValue($genderCode, $index, $value);
        }

        return $this->exportRecords;
    }

    /**
     * Initialization export records.
     * @param int $length
     */
    private function initialExportRecords($length=0) {
        $this->exportRecords = array(
            array(
                'label' => 'all',
                'values' => array_fill(0, $length, 0)
            ),
            array(
                'label' => 'male',
                'values' => array_fill(0, $length, 0)
            ),
            array(
                'label' => 'female',
                'values' => array_fill(0, $length, 0)
            )
        );
    }

    /**
     * Set new value into export record.
     * @param int $genderCode
     * @param int $index
     * @param int $value
     */
    private function setExportRecordsValue($genderCode=0, $index=0, $value=0) {
        if(array_key_exists($genderCode, $this->exportRecords)) {
            $this->exportRecords[$genderCode]['values'][$index] = $value;

            $maleValue      = $this->exportRecords[1]['values'][$index];
            $femaleValue    = $this->exportRecords[2]['values'][$index];
            $this->exportRecords[0]['values'][$index] = $maleValue + $femaleValue;
        }
    }

    /**
     *   Export records.
     *
     *   @return array Export's records.
     */
    public function getRecords() {

        $labels = $this->getLabels();
        $records = $this->createRecords($labels);

        return array(
            'labels' => $labels,
            'records' => $records
        );
    }

}


?>