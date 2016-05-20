<?php
/**
*	Interpreter 反覆器
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Wayne <wayne@synctech.ebiz.tw>
*	@copyright 2014 synctech.com
*/

interface Interpreter
{
	public function getNext();

	public function hasNext();
}
?>