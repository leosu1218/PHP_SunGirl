<?php
require_once(dirname(__FILE__) . '/CashFlowNotifyResultException.php');
require_once(dirname(__FILE__) . '/CashFlowOrderCollection.php');
require_once(dirname(__FILE__) . '/CashFlowUserNotify.php');

/**
 *	CashFlowServiceProvider code
 *
 *	PHP version 5.3
 *
 *	@category CashFlow
 *	@package CashFlowServiceProvider
 *	@author Rex Chen <rexchen@synctech-infinity.com>
 *	@copyright 2015 synctech.com
 */
interface CashFlowServiceProvider {

    /**
     * Create params for third party cash flow api.
     * @param array $orderInfo The info of prepare order.
     * @return array Params for api.
     */
    public function createPaymentParams($orderInfo);

    /**
     * Get order's checksum.
     * @param $serial
     * @param $price
     * @return string
     */
    public function getChecksum($serial, $price);

    /**
     * Get outer trade number.
     * @param $id
     * @return mixed
     */
    public function getOuterTradeNo($id);

    /**
     * Check param is correctly.
     * @param array $param The received notify params.
     * @throws InvalidAccessParamsException
     */
    public function requireParams($param=array());

    /**
     * Checking received params is valid by checksum.
     * @param array $param The received notify params.
     * @return bool Return true when checksum valid.
     */
    public function isValidChecksum($param=array());

    /**
     * Process order trade callback notify.
     * @param array $param The callback info for notify trade result.
     * @param CashFlowOrderCollection $order The collection of order that for recording trade result.
     * @param CashFlowUserNotify $notify
     * @return bool Return true when trade authentication success.
     */
    public function receiveNotify($param=array(), CashFlowOrderCollection $order, CashFlowUserNotify $notify=NULL);
}



?>