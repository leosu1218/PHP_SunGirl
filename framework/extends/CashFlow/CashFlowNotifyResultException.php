<?php

/**
 * Class CashFlowNotifyResultException
 *  PHP version 5.3
 *
 * @author Rex Chen <rexchen@synctech-infinity.com>
 * @package provider
 * @category service provider
 */
class CashFlowNotifyResultException extends Exception {
    
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /** 
    *   custom string representation of object
    *
    */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>