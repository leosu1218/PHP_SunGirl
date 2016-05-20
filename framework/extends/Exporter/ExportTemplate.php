<?php
/**
*	ExportTemplate 樣版設定
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package Model
*	@author Jai Chien <jaichien@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

interface ExportTemplate {
	
	/**
	*	ExportTemplate use output data format.
	*
	*	@return array()
	*/
	public function getField();

}
?>