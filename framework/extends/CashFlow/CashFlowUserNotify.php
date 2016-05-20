<?php


/**
 * Interface CashFlowUserNotify
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
interface CashFlowUserNotify {

	/**
	*	Notify user that order trade result.
	*
	*	@param $info array The info for user notify
	*/
	public function send($info=array());
}



?>