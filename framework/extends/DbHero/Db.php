<?php
require_once(__DIR__ . '/DbHero.php');
require_once(__DIR__ . '/TestPDO.php');

/**
*	Db Class
*
*	PHP version 5.3
*
*	@category Database
*	@package DbHero
*	@author Rex Chen <chen.cyr@gmail.com>
*	@copyright 2014 synctech.com
*/
class Db extends DbHero {
	protected function _config() {
		return array(
            "aems"  =>  new PDO(
                'mysql:host=localhost;dbname=' . DB_CONNECT_NAME,
                DB_LOGIN_USRE,
                DB_LOGIN_PASSWORD,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            ),
            "test_pdo"  => TestPDO::getInstance(),
		);
	}
}

?>
