<?php



/**
* Simple Project Framework. only Router, Controller, REST-API and Library-Management Model.
*/
class Synature
{
	public function __construct( $config = array() )
	{
		require_once( $config[ 'frameworkRoot' ] . 'system/RouterFactory.php' );
		$router = new RestRouter($config[ 'logPath' ]);
		$router->load( $config[ 'router' ] );

	}
}











?>