<?php
/**
*	PlatformUserCollection code.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'system/exception/AuthorizationException.php' );
require_once( FRAMEWORK_PATH . 'system/exception/OperationConflictException.php' );
require_once( FRAMEWORK_PATH . 'models/PlatformUser.php' );
require_once( FRAMEWORK_PATH . 'extends/AuthenticateHelper.php' );

/**
*	UserCollection Access User entity collection.
*
*	PHP version 5.3
*
*	@category Collection
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/
class PlatformUserCollection extends PermissionDbCollection {

	private $helper;

	public function __construct(&$dao=null) {
		parent::__construct($dao);
		$this->helper = new AuthenticateHelper();
	}

	/**
	*	Create a salt for hash.
	*
	*	@param $length 		string 	Salt string's length.	
	*	@return string  	Salt string.
	*/
	public function generateSalt($length = 5) {
		return $this->helper->generateSalt($length);
	}

	/**
	*	Verify password by hash with salt.
	*
	*	@param $password string
	*	@param $hash 	 string
	*	@param $salt 	 string
	*	@return bool 	 Retrun true when password is correct.
	*/
	public function passwordVerify($password, $hash, $salt) {
		return $this->helper->passwordVerify($password, $hash, $salt);
	}
	
	/**
	*	hash password
	*
	*	@param $password 	string 
	*	@param $salt  		string
	*	@return string hash code
	*/
	public function hash($password, $salt) {
		return $this->helper->hash($password, $salt);
	}

	/**
	*	User login.
	*
	*	@param $domain 		string 	The domain name of the user register.	
	*	@param $account 	string  The account of user.
	*	@param $passowrd 	string  The password clear text of user.
	*	@return Model  		User's model.
	*/
	public function login($domain, $account, $password) {

		$result = $this->getRecords(array(
			"domain_name" => $domain,
			"account" => $account
		));

		if($result["totalPage"] > 0) {
						
			$record = $result["records"][0];
			$hash 	= $record["hash"];
			$salt 	= $record["salt"];

			if($this->passwordVerify($password, $hash, $salt)) {				
				return $record;
			}
			else {
				throw new AuthorizationException("User account, domain or password incorrect", 1);
			}

		}
		else {
			throw new AuthorizationException("User not exsits in the domain", 1);
		}
	}

	/**
	*	User update self password.
	*
	*	@return Boolean.
	*/
	public function updatePassword($userId, $newpassword) {

		$salt = $this->generateSalt();
		$hash = $this->hash($newpassword , $salt);
		$userModel = $this->getById( $userId );
		$rowNumber = $userModel->update( array("hash"=>$hash,"salt"=>$salt) );
		if($rowNumber>0){
			return array("isSuccess"=>true);
		}else{
			return array("isSuccess"=>false);
		}
		
	}
	
	/**
	*	Register new user.
	*
	*	@param $domain 		string 	The domain name of the user register.	
	*	@param $account 	string  The account of user.
	*	@param $passowrd 	string  The password clear text of user.
	*	@param $groupId 	integer The id of group that user want to register.
	*	@param $userInfo 	array   Extends user information.
	*	@return Model  		If success return user's model or fail return null.
	*/
	public function register($domain, $account, $password, $groupId, $userInfo = array()) {

		$result = $this->getRecords(array(
			// 'group_id' => $groupId, 
			'account' => $account
		));

		if($result["totalPage"] == 0) {
			$salt = $this->generateSalt();
			$hash = $this->hash($password , $salt);

			$attributes = array_merge($userInfo, array(
				'domain_name'  => $domain,
				'account' => $account,
				'hash' => $hash,
				'salt' => $salt,
				'group_id' => $groupId
			));

			$effectRows = $this->create($attributes);

			if($effectRows > 0) {
				$userId = $this->dao->lastInsertId();
				return new PlatformUser($userId);
			}
			else {
				throw New DbOperationException("Insert database fail.", 1);
			}
		}
		else {			
			throw new OperationConflictException("User already exsits.", 1);
		}
	}	

	/**	
	*	Get user's records with group info.
	*
	*/
	public function getRecordsWithGroup($attributes, $pageNo, $pageSize) {
		if($this->hasPermission("list")) {		

			$this->dao->fresh();
			$this->dao->select(array(
				"u.id id",
				"u.name name",
				"u.account account",
				"u.email email",
				"g.parent_group_id parent_group_id",
				"g.id group_id",
				"g.name group_name",
			));

			$this->dao->from("platform_user u");
			$this->dao->leftJoin("platform_user_group g", "u.group_id=g.id");			

			$primaryKey = $this->getPrimaryAttribute();			
			$this->dao->paging($pageNo, $pageSize);

			$result["recordCount"]  = $this->dao->queryCount();
			$result["records"] 		= $this->dao->queryAll();
			$result["totalPage"] 	= intval(ceil($result["recordCount"] / $pageSize));
			$result["pageSize"]		= $pageSize;
			$result["pageNo"]		= $pageNo;

			return $result;
		}
		else {
			throw new AuthorizationException("Actor haven't permission to list groug permission in " . $this->getTable(), 1);		
		}
	}

	/**	
	*	Get user's records number.
	*	@param $id number 
	*	@return $result array(
	*					"ids"=>array(array("id"=>1),array("id"=>3),array("id"=>5),...)
	* 					"count"=>10
	*				) 
	*/
	public function getUserCountByGroupId( $id ){
		$result = array();
		$table = $this->getTable();
		$conditions = array('and');
		$params = array();

		$this->dao->fresh();
		$this->dao->select(array(
			'pu.id id',
		));

		$this->dao->from("$table pu");
		array_push($conditions, 'pu.group_id = :group_id');
		$params[':group_id'] = $id;

		$this->dao->where($conditions,$params);
		$result['count'] = intval($this->dao->queryCount());
		$result['ids'] = $this->dao->query();
		return $result;
	}

	/**	
	*	Update user's groupId.
	*	@param $groupId number 
	*	@param $ids array 
	*	@return $effectRows number 
	*/
	public function updateGroupIdById( $groupId, $ids ){
		
		$updateIds = array();
	    foreach ($ids as $key => $id) {
	      array_push($updateIds, $id);
	    }
		$attribute = array( "group_id" => $groupId );
		$effectRows = $this->multipleUpdateById($updateIds, $attribute);
		
		return $effectRows;
		
	}

	/* DbCollection abstract methods. */

	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user";
	}

	public function getModelName() {
		return "PlatformUser";
	}

	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		
		if(array_key_exists("id", $attributes)) {
			throw new Exception("Can't write the attribute 'id'.");
		}

		if(!array_key_exists("domain_name", $attributes)) {
			throw new Exception("Attributes should be has 'domain_name'.", 1);
		}

		if(!array_key_exists("group_id", $attributes)) {
			throw new Exception("Attributes should be has 'group_id'.", 1);
		}

		if(!array_key_exists("account", $attributes)) {
			throw new Exception("Attributes should be has 'account'.", 1);
		}

		if(!array_key_exists("hash", $attributes)) {
			throw new Exception("Attributes should be has 'hash'.", 1);
		}

		if(!array_key_exists("salt", $attributes)) {
			throw new Exception("Attributes should be has 'salt'.", 1);
		}

		return true;
	}

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}
}



?>
