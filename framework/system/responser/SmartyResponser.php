<?php
/**
*	Smarty Responser code.
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/


/**
*	SmartyResponser 使用smarty樣板模組來定義回應瀏覽器資料的方法
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/

require_once( ROOT . 'configs/sys.smarty.inc.php' );

class SmartyResponser extends Responser {

	protected $statusCode;
	protected $statusMessage;

	public function ContinueRequest() { 
		$this->statusCode = 100;
		$this->statusMessage = 'ContinueRequest';
		return 'error.tpl';
	}

	public function SwitchingProtocols() { 
		$this->statusCode = 101; 
		$this->statusMessage = 'SwitchingProtocols';
		return 'error.tpl';
	}

	public function OK( $templateName ) { 

		$this->statusCode = 200;
		$templateFileName = $templateName . ".tpl";
		return $templateFileName;
	}

	public function Created() { 
		$this->statusCode = 201; 
		$this->statusMessage = 'Created';
		return 'error.tpl';
	}

	public function Accepted() { 
		$this->statusCode = 202; 
		$this->statusMessage = 'Accepted';
		return 'error.tpl';
	}

	public function NonAuthoritativeInformation() { 
		$this->statusCode = 203; 
		$this->statusMessage = 'NonAuthoritativeInformation';
		return 'error.tpl';
	}

	public function NoContent() { 
		$this->statusCode = 204; 
		$this->statusMessage = 'NoContent';
		return 'error.tpl';
	}

	public function ResetContent() { 
		$this->statusCode = 205; 
		$this->statusMessage = 'ResetContent';
		return 'error.tpl';
	}

	public function PartialContent() { 
		$this->statusCode = 206; 
		$this->statusMessage = 'PartialContent';
		return 'error.tpl';
	}

	public function MultipleChoices() { 
		$this->statusCode = 300; 
		$this->statusMessage = 'MultipleChoices';
		return 'error.tpl';
	}

	public function MovedPermanently() { 
		$this->statusCode = 301; 
		$this->statusMessage = 'MovedPermanently';
		return 'error.tpl';
	}

	public function Found() { 
		$this->statusCode = 302; 
		$this->statusMessage = 'Found';
		return 'error.tpl';
	}

	public function SeeOther() { 
		$this->statusCode = 303; 
		$this->statusMessage = 'SeeOther';
		return 'error.tpl';
	}

	public function NotModified() { 
		$this->statusCode = 304; 
		$this->statusMessage = 'NotModified';
		return 'error.tpl';
	}

	public function UseProxy() { 
		$this->statusCode = 305; 
		$this->statusMessage = 'UseProxy';
		return 'error.tpl';
	}

	public function TemporaryRedirect() { 
		$this->statusCode = 307; 
		$this->statusMessage = 'TemporaryRedirect';
		return 'error.tpl';
	}

	public function BadRequest() { 
		$this->statusCode = 400; 
		$this->statusMessage = '錯誤的請求';
		return 'error.tpl';
	}

	public function Unauthorized() { 
		$this->statusCode = 401; 
		$this->statusMessage = '未經驗證';
		return 'error.tpl';
	}
	
	public function PaymentRequired() { 
		$this->statusCode = 402; 
		$this->statusMessage = '需要付費';
		return 'error.tpl';
	}

	public function Forbidden() { 
		$this->statusCode = 403; 
		$this->statusMessage = '權限不足';
		return 'error.tpl';
	}
	
	public function NotFound() { 
		$this->statusCode = 404; 
		$this->statusMessage = '不存在資料';
		return 'error.tpl';
	}
	
	public function MethodNotAllowed() { 
		$this->statusCode = 405;
		$this->statusMessage = '不允許此操作';
		return 'error.tpl';
	}
	public function NotAcceptable() { 
		$this->statusCode = 406; 
		$this->statusMessage = '拒絕此項要求';
		return 'error.tpl';
	}
	public function ProxyAuthenticationRequired() { 
		$this->statusCode = 407; 
		$this->statusMessage = 'Proxy需要被驗證';
		return 'error.tpl';
	}
	public function RequestTimeout() { 
		$this->statusCode = 408; 
		$this->statusMessage = '逾時的請求';
		return 'error.tpl';
	}
	public function Conflict() { 
		$this->statusCode = 409; 
		$this->statusMessage = '發生衝突的錯誤';
		return 'error.tpl';
	}
	public function Gone() { 
		$this->statusCode = 410; 
		$this->statusMessage = '過期的資料';
		return 'error.tpl';
	}
	public function LengthRequired() { 
		$this->statusCode = 411; 
		$this->statusMessage = '需要長度';
		return 'error.tpl';
	}

	public function PreconditionFailed() { 
		$this->statusCode = 412; 
		$this->statusMessage = '此功能需要的前置條件不滿足';
		return 'error.tpl';
	}

	public function RequestEntityTooLarge() { 
		$this->statusCode = 413; 
		$this->statusMessage = '傳送請求時的資料量過大';
		return 'error.tpl';
	}
	
	public function RequestURITooLong() { 
		$this->statusCode = 414; 
		$this->statusMessage = '請求的路徑過長';
		return 'error.tpl';
	}

	public function UnsupportedMediaType() { 
		$this->statusCode = 415; 
		$this->statusMessage = '不支援此媒體格式';
		return 'error.tpl';
	}
	
	public function RequestedRangeNotSatisfiable() { 
		$this->statusCode = 416; 
		$this->statusMessage = '請求的範圍不符合';
		return 'error.tpl';
	}

	public function ExpectationFailed() { 
		$this->statusCode = 417; 
		$this->statusMessage = '預期外的錯誤';
		return 'error.tpl';
	}
	
	public function InternalServerError() { 
		$this->statusCode = 500; 
		$this->statusMessage = '伺服器錯誤';
		return 'error.tpl';
	}
	
	public function NotImplemented() { 
		$this->statusCode = 501; 
		$this->statusMessage = '未實做此功能';
		return 'error.tpl';
	}
	
	public function BadGateway() { 
		$this->statusCode = 502; 
		$this->statusMessage = '錯誤的路由';
		return 'error.tpl';
	}
	
	public function ServiceUnavailable() { 
		$this->statusCode = 503; 
		$this->statusMessage = '此服務尚未被啟動';
		return 'error.tpl';
	}
	
	public function GatewayTimeout() { 
		$this->statusCode = 504; 
		$this->statusMessage = '路由逾時';
		return 'error.tpl';
	}
	
	public function HTTPVersionNotSupported() { 
		$this->statusCode = 505; 
		$this->statusMessage = '此版本的HTTP不支援';
		return 'error.tpl';
	}

	public function __construct() {

		$this->statusCode = 200;
		$this->statusMessage = 'OK';
	}

	// send response
	public function send( $data = array(), $options = null ) {

		$smarty = new MySmarty();

		if( $this->statusCode != 200 ) {
			
			$data['statusMessage'] = $this->statusMessage;
			$this->setMessage($data);
		}

		$smarty->assign( 'data', $data );
		
		if(isset($options))
		{
			$smarty->display( $options );
		}	
		
	}

	public function setMessage( $data = array() ) {

		if(isset($data['message'])) {
				$data['message'] = '';
		}
	}

}


?>