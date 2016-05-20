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
require_once( dirname(__FILE__) . '/../ExportTemplate.php' );

class JsonPieChartTemplate implements ExportTemplate
{

	public function assign( $entity ){

		if( $entity instanceof iMediaPieChartEntity ){

			$result = array();

			$records = $entity->getRecords();
			$fields = $this->getField();
			foreach ($records as $key => $record) {
				$temp = array();

				foreach ($fields as $index => $field) {
					
					if(array_key_exists($field, $record)){
						$temp[ $field ] = $record[ $field ];	
					}else{
						$temp[ $field ] = null;
					}
					
				}
				
				array_push($result, $temp);

			}

			return array("records"=>$result);

		}
		else{
            throw new Exception("Invalid instance type of [iMediapPieChartEntity].", 1);
        }

	}

	public function getField()
	{
		return array(
				"value",
				"label"
			);
	}

}

?>