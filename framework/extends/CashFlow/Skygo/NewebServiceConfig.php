<?php


/**
 * Class NewebServiceConfig
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class NewebServiceConfig {

	const MERCHANT_NUMBER 	= '760839';
	const HASH_SALT 		= 'abcd1234';
	const APPROVE_FLAG 		= '1';
	const DEPOSIT_FLAG		= '1';
	const ENGLISH_MODE		= '0';
	const IPHONE_PAGE_FLAG	= '1';

	//user received
	const RETURN_URL		= 'http://skygo.109life.com/receive.php'; 
	//server received 
	const NOTIFY_URL		= 'http://skygo.109life.com/api/order/groupbuying/notify/neweb';
	const LOG_PREFIX		= 'trade';
    const LOG_PATH			= TRADE_LOG_PATH;
	const PROVIDER_URL 		= 'https://testmaple2.neweb.com.tw/NewebmPP/cdcard.jsp';
}




?>