<?php
// ob_start();

/**
*	Rest Responser code.
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/


/**
*	RestResponser 使用RESTful API回應資料的回應器
*
*	PHP version 5.3
*
*	@category NeteXss
*	@package System
*	@author Rex Chen <rexchen@synctech-infinity.com>
*	@copyright 2014 synctech.com
*/
class RestResponser extends Responser
{

	final public function ContinueRequest 				(){ return 100; }
	final public function SwitchingProtocols			(){ return 101; }
	final public function OK 							(){ return 200; }
	final public function Created 						(){ return 201; }
	final public function Accepted 						(){ return 202; }
	final public function NonAuthoritativeInformation	(){ return 203; }
	final public function NoContent 					(){ return 204; }
	final public function ResetContent 					(){ return 205; }
	final public function PartialContent 				(){ return 206; }
	final public function MultipleChoices 				(){ return 300; }
	final public function MovedPermanently 				(){ return 301; }
	final public function Found 						(){ return 302; }
	final public function SeeOther 						(){ return 303; }
	final public function NotModified 					(){ return 304; }
	final public function UseProxy 						(){ return 305; }
	final public function TemporaryRedirect 			(){ return 307; }
	final public function BadRequest 					(){ return 400; }
	final public function Unauthorized 					(){ return 401; }
	final public function PaymentRequired 				(){ return 402; }
	final public function Forbidden 					(){ return 403; }
	final public function NotFound 						(){ return 404; }
	final public function MethodNotAllowed 				(){ return 405; }
	final public function NotAcceptable 				(){ return 406; }
	final public function ProxyAuthenticationRequired 	(){ return 407; }
	final public function RequestTimeout 				(){ return 408; }
	final public function Conflict 						(){ return 409; }
	final public function Gone 							(){ return 410; }
	final public function LengthRequired 				(){ return 411; }
	final public function PreconditionFailed 			(){ return 412; }
	final public function RequestEntityTooLarge 		(){ return 413; }
	final public function RequestURITooLong 			(){ return 414; }
	final public function UnsupportedMediaType 			(){ return 415; }
	final public function RequestedRangeNotSatisfiable 	(){ return 416; }
	final public function ExpectationFailed 			(){ return 417; }
	final public function InternalServerError 			(){ return 500; }
	final public function NotImplemented 				(){ return 501; }
	final public function BadGateway 					(){ return 502; }
	final public function ServiceUnavailable 			(){ return 503; }
	final public function GatewayTimeout 				(){ return 504; }
	final public function HTTPVersionNotSupported 		(){ return 505; }

	final public function Json							(){ return 'application/json; charset=utf-8;'; }
	final public function Html							(){ return 'text/html; charset=utf-8;'; }


	private $_encoder;


	public function __construct()
	{
		$this->_encoder = new JsonEncoder();
	}

	public function setEncoder( $encoder )
	{
		if( $encoder instanceof YEncoder )
			$this->_encoder = $encoder;
		else
			throw new Exception( "Invalid Encoder type." );
	}

	
	/**
	*	Default options getter.
	*/
	private function _getDefOptions()
	{
		return array( 'status' => $this->OK(), 'contentType' => $this->Json(), 'encoder' => $this->_encoder );
	}
	
	// send response
	public function send( $data = array(), $options = null ) {

		

		// get full options.
		if( is_int( $options ) )
			$options = array( 'status' => $options );
		else if( is_array( $options ) )
			{} // do not things.
		else 
			$options = array();

		$options = array_merge( $this->_getDefOptions(), $options );


		// found resource.
		if( $options[ 'status' ] == $this->Found() )
		{
			// set header
			$tpl = $this->_getResponseTemplate( $this->Found() );
			$statusHeader = 'HTTP/1.1 ' . $tpl[ 'c' ] . ' ' . $tpl[ 'm' ];
			header( $statusHeader );
			header( 'Content-type: ' . $options[ 'contentType' ] );
			header( 'Location: ' . $data );
		}
			

	    // send response.
	    if( is_array( $data ) )
	    {
	    	// prepare reponse template.
			// c : status code.
			// m : status message.
	   		$tpl = $this->_getResponseTemplate( $options[ 'status' ] ); 
	   		$data = array_merge( $tpl, $data );
	    }
	    else
	    {
	   		// invalid object type, response internal server error header.
	   		throw new Exception( "invalid object type, response internal server error header." );
		    $data = $this->_getResponseTemplate( $this->InternalServerError() );
		}   

		// set header.
		$statusHeader = 'HTTP/1.1 ' . $data[ 'c' ] . ' ' . $data[ 'm' ] ;
		header( $statusHeader );
		header( 'Content-type: ' . $options[ 'contentType' ] );
		echo $options[ 'encoder' ]->process( $data );	 
		ob_end_flush();
		exit();

	}


	private function _getResponseTemplate( $status )
	{
		return array( 'c' => $status, 'm' => $this->_getStatusCodeMessage( $status ) );
	}

	private function _getStatusCodeMessage( $status )
	{
		
		// define response message in header by status code.
	    $codes = Array(

	        $this->OK() 					=> 'OK',
	        $this->BadRequest() 			=> 'Bad Request',
	        $this->Unauthorized()	 		=> 'Unauthorized',
	        $this->PaymentRequired()		=> 'Payment Required',
	        $this->Forbidden() 				=> 'Forbidden',
	        $this->NotFound()	 			=> 'Not Found',
	        $this->InternalServerError()	=> 'Internal Server Error',
	        $this->NotImplemented() 		=> 'Not Implemented',
	        $this->Found()	 				=> 'Found'

	    );

	    return ( isset( $codes[ $status ] ) ) ? $codes[ $status ] : '';
	    
	}
	
	
}


?>