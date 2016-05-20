<?php

/**
*	ExportEntity code
*
*	PHP version 5.3
*
*	@category Entity
*	@package Export
*	@author Rex Chen <rexchen@synctech-infinity.com>, Jai Chien <jaichien@synctech-infinity.com>
*	@copyright 2015 synctech.com
*/
interface ExportEntity
{
	/**
	*	ExportEntity set Resource data.
	*
	*	@param $data	
	*/
    public function setResource( $data );

    /**
	*	ExportEntity get records.
	*
	*	@return $records	
	*/
    public function getRecords();
}


?>