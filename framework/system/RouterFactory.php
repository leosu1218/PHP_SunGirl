<?php 

require_once( FRAMEWORK_PATH . 'system/router/SnRouter.php' );
require_once( FRAMEWORK_PATH . 'system/router/RestRouter.php' );

class RouterFacotry
{
	public function create( $name )
	{
		$inst; //instance.

		// create router.
		if( strtolower( $name ) == 'restrouter' )
			$inst = new RestRouter;
		else
			throw new Exception("Undefined router name.", 1);

		// validator the router type.
		if( $inst instanceof SnRouter )
			return $inst;
		else
			throw new Exception("Invalid SnRouter Object.", 1);

			
	}
}





?>