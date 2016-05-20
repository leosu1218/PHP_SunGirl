<?php
/**
*	Permission DataBase Actor 基於驗證授權而執行動做的人
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/


/**
*	PermissionDbActor code
*
*	PHP version 5.3
*
*	@category Dao
*	@package Model
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
interface PermissionDbActor {	

	/**
	*	Get permission query dao.
	*	
	*	@param $provider string The Provider of permission.
	*	@return dao The dao for query permission records.
	*/
	public function getPermissionDao($provider = "");

    /**
     * Get the actor's id.
     * @return mixed
     */
    public function getId();
}

?>