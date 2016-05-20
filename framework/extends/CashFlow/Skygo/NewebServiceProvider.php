<?php
/**
*  NewebServiceProvider code.
*
*  PHP version 5.3
*
*  @category service provider
*  @package provider
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/
require_once(dirname(__FILE__) . '/../CashFlowServiceProvider.php');
require_once(dirname(__FILE__) . '/NewebServiceConfig.php');
require_once(FRAMEWORK_PATH . 'extends/LoggerHelper.php');
require_once(FRAMEWORK_PATH . 'extends/ValidatorHelper.php');

class NewebServiceProvider implements CashFlowServiceProvider {

	private $log;
	private $validator;

	public function __construct() {

		$this->validator 	= new ValidatorHelper();
		$this->log 			= new LoggerHelper(
								NewebServiceConfig::LOG_PREFIX, 
								NewebServiceConfig::LOG_PATH);
	}

    /**
     * Logging trade transaction behavior.
     * @param array $param
     * @param string $text
     */
	public function info($param=array(), $text='') {
		$param = array_merge($param, $_SERVER);
		$this->log->info("[Neweb trade] $text \r\n" . json_encode($param) . "\r\n");
	}

    /**
     * Logging trade transaction behavior.
     * @param array $param
     * @param string $text Transaction behavior message.
     */
	public function error($param=array(), $text='') {
		$param = array_merge($param, $_SERVER);
		$this->log->error("[Neweb trade] $text \r\n" . json_encode($param) . "\r\n");
	}

    /**
     * Create params for third party cash flow api.
     * @param array $orderInfo The info of prepare order.
     * @return array Params for api.
     */
	public function createPaymentParams($orderInfo) {

		$checksum 	= $this->getChecksum($orderInfo['serial'], $orderInfo['final_total_price']);
		$tradeNo 	= $this->getOuterTradeNo($orderInfo['id']);
		
		return array(			
			'MerchantNumber' 	=> NewebServiceConfig::MERCHANT_NUMBER,
			'OrderNumber' 		=> $orderInfo['serial'],
			'Amount' 			=> $orderInfo['final_total_price'],
			'OrgOrderNumber' 	=> $tradeNo,
			'ApproveFlag' 		=> NewebServiceConfig::APPROVE_FLAG,
			'DepositFlag' 		=> NewebServiceConfig::DEPOSIT_FLAG,
			'Englishmode' 		=> NewebServiceConfig::ENGLISH_MODE,
			'iphonepage' 		=> NewebServiceConfig::IPHONE_PAGE_FLAG,
			'OrderURL' 			=> NewebServiceConfig::NOTIFY_URL,
			'ReturnURL' 		=> NewebServiceConfig::RETURN_URL,
			'checksum' 			=> $checksum,
			'op' 				=> 'AcceptPayment',
			'providerUrl'		=> NewebServiceConfig::PROVIDER_URL,
		);
	}

    /**
     * Get order's checksum.
     * @param $serial
     * @param $price
     * @return string
     */
	public function getChecksum($serial, $price) {
		$checksum = md5(
			NewebServiceConfig::MERCHANT_NUMBER.
			$serial.
			NewebServiceConfig::HASH_SALT.
			$price
		);

		return $checksum;
	}

    /**
     * Get outer trade number.
     * @param $id
     * @return mixed
     */
	public function getOuterTradeNo($id) {
		return $id;
	}

    /**
     * Check param is correctly.
     * @param array $param The received notify params.
     * @throws InvalidAccessParamsException
     */
	public function requireParams($param=array()) {
		$this->validator->requireAttribute('MerchantNumber', $param);
		$this->validator->requireAttribute('OrderNumber', $param);
		$this->validator->requireAttribute('PRC', $param);
		$this->validator->requireAttribute('SRC', $param);
		$this->validator->requireAttribute('Amount', $param);
		$this->validator->requireAttribute('CheckSum', $param);
		$this->validator->requireAttribute('ApprovalCode', $param);
		$this->validator->requireAttribute('BankResponseCode', $param);
		$this->validator->requireAttribute('BatchNumber', $param);		
	}

    /**
     * Checking received params is valid by checksum.
     * @param array $param The received notify params.
     * @return bool Return true when checksum valid.
     */
	public function isValidChecksum($param=array()) {
		$checksum = md5(
					$param['MerchantNumber'] . 
					$param['OrderNumber'] . 
					$param['PRC'] . 
					$param['SRC'] . 
					NewebServiceConfig::HASH_SALT .
					$param['Amount']
					);

		return (strtolower($checksum) == strtolower($param['CheckSum']));
	}

    /**
     * Process order trade callback notify.
     * @param array $param The callback info for notify trade result.
     * @param CashFlowOrderCollection $order The collection of order that for recording trade result.
     * @param CashFlowUserNotify $notify
     * @return bool Return true when trade authentication success.
     */
	public function receiveNotify($param=array(), CashFlowOrderCollection $order, CashFlowUserNotify $notify=NULL) {

		try {			
			$this->requireParams($param);

			if($param['PRC']=="0" && $param['SRC']=="0") {
				if($this->isValidChecksum($param)) {
					$result = $order->recordResult($order->getTradeSuccessState(), $param['OrderNumber']);
					$this->info($param, "Trade successful.");

					if(!is_null($notify)) {
						$notify->send($result);
					}					

					return true;
				}
				else {
					throw new CashFlowNotifyResultException("Invalid trade checksum !", 1);
				}
			}
			else if($param['PRC']=="34" && $param['SRC']=="171") {
				throw new CashFlowNotifyResultException("Banking trade fail !", 1);
			}
			else if($param['PRC']=="8" && $param['SRC']=="204") {
				throw new CashFlowNotifyResultException("Exists order !", 1);
			}
			else if($param['PRC']=="52" && $param['SRC']=="554") {
				throw new CashFlowNotifyResultException("User account or password error !", 1);
			}
			else {
				throw new CashFlowNotifyResultException("Undefined error !", 1);
			}		
		}	
		catch(CashFlowNotifyResultException $e) {			
			$this->error($param, $e->getMessage());
			$order->recordResult($order->getTradeErrorState(), $param['OrderNumber']);
            throw $e;
		}
		catch(Exception $e) {
			$this->error($param, $e->getMessage());
            throw $e;
		}
	}
}




?>