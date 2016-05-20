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
require_once( FRAMEWORK_PATH . '/collections/RawFile/JoinIMediaEventRawGenderHourly.php' );


class iMediaGenderHourlyCurveChartEntity extends CurveChartEntity
{

    private $response = array(
        "labels"=>array(),
        "records"=>array(),
    );

    private $actor              = null;
    private $searchStatements   = array();
    private $joinStatement      = array();

    /**
     * Construct the object.
     */
    public function __construct() {
        $this->searchStatements = array(new SearchUserId());
        $this->joinStatement    = array(new JoinIMediaEventRawGenderHourly());
    }

    /**
     * Set actor for permission control.
     * @param null $actor
     */
    public function setActor( $actor = null ){
        $this->actor = $actor;
    }

    /**
     *   ExportEntity set Resource data.
     *
     *   @param $data
     */
    public function setResource( $data ) {
        $this->collection = new RawFileCollection();
        $this->collection->setActor($this->actor);
        $this->data = $data;

        $this->searchRecords();
    }

    /**
     *   利用設定的區間 一一去取得所有區間的Records
     *   作區間分類的準備
     *
     */
    private function searchRecords(){
        $result = array();
        $intervals = $this->getIntervals();

        foreach ($intervals as $index => $interval) {
            $data = $this->getIntervalRecords( $interval );
            array_push($this->response['labels'],$interval[0]);

            foreach ($data as $key => $record) {
                // add new array.
                if (!array_key_exists($record['label'], $result)) {
                    $result[$record['label']] = @array_fill(0, $index, 0);
                }

                $result[$record['label']][$index] = $record['count'];
            }

            // add 0 to no count item.
            foreach($result as $label => $item) {
                if(count($item) < $index + 1 ) {
                    $result[$label][$index] = 0;
                }
            }
        }

        $this->response['records'] = $this->process($result);
    }

    /**
     *   將資料時間區間搜尋出的Records準備成每個type的分類
     *
     */
    private function process( $result ){
        $records = array();
        foreach ($result as $label => $item) {
            array_push($records, array(
                "label" => $label,
                "values" => $item
            ));
        }

        return $records;
    }

    /**
     *   取得default的values
     *
     */
    private function getDefaultValues(){
        $default = array();
        $intervals = $this->getIntervals();
        foreach ($intervals as $key => $value) {
            array_push($default, 0);
        }
        return $default;
    }

    /**
     *   區間設定config
     *
     */
    private function getIntervals(){
        return array(
            array("00:00","01:00",0,0),
            array("01:00","02:00",1,2),
            array("02:00","03:00",2,3),
            array("03:00","04:00",3,4),
            array("04:00","05:00",4,5),
            array("05:00","06:00",5,6),
            array("06:00","07:00",6,7),
            array("07:00","08:00",7,8),
            array("08:00","09:00",8,9),
            array("09:00","10:00",9,10),
            array("10:00","11:00",10,11),
            array("11:00","12:00",11,12),
            array("12:00","13:00",12,13),
            array("13:00","14:00",13,14),
            array("14:00","15:00",14,15),
            array("15:00","16:00",15,16),
            array("16:00","17:00",16,17),
            array("17:00","18:00",17,18),
            array("18:00","19:00",18,19),
            array("19:00","20:00",19,20),
            array("20:00","21:00",20,21),
            array("21:00","22:00",21,22),
            array("22:00","23:00",22,23),
            array("23:00","00:00",23,0),
        );
    }

    /**
     *   依照時間區間的值設定完基本參數後去做SearchRecords
     *   @param $timeInterval 時間區間
     *   @return $result['records'] 搜尋出的記錄
     */
    private function getIntervalRecords( $timeInterval ){
        $pageNo = 1;
        $pageSize = 999999;
        $search = array_merge($this->data, array("timeInterval"  => $timeInterval));

        $result = $this->collection->executeGetRecords($pageNo, $pageSize, $search, $this->joinStatement, $this->searchStatements);

        return $this->replaceLabelName($result['records']);
    }

    /**
     * Process query result, add or replace 'all', 'male', 'female' label.
     * @param $record
     * @return array
     */
    private function replaceLabelName($record) {

        $countAll = 0;

        foreach ($record as $index => $item) {
            $countAll += (int)$item["count"];
            if($record[$index]["label"] == "g:1") {
                $record[$index]["label"] = "male";
            }
            else {
                $record[$index]["label"] = "female";
            }
        }

        array_push($record, array(
            "label" => "all",
            "count" => $countAll
        ));

        return $record;
    }

    /**
     * ExportEntity get records.
     * @return array
     */
    public function getRecords()
    {
        return $this->response;
    }

}


?>