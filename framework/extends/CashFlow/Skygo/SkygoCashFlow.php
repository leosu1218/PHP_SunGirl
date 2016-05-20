<?php
require_once(dirname(__FILE__) . '/../CashFlow.php');
require_once(dirname(__FILE__) . '/GroupBuyingUserNotify.php');
require_once(dirname(__FILE__) . '/ReorderGroupBuyingUserNotify.php');

require_once(dirname(__FILE__) . '/GeneralUserNotify.php');
require_once(dirname(__FILE__) . '/ReorderGeneralUserNotify.php');

require_once(dirname(__FILE__) . '/NewebServiceProvider.php');
require_once(dirname(__FILE__) . '/ReorderServiceProvider.php');


/**
 * Class SkygoCashFlow
 *
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class SkygoCashFlow implements CashFlow {

    const GROUPBUYING_NOTIFY            = "groupbuying";
    const GENERAL_NOTIFY                = "general";
    const REORDER_GROUPBUYING_NOTIFY    = "groupbuying";
    const REORDER_GENERAL_NOTIFY        = "general";
    const NEWEB_PROVIDER                = "neweb";
    const REORDER_PROVIDER              = "reorder";

    /**
     * Create service provider instance.
     * @param string $type
     * @return CashFlowServiceProvider
     */
    public function createProvider($type='') {
        if($type == self::NEWEB_PROVIDER) {
            return new NewebServiceProvider();
        }
        else if($type == self::REORDER_PROVIDER) {
            return new ReorderServiceProvider();
        }
        else {
            throw new CashFlowException("Create CashFlowServiceProvider error. Invalid service provider type [$type]");
        }
    }

    /**
     * Create user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createUserNotify($type='') {
        if($type == self::GROUPBUYING_NOTIFY) {
            return new GroupBuyingUserNotify();
        }
        else if($type == self::GENERAL_NOTIFY) {
            return new GeneralUserNotify();
        }
        else {
            throw new CashFlowException("Create CashFlowUserNotify error. Invalid notify type [$type]");
        }
    }

    /**
     * Create reorder user notify instance.
     * @param string $type
     * @return CashFlowUserNotify
     */
    public function createReorderUserNotify($type='') {
        if($type == self::REORDER_GROUPBUYING_NOTIFY) {
            return new ReorderGroupBuyingUserNotify();
        }
        else if($type == self::REORDER_GENERAL_NOTIFY) {
            return new ReorderGeneralUserNotify();
        }
        else {
            throw new CashFlowException("Create CashFlowUserNotify error. Invalid notify type [$type]");
        }
    }
}

?>