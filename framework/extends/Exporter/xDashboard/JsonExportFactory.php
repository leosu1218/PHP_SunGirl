<?php


require_once( dirname(__FILE__) . '/../ExportFactory.php' );

abstract class JsonExportFactory implements ExportFactory
{

	private $io = null;

	/**
	*	ExportFactory use template output format.
	*
	*	@return $template	
	*/
	abstract function createTemplate();

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $type string The trade result state.	
	*	@return $entity
	*/
	abstract function createEntity( $type );

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $type string The trade result state.	
	*	@return $entity
	*/
	abstract function createIo();

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $io object 	
	*	@return boolean  effect success or error
	*/
	public function open( $io )
	{
		if(!empty($io)){
			$this->io = $io;	
		}
		
		return true;
	}

	/**
	*	ExportFactory use $template and $entity fellow flow to use.
	*
	*	@param $io object 	
	*	@return boolean  effect success or error
	*/
	public function write( ExportTemplate $template , ExportEntity $entity )
	{
		$output = $template->assign($entity);
		$this->io->setResource($output);
	}

	/**
	*	ExportFactory use entity output records and prepare data
	*	for export format.
	*
	*	@param $io object 	
	*	@return boolean  effect success or error
	*/
	public function close()
	{

	}

	public function getDateTime()
	{
		$datetime = new DateTime("now");
        $date = $datetime->format('Y-m-d His')." ";
        return $date;
	}

	

}

?>