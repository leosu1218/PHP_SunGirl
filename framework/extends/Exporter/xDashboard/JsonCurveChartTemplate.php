<?php
/**
*	JsonPieChartTemplate 樣版設定
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Export
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
require_once( dirname(__FILE__) . '/CurveChartEntity.php' );

class JsonCurveChartTemplate implements ExportTemplate
{

	public function assign( $entity ){

		if( $entity instanceof CurveChartEntity ){

			$records = $entity->getRecords();

			return $records;

		}
		else{
            throw new Exception("Invalid instance type of [iMediapCurveChartEntity].", 1);
        }

	}

	public function getField()
	{
		return array();
	}

}

?>