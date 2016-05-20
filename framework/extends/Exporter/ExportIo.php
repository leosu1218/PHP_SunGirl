<?php
/**
*	ExportIo 介面
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Exporter
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

interface ExportIo {
	
	/**
	*	ExportIo use output data format is array.
	*
	*	@return array()
	*/
	public function toArray();
	
	/**
	*	ExportIo get data in $io self buffer.
	*
	*	@return array()
	*/
	public function setResource( $data );

	/**
	*	ExportIo send data by $io self.
	*
	*	@return array()
	*/
	public function send();

}

?>