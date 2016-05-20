<?php 
/**
*  LoggerHelper code.
*
*  PHP version 5.3
*
*  @category AuthenticateHelper
*  @package authenticate
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

class LoggerHelper {

	private $device;

	public function __construct($prefix='system', $path='') {
		if($path == '') {
			$path = dirname(__FILE__) . '/';
		}


		$fileName = $path . $prefix . date("-Y-m-d") . '.log';
		$this->device = fopen($fileName, "a");
	}

	private function now() {
		return date("Y-m-d H:i:s");
	}

	private function write($type, $string) {
		try {
            if($this->device) {
                $now = $this->now();
                $string = "[$now][$type] $string \r\n";
                fwrite($this->device, $string);
            }
		}
		catch(Exception $e) {
			// out of control.
		}
	}

	public function debug($string) {
		$this->write('debug', $string);
	}

	public function error($string) {
		$this->write('error', $string);
	}

	public function warn($string) {
		$this->write('warn', $string);
	}

	public function info($string) {
		$this->write('info', $string);
	}
}


?>