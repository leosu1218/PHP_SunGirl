<?php
/**
*  PermissionController code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/
require_once( FRAMEWORK_PATH . 'collections/PermissionCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );


class PermissionController extends RestController {

   	public function __construct() {
      	parent::__construct();      	      	
   	}

   	/**
   	*	GET: 	/permission/list
   	*  	Get all of permission
   	*
   	*  	@return  array Struct of array( 
   	*				'name' => <group name>,
   	*				'permissions' => array( 
   	*					<permission id>, 
   	*					<permission id>, 
   	*					....   	
   	*			)
   	*/
   	public function getList() {
      	try {
      		$user = PlatformUser::instanceBySession();
      		$permissions = new PermissionCollection();
      		$permissions->setActor($user);
            
      		$records = $permissions->getRecords();
      		$this->responser->send($records, $this->responser->OK());

    	}
    	catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Unauthorized() );         
	    }
      	catch ( Exception $e) {
         	// $data['message'] = SERVER_ERROR_MSG;
         	$data['message'] = $e->getMessage();
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}
}




?>