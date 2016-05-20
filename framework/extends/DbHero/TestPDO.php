<?php

/**
 * Class TestPDO
 */
class TestPDO {

    static private $pdo = null;
    static function getInstance() {
        if(is_null(self::$pdo)) {
            self::$pdo = new PDO(
                'mysql:host=localhost;dbname=' . DB_CONNECT_NAME,
                DB_LOGIN_USRE,
                DB_LOGIN_PASSWORD,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }
        return self::$pdo;
    }
}

?>