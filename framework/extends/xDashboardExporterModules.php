<?php
/**
*  ExporterFactory code.
*
*  PHP version 5.3
*
*  @category ExporterFactory
*  @package ExporterFactory
*  @author Jai Chien <jaichien@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/


require_once( FRAMEWORK_PATH . 'extends/Exporter/PieChartJsonExportFactory.php' );
require_once( FRAMEWORK_PATH . 'extends/Exporter/CurveChartJsonExportFactory.php' );


class xDashboardExporterModules {

    public function __construct( $category ) {
        $this->category = $category;
    }

    public function create()
    {
        return $this->getExportFactory( $this->category );
    }

    /**
    *   get really Export entity
    *   
    *   @param string category (ex. 'ReturnedExcel' or 'PickupExcel')
    *   @return collection object
    */
    public function getExportFactory( $category ) {
        $export = $this->getExportList();
        if( array_key_exists($category, $export) ) {
            $className = $export[ $category ];
            return new $className();
        }
        else {
            throw new Exception("Undefined $category export factory.", 1);
        }
    }

    public function getExportList()
    {
        return array(
                "PieChartJson"=>"PieChartJsonExportFactory",
                "CurveChartJson"=>"CurveChartJsonExportFactory",
            );
    }

}




?>