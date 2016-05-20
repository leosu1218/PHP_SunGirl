<?php
require_once ROOT . 'libs/Smarty.class.php';  


class MySmarty extends Smarty{

	//預設的資料庫名稱,可透過外部來改變
	public  $DbName = 'netexss';

	function __construct() 
	{
		parent :: __construct();

		$this->setTemplateDir(ROOT . 'templates/');
		$this->setCompileDir (ROOT . 'templates_c/');
		$this->setConfigDir  (ROOT . 'configs/');
		$this->setCacheDir	 (ROOT . 'cache/');
		$this->cache_lifetime	= 60 * 60 * 24; //設定快取時間
		$this->caching 			= Smarty::CACHING_OFF;; //0 false  1 true 2 使用cache_lifetime設定
		$this->error_reporting	= false;
		$this->left_delimiter 	= '<%';
		$this->right_delimiter 	= '%>';
		//$this->debugging			= true;
	}
}

?>