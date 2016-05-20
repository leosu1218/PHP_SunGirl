<?php
/**
*  User Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@syncte-infinity.com>
*  @copyright 2014 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
include_once( FRAMEWORK_PATH . 'collections/IMediaEventTranslateRuleCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );

class UserController extends RestController {

   	public function __construct() {
    	parent::__construct();      
   	}

   	/**
   	*	GET: 	/user/self
   	*	Get self's user info API. There work under login state.
   	*	Response json: 
  	*	{
  	*		"account":"admin",
  	*		"domainName":"skygo.com.tw",
  	*		"groupId":"2",
  	*		"id":"1",
  	*		"name":"admin"
  	*	}   
   	*
   	*/
   	public function getSelf() {

   		$data = array();
   		try {   			
   			$response = $this->getFromSession();
   			$response = $this->getUserInfo($response);
   			$this->responser->send($response, $this->responser->OK());
   		}
   		catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Unauthorized() );         
	    }
   		catch(Exception $e) {
   			$data['message'] = SERVER_ERROR_MSG;
	        // $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
   		}
   	}

   	/**
   	*	GET: 	/user/self/permission
   	*	User get permission list itself.
   	*
   	*	Response json: 
  	*	{
  	*		"group": [
  	*			{
  	*				"id":"1",
  	*				"permission_id":"1",
  	*				"entity":"platform_user_group",
  	*				"entity_id":"0",
  	*				"action":"read",
  	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
  	*			},
  	*			... permissions depend group
  	*		],
  	*		"user": [
  	*			{
  	*				"id":"1",
  	*				"permission_id":"1",
  	*				"entity":"platform_user_group",
  	*				"entity_id":"0",
  	*				"action":"read",
  	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
  	*			},
  	*			... permissions depend user
  	*		]
  	*	}   	
   	*
   	*/
   	public function getSelfPermission() {
   		$data = array();
	    try {
	    	$userInfo = $this->getFromSession();
	    	$user = (new PlatformUserCollection())->getById($userInfo["id"]);

	    	$data["user"] = $user->getUserPermission();
	    	$data["group"] = $user->getGroupPermission();
	       	
	        $this->responser->send($data, $this->responser->OK());
	    }      
	    catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Unauthorized() );         
	    }
	    catch (Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        // $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
   	}

   	/**
   	*	POST: 	/user/<userType:\w+>/login
   	*  	User login API, 
   	*	Response json: 
  	*	{
  	*		"account":"admin",
  	*		"domainName":"skygo.com.tw",
  	*		"groupId":"2",
  	*		"id":"1",
  	*		"name":"admin"
  	*	}   	
   	*
   	*  	@param $this->receiver array array( 'domain' => <domain name>, 
   	*                             'account' => <user account>,
   	*                             'password' => <user password> )
   	*/
   	public function login() {

    	$data = array();
	    try {
	        // TODO refactoring receiver class.	        
	      	$this->vertify("domain", $this->receiver);
	      	$this->vertify("account", $this->receiver);
	      	$this->vertify("password", $this->receiver);

	        $domain     = $this->receiver['domain'];
	        $account    = $this->receiver['account'];
	        $password   = $this->receiver['password'];

	        $user 		= (new PlatformUserCollection())->login($domain, $account, $password);	        
	        $response 	= $this->getUserInfo($user);
	        
	        $this->saveToSession($user);	        	       
	        $this->responser->send($response, $this->responser->OK());
	    }      
	    catch (AuthorizationException $e) {
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->Forbidden() );         
	    }
	    catch (Exception $e) {
	        // $data['message'] = SERVER_ERROR_MSG;
	        $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
   	}

   	/**
   	*	Vertify variable in a params array. 
   	*	Response Bad Reqeust to browser when find invalid params.
   	*
   	*	@param $name string The name want to vertifing.
   	*	@param $parms array The params want to vertifing.
   	*/
   	public function vertify($name, $params) {
   		if(!array_key_exists($name, $params)) {
	    	$this->responser->send(array("message" => "Missing parameter [$name]."), $this->responser->BadRequest());
	    }
   	}

   	/**
   	*	POST: 	/user/logout
   	*  	User login API, 
   	*	Response json: 
  	*	{
  	*	}   	
   	*   	
   	*/
   	public function logout() {

    	$data = array();
	    try {
	    	$this->clearSession();
	        $this->responser->send($data, $this->responser->OK());
	    }      	    
	    catch (Exception $e) {
	        $data['message'] = SERVER_ERROR_MSG;
	        // $data['message'] = $e->getMessage();
	        $this->responser->send( $data, $this->responser->InternalServerError() );
	 	}
   	}

   	/**
   	*	Get user info from session variable.
   	*
   	*	@return array User info.
   	*/
   	private function getFromSession() {
   		return PlatformUser::getRecordBySession();  
   	}

   	/**
   	*	Clear all of session variables.
   	*
   	*/
   	private function clearSession() {   		
   		PlatformUser::clearSession();
   	}

   	/**
   	*	Saving user info into session variable.
   	*
   	*	@param $userInfo array user info.
   	*/
   	private function saveToSession($userInfo) {  
		PlatformUser::saveToSession($userInfo);
   	}

   	/**
   	*	Get user info from user row data for api response.
   	*
   	*	@param $userInfo array User data of row.
   	*	@return array 	Response data.
   	*/
   	private function getUserInfo($userInfo) {   		
        $response = array(
        	"account" 		=> $userInfo["account"],
        	"domainName"	=> $userInfo["domain_name"],
        	"groupId"		=> $userInfo["group_id"],
        	"id"			=> $userInfo["id"],
        	"name"			=> $userInfo["name"]
		);

		return $response;
   	}

   /**
   *  PUT:  /user/self
   *  platform user modify itself password
   *
   *  @param $this->receiver array array( 'password' => <user password> , 'newpassword' => <user new password> ) 
   *                             
   */
   public function updateSelf(){

       $user = $this->getFromSession();
       $this->vertify("newpassword", $this->receiver);

       $domain        = $user['domain_name'];
       $account       = $user['account'];
       $userId        = $user['id'];
       $password      = $this->receiver['password'];
       $newpassword   = $this->receiver['newpassword'];
       $collection = new PlatformUserCollection();

       $collection->login($domain, $account, $password);

       $salt = $collection->generateSalt();
       $hash = $collection->hash($newpassword , $salt);
       $model = $collection->getById( $userId );
       $result = $model->update( array("hash"=>$hash,"salt"=>$salt) );

       if($result){
           return array();
       }
       else {
           throw new DataAccessResultException("Update user self's password fail.");
       }
   }

    /**
   	*	POST: 	/user/<id:\d+>/group/permission
   	*
   	*	@param $id int The id of platform user group. 
   	*  	@param $this->receiver array Struct of array(    	
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....   	
   	*									); 
   	*								 Permission id is feild 'platform_group_permission_id' 
   	*								 of PlatformUserHasGroupPermissionCollection.
   	*/
    public function appendGroupPermission($id) {
   		$data = array();
      	try {
      		$this->vertify("permissions", $this->receiver);
      					
  			$actor 			= PlatformUser::instanceBySession();
  			$permissions 	= $this->receiver["permissions"];      		
  			$user 			= (new PlatformUserCollection())->getById($id);
  			$user->setActor($actor);      		

  			$rowCount 		= $user->appendGroupPermissions($permissions);

  			if(count($rowCount) > 0) {
  				$this->responser->send(array(), $this->responser->Created());
  			}
  			else {
  				throw new Exception("Not create permissions.", 1);      				
  			}    		   		       
       	}
       	catch (AuthorizationException $e) {   	        
   	        $this->responser->send( $data, $this->responser->Unauthorized() );         
   	   }
      	catch ( Exception $e) {
         	$data['message'] = SERVER_ERROR_MSG;         	
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}

   	/**
   	*	POST: 	/user/<id:\d+>/person/permission
   	*
   	*	@param $id int The id of platform user group. 
   	*  	@param $this->receiver array Struct of array(    	
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....   	
   	*									)
   	*/
   	public function appendPersonPermission($id) {
   		$data = array();
      	try {
      		$this->vertify("permissions", $this->receiver);
      					
  			$actor 			= PlatformUser::instanceBySession();
  			$permissions 	= $this->receiver["permissions"];      		
  			$user 			= (new PlatformUserCollection())->getById($id);
  			$user->setActor($actor);      		

  			$rowCount 		= $user->appendPersonPermissions($permissions);

  			if(count($rowCount) > 0) {
  				$this->responser->send(array(), $this->responser->Created());
  			}
  			else {
  				throw new Exception("Not create permissions.", 1);      				
  			}    		   		       
       	}
       	catch (AuthorizationException $e) {   	        
   	        $this->responser->send( $data, $this->responser->Unauthorized() );         
   	   }
      	catch ( Exception $e) {
         	$data['message'] = SERVER_ERROR_MSG;         	
         	$this->responser->send( $data, $this->responser->InternalServerError() );
      	}
   	}

   	/**
   	*	POST: 	/user/register
   	*  	User register API, 
   	*	Response json: 
  	*	{
  	*		"account":"admin",
  	*		"domainName":"skygo.com.tw",
  	*		"groupId":"2",
  	*		"id":"1",
  	*		"name":"admin"
  	*	}
   	*
   	*  	@param $this->receiver array array( 'domain' => <domain name>, 
   	*                             'account' => <user account>,
   	*                             'password' => <user password> )
   	*/
    public function register() {
        // TODO refactoring receiver class.
        $this->vertify("domain", $this->receiver);
        $this->vertify("account", $this->receiver);
        $this->vertify("password", $this->receiver);
        $this->vertify("groupId", $this->receiver);
        $this->vertify("name", $this->receiver);
        $this->vertify("email", $this->receiver);
        $this->vertify("personPermissions", $this->receiver);
        $this->vertify("groupPermissions", $this->receiver);

        $domain     = $this->receiver['domain'];
        $account    = $this->receiver['account'];
        $password   = $this->receiver['password'];
        $groupId   	= $this->receiver['groupId'];
        $userInfo   = array(
            "name" => $this->receiver['name'],
            "email" => $this->receiver['email'],
        );

        $actor 		= PlatformUser::instanceBySession();

        $collection = new PlatformUserCollection();
        $collection->setActor($actor);
        $user 		= $collection->register($domain, $account, $password, $groupId, $userInfo);

        $user->setActor($actor);
        $rowCount 	= $user->appendGroupPermissions($this->receiver['groupPermissions']);
        $rowCount 	= $user->appendPersonPermissions($this->receiver['personPermissions']);

        $this->setDefaultRules($user);

        return $this->getUserInfo($user->toRecord());
   	}

    /**
     * Append default rules for the new user.
     * @param PlatformUser $user
     * @throws AuthorizationException
     */
	public function setDefaultRules(PlatformUser $user) {

		$attributes = array("user_id", "name", "type");
		$values = array(
            array("user_id" => $user->id, "name" => "y:1, g:1", "type" => "0-17歲男性"),
            array("user_id" => $user->id, "name" => "y:1, g:2", "type" => "0-17歲女性"),
            array("user_id" => $user->id, "name" => "y:2, g:1", "type" => "18-31歲男性"),
            array("user_id" => $user->id, "name" => "y:2, g:2", "type" => "18-31歲女性"),
            array("user_id" => $user->id, "name" => "y:3, g:1", "type" => "32-59歲男性"),
            array("user_id" => $user->id, "name" => "y:3, g:2", "type" => "32-59歲女性"),
            array("user_id" => $user->id, "name" => "y:4, g:1", "type" => "60-100歲男性"),
            array("user_id" => $user->id, "name" => "y:4, g:2", "type" => "60-100歲女性"),
		);

		$rules = new IMediaEventTranslateRuleCollection();
		$rules->multipleCreate($attributes, $values);
	}

   	/**
   	*	GET: 	/user/list/<pageNo:\d+>/<pageSize:\d+>
   	*	Get of the user list.
   	*
   	*	Response json: 
  	*	{
  	*		"records": [
  	*			{
  	*				"id":"1",
  	*				"name":"user name",
  	*				"email":"user@email.com",
  	*				"account":"userAccount",	
  	*				"parent_group_id":"1"
  	*				"group_id":"2"
  	*				"group":"group name"
  	*			},
  	*			...	
  	*		],
  	*		"pageSize": 100,
  	*		"pageNo": 1,
  	*		"toatalPage": 1
  	*	}   	
   	*
   	*	@param $pageNo int 		The record page's number.
   	*	@param $pageSize int 	The record page's size.    	
   	*/
   	public function getList( $pageNo, $pageSize) {
        $actor 		= PlatformUser::instanceBySession();
        $collection = new PlatformUserCollection();
        $collection->setActor($actor);
        $records 	= $collection->getRecords(array(), $pageNo, $pageSize);
        return $records;
    }
}




?>