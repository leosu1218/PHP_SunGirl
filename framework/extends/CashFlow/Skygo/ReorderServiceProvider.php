<?php
require_once(dirname(__FILE__) . '/../CashFlowServiceProvider.php');
require_once(FRAMEWORK_PATH . 'extends/LoggerHelper.php');
require_once(FRAMEWORK_PATH . 'extends/ValidatorHelper.php');

/**
 * Class ReorderServiceProvider

 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class ReorderServiceProvider implements CashFlowServiceProvider {
    /**
     * Create params for third party cash flow api.
     * @param array $orderInfo The info of prepare order.
     * @return array Params for api.
     */
    public function createPaymentParams($orderInfo) {
//        throw new Exception("Not implement the method");
        return $orderInfo;
    }

    /**
     * Get order's checksum.
     * @param $serial
     * @param $price
     * @return string
     */
    public function getChecksum($serial, $price) {
        throw new Exception("Not implement the method");
    }

    /**
     * Get outer trade number.
     * @param $id
     * @return mixed
     */
    public function getOuterTradeNo($id) {
        throw new Exception("Not implement the method");
    }

    /**
     * Check param is correctly.
     * @param array $param The received notify params.
     * @throws InvalidAccessParamsException
     */
    public function requireParams($param=array()) {
        throw new Exception("Not implement the method");
    }

    /**
     * Checking received params is valid by checksum.
     * @param array $param The received notify params.
     * @return bool Return true when checksum valid.
     */
    public function isValidChecksum($param=array()) {
        throw new Exception("Not implement the method");
    }

    /**
     * Process order trade callback notify.
     * @param array $param The callback info for notify trade result.
     * @param CashFlowOrderCollection $order The collection of order that for recording trade result.
     * @param CashFlowUserNotify $notify
     * @return bool Return true when trade authentication success.
     */
    public function receiveNotify($param=array(), CashFlowOrderCollection $order, CashFlowUserNotify $notify=NULL) {
        $result = $order->recordResult($order->getTradeSuccessState(), $param['serial']);
//        $this->info($param, "Trade successful.");

        if(!is_null($notify)) {
            $notify->send($result);
        }

        return true;
    }
}

?>