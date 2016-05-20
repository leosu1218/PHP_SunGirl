<?php 


require_once( dirname(__FILE__) . '/xDashboard/JsonExportFactory.php' );
require_once( dirname(__FILE__) . '/xDashboard/JsonPieChartTemplate.php' );
require_once( dirname(__FILE__) . '/xDashboard/iMediaPieChartEntity.php' );
require_once( dirname(__FILE__) . '/xDashboard/JsonIo.php' );


/**
*  PickupExcelExportFactory can help you exporter file
*
*	PHP version 5.3
*
*	@category OrderPickingEntity
*	@package Exporter
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*
*/
class PieChartJsonExportFactory extends JsonExportFactory
{

    public function createIo()
    {
        return new JsonIo();
    }

	public function createTemplate()
	{
		return new JsonPieChartTemplate();
	}

	public function createEntity( $entity_type )
	{
		return $this->getEntity( $entity_type );
	}

	/**
    *   get collection list entity
    *   
    *   @return array
    */
    private function getEntityList(){

        return array(
            "iMedia"=>"iMediaPieChartEntity",
        );
    }    

    /**
    *   get really type collection entity
    *   
    *   @param string category (ex. 'groupbuying' or 'gernal')
    *   @return collection object
    */
    private function getEntity( $type ) {
        $entitys = $this->getEntityList();
        if( array_key_exists($type, $entitys) ) {
            $className = $entitys[ $type ];
            return new $className();
        }
        else {
            throw new Exception("Undefined $type collection.", 1);
        }
    }

}




?>