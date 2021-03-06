<?php
/**
*   AutorizationException code.
*
*   PHP version 5.3
*
*   @category Exception
*   @package Framework
*   @author Rex chen <rexchen@synctech.ebiz.tw>
*   @copyright 2015 synctech.com
*/

class AuthorizationException extends Exception {
    
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