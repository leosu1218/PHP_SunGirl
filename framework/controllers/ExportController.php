<?php
/**
*  ExportController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'extends/xDashboardExporterModules.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );


class ExportController extends RestController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get condition for search product method from http request querystring.
     * There will filter querystring key, values.
     *
     * @return array
     */
    public function getCondition() {
        $condition = array();
        $this->getQueryString("userId", $condition);
        $this->getQueryString("startDate", $condition);
        $this->getQueryString("endDate", $condition);
        $this->getQueryString("boxId", $condition);
        $this->getQueryString("camId", $condition);
        return $condition;
    }

    /**
    *
    *   GET:  /export/<category:\w+>/<entityType:\w+>/<querystring:\w+>
    *
    *   @param $category string which category you wanted used ( ex. 'PieChartJson' )
    *   @param $entityType stirng ( ex. 'iMedia' )
    *   @param $querystring string
    */
    public function export( $category, $entityType, $querystring ){

        $export = $this->getExport( $category );
        $io     = $export->createIo();

        $actor  = PlatformUser::instanceBySession();

        if( $export->open($io) ){

            $actor                  = PlatformUser::instanceBySession();
            $condition              = $this->getCondition();
            $condition["userId"]    = $actor->getId();

            $template   = $export->createTemplate();
            $entity     = $export->createEntity($entityType);

            $entity->setActor($actor);
            $entity->setResource($condition);

            $export->write( $template, $entity );
            $export->close();

            return $io->toArray();
        }
        else {
            throw new Exception("Error about open export io.", 1);
        }
                
    }

    public function getExport( $category )
    {
        $factory = new xDashboardExporterModules( $category );
        return $factory->create();
    }


}




?>