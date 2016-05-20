<?php
/**
*	Permission DataBase Entity 基於驗證授權而執行動作的對象
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/


/**
*	PermissionDbEntity code
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
interface PermissionDbEntity {

	/**
   	*	Setting permission actor that will validate 
   	*	the actor if has permission to action.
   	*	
   	*	@param $actor PermissionDbActor The person that do something action.   	
   	*	@return  PermissionDbEntity It self. 
   	*/
	public function setActor(PermissionDbActor $actor);

   	/**
   	*	Check actor has permission do the action.
   	*
   	*	@param $action string The action that actor do for the collection/model.
   	*	@return bool If has permission return true. 
   	*				 And always return true when not set actor.
   	*/
   	public function hasPermission($action = "");

    /**
     * Get used actor
     * @return PermissionDbActor
     */
    public function getActor();
}

?>