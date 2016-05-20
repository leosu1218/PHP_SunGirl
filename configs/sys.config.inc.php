<?PHP
/********************************************
 *
 * 路徑定義
 *
 ********************************************/
define('ROOT', str_replace('\\', '/',dirname(dirname(__FILE__)) . '/' ));
define('FRAMEWORK_PATH', ROOT . 'framework/');
define('UPLOAD', ROOT . 'upload/');
define('LOG_PATH', ROOT . "../sys_logs/");
define('TRADE_LOG_PATH', ROOT . "../trade_logs/");
define('RAW_FILES_PATH', ROOT . "../raw_files/");

define('MAIL_API_PATH', 'http://' . $_SERVER['HTTP_HOST'] . '/api/mail');

define('DB_NAME', 'aems');
define('DB_CONNECT_NAME', 'aems');
define('DB_LOGIN_USRE', 'aems');
define('DB_LOGIN_PASSWORD', 'laskdnmcanv');

/********************************************
 *
 * 時區定義
 *
 ********************************************/
date_default_timezone_set("Asia/Taipei");

/********************************************
 *
 * 錯誤訊息
 *
 ********************************************/
define('SERVER_ERROR_MSG', 		'不好意思, 伺服器發生預期外的錯誤, 我們已經在進行修復中');
define('PERMISSION_ERROR_MSG', 	'您沒有權限進行此項操作');
define('NOCONTENT_MSG', 		'您查詢的資料為空');

/********************************************
 *
 * 基本程式
 *
 ********************************************/
require_once FRAMEWORK_PATH . 'synature.php';

/********************************************
 *
 * 除錯訊息
 *
 ********************************************/
ini_set("display_errors", "On");
error_reporting(-1);
?>