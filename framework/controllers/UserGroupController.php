<?php
/**
*  UserGroup Controller code.
*
*  PHP version 5.3
*
*  @category NeteXss
*  @package Controller
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @author Jai Chien <jaichien@syncte-infinity.com>
*  @copyright 2015 synctech.com
*/
require_once( FRAMEWORK_PATH . 'collections/PlatformUserGroupCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'extends/GeneralSession.php' );


class UserGroupController extends RestController {

	private $rootGroupId;
  const ORTER_GROUP_ID = 0;

   	public function __construct() {
      	parent::__construct();      	
      	$this->rootGroupId = 1;
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
   	*	POST: 	/group/platformuser/<id:\d+>/permission
   	*
   	*	@param $id int The id of platform user group. 
   	*  	@param $this->receiver array Struct of array(    	
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....   	
   	*									)
   	*/
   	public function appendPermission($id) {
   		$data = array();
      	try {
      		if(!array_key_exists("permissions", $this->receiver)) {
      			$this->responser->send(array("message" => "Missing parameter [permissions]."), $this->responser->BadRequest());
      		}
      		else {      			
      			$user 			= PlatformUser::instanceBySession();
      			$permissions 	= $this->receiver["permissions"];      		
      			$group 			= (new PlatformUserGroupCollection())->getById($id);
      			$group->setActor($user);      		

      			$rowCount 		= $group->appendPermissions($permissions);

      			if(count($rowCount) > 0) {
      				$this->responser->send(array(), $this->responser->Created());
      			}
      			else {
      				throw new Exception("Not create permissions.", 1);      				
      			}
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
   	*	POST: 	/user/<id:\d+>/permission
   	*  	Add a user group under root group.
   	*
   	*  	@param $this->receiver array Struct of array( 
   	*										'name' => <group name>,
   	*										'permissions' => array( 
   	*											<permission id>, 
   	*											<permission id>, 
   	*											....   	
   	*									)
   	*/
   	public function create() {
      	$data = array();
      	try {
      		if(!array_key_exists("name", $this->receiver)) {
      			$this->responser->send(array("message" => "Missing parameter [name]."), $this->responser->BadRequest());
      		}
      		else if(!array_key_exists("permissions", $this->receiver)) {
      			$this->responser->send(array("message" => "Missing parameter [permissions]."), $this->responser->BadRequest());
      		}
      		else {
      			
      			$user = PlatformUser::instanceBySession();

      			$permissions = $this->receiver["permissions"];
      			$attributes = array(
      				"name" => $this->receiver["name"],
      				"parent_group_id" => $this->rootGroupId
      			);

      			$groups = new PlatformUserGroupCollection();
      			$groups->setActor($user);
      			// $group = $groups->create($attributes);
      			// $group = $groups->create($attributes)->with('permission', $permissions);
      			$group = $groups->createWithPermission($attributes, $permissions);

      			$this->responser->send(array(), $this->responser->Created());
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
      *  POST:    /group/platformuser
      *     Add a user group under root group.
      *
      *     @param $this->receiver array Struct of array( 'name' => <group name> )
      */
      private function supverisorGroupId(){
         return array('1');
      }

      /**
      *  	GET:    /group/platformuser/list/<pageNo:\d+>/<pageSize:\d+>
      *     get user group list without superisor Group
      *
      *     @return "records":{
      *               "0" : {
      *                 "id"             :"0",
      *                 "name"           :"others",
      *                 "parent_group_id":"1"
      *                },
      *               "2" : {
      *                 "id"             :"2",
      *                 "name"           :"group-2",
      *                 "parent_group_id":"1"
      *                },
      *               "3" : {
      *                 "id"             :"4",
      *                 "name"           :"group-4",
      *                 "parent_group_id":"1"
      *                }
      *             },
      *             "pageNo":1,
      *             "pageSize":1000,
      *             "totalPage":1
      *            }
      */
      public function getList($pageNo=1, $pageSize=1000 ){
         $data = array();
         try {
         	$collection = new PlatformUserGroupCollection();
         	$attributes = array("parent_group_id" => 1);
         	$actor 		= PlatformUser::instanceBySession();
         	
         	$collection->setActor($actor);
            $records = $collection->getRecords($attributes, $pageNo, $pageSize);
            
            if( $records['totalPage'] != 0 ){
               return $this->responser->send($records, $this->responser->OK());            
            }
            else{               
               $this->responser->send( $data, $this->responser->NotFound() );
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
   	*	GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>
   	*  	Get permission list of a user group.
   	*
   	*	Response json: 
	*	{
	*		"records": [
	*			{
	*				"id":"1", // group id
	*				"permission_id":"1",
	*				"entity":"platform_user_group",
	*				"entity_id":"0",
	*				"action":"read",
	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
	*			},
	*			... permissions depend group
	*		]
	*	}   	
   	*/
    public function getPermissionList($id, $pageNo=1, $pageSize=1000) {
    	$data = array();
        try {
        	$model = (new PlatformUserGroupCollection())->getById($id);         	
         	$actor = PlatformUser::instanceBySession();         	         	
         	
         	$model->setActor($actor);
            $records = $model->getPermissions($pageNo, $pageSize);
            
            if( $records['totalPage'] != 0 ){
               return $this->responser->send($records, $this->responser->OK());            
            }
            else{               
               $this->responser->send( $data, $this->responser->NotFound() );
            }            
        }
        catch(AuthorizationException $e) {            
            $this->responser->send( $data, $this->responser->Unauthorized() );         
        }
        catch(Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
	}

	/**
   	*	GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>
   	*  	Get user list of a user group.
   	*
   	*	Response json: 
	*	{
	*		"records": [
	*			{
	*				"id":"1", // group id
	*				"permission_id":"1",
	*				"entity":"platform_user_group",
	*				"entity_id":"0",
	*				"action":"read",
	*				"permission_name":"\u700f\u89bd\u7fa4\u7d44"
	*			},
	*			... permissions depend group
	*		]
	*	}   	
	* 	@param $id int The group id.
   	*/
    public function getUserList($id, $pageNo=1, $pageSize=1000) {
    	$data = array();
        try {
        	$actor 		= PlatformUser::instanceBySession();
        	$model 		= (new PlatformUserGroupCollection())->getById($id);
        	$groupName 	= $model->getAttribute("name");

         	$model->setActor($actor);
            $records = $model->getUsers($pageNo, $pageSize);
            
            if( $records['totalPage'] != 0 ){
               	$records["name"] = $groupName;
               	return $this->responser->send($records, $this->responser->OK());            
            }
            else{               
               	$this->responser->send( $data, $this->responser->NotFound() );
            }            

        }
        catch(AuthorizationException $e) {            
            $this->responser->send( $data, $this->responser->Unauthorized() );         
        }
        catch(Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
	}

  /**
  * Update user's info that in a group.
  * PUT:  /group/platformuser/<userId:\d+>
  *
  */
  public function updateUserPassword($groupId,$userId) {
      $actor    = PlatformUser::instanceBySession();
      $newpassword = $this->params("newpassword");
      $isSuccess     = (new PlatformUserCollection())->setActor($actor)->updatePassword($userId, $newpassword);
      return $isSuccess;
  }
          
	/**
	*	Update user's info that in a group.
	*	PUT: 	/group/platformuser/<groupId:\d+>/user/<userId:\d+>
	*
	*/
	public function updateUser($groupId, $userId) {
		$data = array();		
        try {
        	$this->vertify("groupId", $this->receiver);
        	$this->vertify("permissions", $this->receiver);        	

        	$newGroupId = $this->receiver['groupId'];
        	$actor 		= PlatformUser::instanceBySession();
        	$user 		= (new PlatformUserCollection())->getById($userId);
        	$oldGroup	= (new PlatformUserGroupCollection())->getById($groupId);
        	$newGroup	= (new PlatformUserGroupCollection())->getById($newGroupId);

         	$user->setActor($actor);
         	$oldGroup->setActor($actor);
         	$newGroup->setActor($actor);

         	$oldGroup->removeUserPermission($userId);
         	$newGroup->appendUser($userId);
         	$rowCount 	= $user->appendGroupPermissions($this->receiver['permissions']);

         	if(count($rowCount) > 0) {
  				$this->responser->send(array(), $this->responser->Created());
  			}
  			else {
  				throw new DbOperationException("Update user permissions fail.", 1);			
  			}
        }
        catch(DbOperationException $e) {
        	$data['message'] = $e->getMessage();
        	$this->responser->send( $data, $this->responser->InternalServerError() );
        }
        catch(AuthorizationException $e) {            
        	$data['message'] = $e->getMessage();
            $this->responser->send( $data, $this->responser->Unauthorized() );
        }
        catch(Exception $e) {
            $data['message'] = SERVER_ERROR_MSG;            
            $this->responser->send( $data, $this->responser->InternalServerError() );
        }
	}

  /**
  * Delete a group and move user to group other.
  *
  * @param $id int Group ID.
  * @param $dao object Group $dao.
  * @return $isSuccess boolean
  */
  private function movePlatformUserGroupId( $oldId, $newId, &$dao=null ){
    $collection = new PlatformUserCollection( $dao );
    $result = $collection->getUserCountByGroupId($oldId);
    $effectRows = $collection->updateGroupIdById($newId, $result['ids'] );
    if( $result['count'] != $effectRows ){
      throw new Exception("Update PlatformUser by group id is fail.", 1);
    }
    return true;
  }

	/**
	*	DELETE: /group/platformuser/<groupId:\d+>
	*	Delete a group and move user to group other.
	*
	*	@param $id int Group ID.
	*/
	public function remove($id) {

  	$actor 		= PlatformUser::instanceBySession();
  	$collection = new PlatformUserGroupCollection();
    $dao = $collection->dao;
    if($dao->transaction()) {
      try {
          $this->movePlatformUserGroupId( $id, UserGroupController::ORTER_GROUP_ID, $dao );

          $model = $collection->getById($id);
          $model->setActor($actor);
          $rowCount = $model->destroy();
          if(count($rowCount) == 0) {
            throw new DbOperationException("Delete user group fail.", 1);
          }

          $dao->commit();
          return array( "effectRow"=>$rowCount );
      }
      catch(Exception $e) {
          $dao->rollback();
          throw $e;
      }
    }
    else {
        throw new DbOperationException("Begin transaction fail.");
    }
	
  }

}




?>