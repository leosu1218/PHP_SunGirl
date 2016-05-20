<?php
require_once(dirname(__FILE__) . '/CashFlowNotifyResultException.php');
require_once(dirname(__FILE__) . '/CashFlowOrderCollection.php');
require_once(dirname(__FILE__) . '/CashFlowServiceProvider.php');
require_once(dirname(__FILE__) . '/CashFlowUserNotify.php');
require_once(dirname(__FILE__) . '/CashFlowException.php');


/**
 * Interface CashFlow
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
interface CashFlow {

    /**
     * Create service provider instance.
     * @param string $type
     * @return NewebServiceProvider
     */
    public function createProvider($type='');

    /**
     * Create user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createUserNotify($type='');

    /**
     * Create reorder user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createReorderUserNotify($type='');
}

?>