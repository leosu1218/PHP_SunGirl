<?php

require_once( dirname(__FILE__) . '/../ExportIo.php' );

class JsonIo implements ExportIo
{
	private $data = array();

	/**
	*	JsonIo set resource inside.
	*
	*	@param array()
	*/
	public function setResource( $data ){
		$this->data = $data;
	}

	/**
	*	JsonIo use send output.
	*
	*	@return boolean $isSuccess
	*/
	public function send(){
		throw new Exception("Not implements this function [ send ].", 1);
	}

	/**
	*	JsonIo use output data format is array.
	*
	*	@return array()
	*/
	public function toArray(){
		return $this->data;
	}

}

?>