<?php

/**
 * Interface CashFlowOrderCollection
 * PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
interface CashFlowOrderCollection {

	/**
	*	Recording order trade result.
	*
	*	@param $state mixed State code or object.
	*	@param $outTradeNo string The trade number.	
	*/
	public function recordResult($state, $outTradeNumber='');

    /**
     * Get trade success state code or object.
     * @return mixed
     */
    public function getTradeSuccessState();

    /**
     * Get trade error state code or object.
     * @return mixed
     */
    public function getTradeErrorState();

    /**
     * Get waiting trade state code or object.
     * @return mixed
     */
    public function getWaitingTradeState();
}



?>