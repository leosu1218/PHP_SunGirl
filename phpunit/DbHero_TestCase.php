<?php
require_once(dirname(__FILE__) . '/../configs/sys.config.test.php');
require_once(dirname(__FILE__) . '/DbHero_ArrayDataSet.php');

/**
 * Class DbHero_TestCase
 */
abstract class DbHero_TestCase extends PHPUnit_Extensions_Database_TestCase {

    private $conn = null;
    private $name = "lifecom_skygo";

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection() {
        if ($this->conn === null) {
            $this->conn = $this->createDefaultDBConnection(TestPDO::getInstance(), $this->name);
        }
        return $this->conn;
    }

    public function createFlatCsvDataSet($table, $filename='') {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable($table, $filename);

        return $dataSet;
    }

    public function createFlatJsonDataSet($jsons=array()) {
        $config = array();
        foreach($jsons as $table => $filePath) {
            $jsonArray = json_decode(file_get_contents($filePath), true);
            $config[$table] = $jsonArray;
        }

        return new DbHero_ArrayDataSet($config);
    }
}


?>