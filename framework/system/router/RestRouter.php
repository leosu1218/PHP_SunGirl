<?php 
ob_start();
require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

/**
* REST router.
*/
class RestRouter implements SnRouter
{

	public function __construct($logPath="") {

        $this->logger = new LoggerHelper("framework", $logPath);

		// get request verb.
		$this->verb = $_SERVER[ 'REQUEST_METHOD' ];
		$this->uri  = $this->_getCorrectUri( $_SERVER[ 'REQUEST_URI' ] );
	}

    /**
     * Get router's default exception configs.
     * @param $controller
     * @return array
     */
    private function getDefaultExceptions($controller) {
       return array(
           array("OperationConflictException",      $controller->responser->Conflict()),
           array("DataAccessResultException",       $controller->responser->InternalServerError()),
           array("DbOperationException",            $controller->responser->InternalServerError()),
           array("InvalidAccessParamsException",    $controller->responser->BadRequest()),
           array("AuthorizationException",          $controller->responser->Forbidden()),
           array("Exception",                       $controller->responser->InternalServerError()),
       );
    }

	private function getPatternVerb( $subject ) {

		$verb = '';
		$pattern = '/^(POST|GET|PUT|DELETE):/';
		$isMatch = preg_match_all($pattern, $subject, $matches);

		if($isMatch) {
			$verb = $matches[1][0];
		}

		return $verb;
	}

	private function getPatternUri( $subject ) {

		$patterns = array();
		$replacements = array();

		$patterns[0] = '/^POST:/';
		$patterns[1] = '/^GET:/';
		$patterns[2] = '/^PUT:/';
		$patterns[3] = '/^DELETE:/';

		$replacements[0] = '';
		$replacements[1] = '';
		$replacements[2] = '';
		$replacements[3] = '';

		$string = preg_replace($patterns, array(), $subject);
		$string = trim($string);

		return $string;
	}


	/**
	*	Load routring paramters. and instanced class and call method.
	*
	*/
	public function load( $paramters )
	{
		// set contorller root.
		$this->_ctrlRoot 	= $paramters[ 'ctrlRoot' ];

		// set default controller.
		$this->_defCtrl	 	= $paramters[ 'default' ][ 0 ];
		$this->_defMethod	= $paramters[ 'default' ][ 1 ];

		$requestUriPaths = explode('/', $this->uri );
		array_shift( $requestUriPaths );

		foreach( $paramters[ 'patterns' ] as $key => $value ) {

			$patternStr 	= $value[ 0 ];
			$patternFile 	= $value[ 1 ];
			$patternFunc 	= $value[ 2 ];

			$verb  	 	= $this->getPatternVerb( $patternStr );

			if( $verb == $this->verb ) {
				
				$patternUri = $this->getPatternUri($patternStr); 
				
				$patternUriPaths = explode('/', $patternUri); 
				array_shift( $patternUriPaths ); 

				if( count($patternUriPaths) == count($requestUriPaths) ) {

					$isPatternFound = true;
					$methodArguments = array();

					foreach ($patternUriPaths as $key => $patternUriPath) {

						$requestUriPath = $requestUriPaths[$key];
						$path = new RegExpPath($patternUriPath);

						if( $path->isValid() ) {

							$argumentName 	= $path->getArgumentName();
							$pattern 		= $path->getPattern();

							if( preg_match( $pattern, $requestUriPath, $matches) ) {
								$methodArguments[ $argumentName ] = $matches[0];
							}
							else {
								// pattern not match.
								$isPatternFound = false;
								break 1;
							}
						}
						else if( $requestUriPath != $patternUriPath ) {
							// path not match
							$isPatternFound = false;
							break 1;
						}
					}

					if($isPatternFound) {
						$this->forwardToController($patternFunc, $patternFile, $methodArguments, $verb);
						exit();
					}
				}
			}
		}

		// Not matched any patterns.
		$this->forwardDefaultController();
	}

    /**
     * @param $controllerMethod
     * @param $controllerFile
     * @param $methodArguments
     * @throws Exception
     */
	public function forwardToController( $controllerMethod, $controllerFile, $methodArguments, $verb ) {

		require_once( $this->_ctrlRoot . "/" . $controllerFile . ".php" );
		$funcData 	= $this->_getFuncData( $controllerMethod );
		$paramArray = $this->_paramArrGenerator( $funcData[ 'params' ], $methodArguments );
		$instance 	= new $controllerFile();

        try {
            $result 	= call_user_func_array( array( $instance, $funcData[ 'name' ] ), $paramArray );
            $this->handleResultSuccess($result, $instance, $verb);
        }
        catch(Exception $e) {
            $this->handleControllerException($instance, $e);
        }
	}

    /**
     * Handle controller exception execute.
     * @param RestController $instance
     * @param Exception $e
     */
    private function handleControllerException($instance, Exception $e) {
        $exceptions = array_merge($instance->getExceptions(), $this->getDefaultExceptions($instance));
        foreach($exceptions as $index => $pattern) {
            $exception = $pattern[0];
            $stateCode = $pattern[1];
            if($e instanceof $exception) {
                $this->logger->error($e->getMessage());
                $this->logger->error( "Trace exception:\n\r" . $e->getTraceAsString());
                $instance->responser->send(array(), $stateCode);
            }
        }
    }

    /**
     * Handle controller execute successfully result.
     * @param mixed $result
     * @param RestController $instance
     * @param string $verb
     */
    private function handleResultSuccess($result, $instance, $verb) {
        if($verb == "POST") {
            $instance->responser->send($result, $instance->responser->Created());
        }
        else {
            $instance->responser->send($result, $instance->responser->OK());
        }
    }

	/**
	*	轉送執行預設的Controller
	*
	*/
	public function forwardDefaultController() {
		require_once( $this->_ctrlRoot . "/" . $this->_defCtrl . ".php" );
		$instance = new $this->_defCtrl();

        try {
            $result 	= call_user_func( array( $instance, $this->_defMethod ), array() );
            $this->handleResultSuccess($result, $instance, "GET");
        }
        catch(Exception $e) {
            $this->handleControllerException($instance, $e);
        }
	}


	/**
	*	get function name and params from function pattern string.
	*	e.g. array( 'name' => "Get", 'params' => array( "addr", "type" ) );
	*
	*	@param string $patternFuncStr function pattern string from router settings.
	*	@return array
	*/
	private function _getFuncData( $patternFuncStr )
	{
		if( preg_match_all( '/(^[a-zA-Z_]\w*)\((.*)\)/', $patternFuncStr, $matches ) )
		{
			$name = $matches[1][0];

			if( $matches[2][0] == "" )
			{
				// have not input args. func()
				$params = array();
			}				
			else				
			{
				//have input args. func(<input1>, <input2>)
				if( preg_match_all( '/<(\w+)>/', $matches[2][0], $matches ) )
					$params = $matches[1];
				else
					$params = array();
			}
				

			return array( 'name' => $name, 'params' => $params );
		}
			
		else
			throw new Exception( "Invalid function name format." );
	}

	/**
	*	generate function params data. e.g. array( "value1", "value2" )
	*
	*	@param array $funcParam function parameters format. e.g. array( 0 => "name1", 1 => "name2" )
	*	@param array $args args function input data. e.g. array( "name1" => "value1", "name2" => "value2" )
	*	@return array
	*/
	private function _paramArrGenerator( $funcParam, $args )
	{
		$paramArray = array();

		foreach ($funcParam as $key => $value) 
		{
			if( isset( $args[ $value ] ) )
				$paramArray[] = $args[ $value ];
		}

		return $paramArray;
	}

	/**
	*	format concrete request uri. index.php/api/polling/716f1ae8ca9ff1ba5c -> /api/polling/716f1ae8ca9ff1ba5c
	*
	*	@param string $requestUri 	request uri from server.
	*	@return string
	*/
	private function _getCorrectUri( $requestUri)
	{
		$scriptNamePattern = preg_replace( '/\./', '\.', $_SERVER['SCRIPT_NAME'] );
		$scriptNamePattern = preg_replace( '/\//', '\/', $scriptNamePattern ) . '\/';
		
		if( $_SERVER['SCRIPT_NAME'] == $requestUri ) {
			$requestUri = '/';
		}
		else if( !preg_match('/^' . $scriptNamePattern . '/', $requestUri) ) {

			$referenceUri = preg_replace('/(\/\w+\.php)/', '', $_SERVER['SCRIPT_NAME']);
			$requestUri = str_replace( $referenceUri, '', $requestUri );
		}	
		else {
			
			$requestUri = '/' . preg_replace( '/^' . $scriptNamePattern . '/', '', $requestUri );
		}


		return $this->fixRequestUriEndChar( $requestUri );
	}

	private function fixRequestUriEndChar($requestUri) {

		if( substr($requestUri, -1) == '/' ) {
			$requestUri = substr($requestUri, 0, -1);
		}

		if( $requestUri == '' ) {
			$requestUri = '/';
		}

		return $requestUri;
	}

}

	/**
	*	一個可以解析的路徑規則
	*/
	class RegExpPath {

		private $path;
		private $isValid;
		private $argumentName;
		private $pattern;

		public function __construct ( $path ) {

			$this->path = $path;

			if( $this->isRegex($path) ) {

				$this->isValid = true;
				$this->saveParameters($path);
			}
			else {
				$this->isValid = false;
			}
		}

		public function isValid() {
			return $this->isValid;
		}

		public function getPattern() {
			return $this->pattern;
		}

		public function getArgumentName() {
			return $this->argumentName;
		}

		/**
		*  判斷是否是regexp的路徑
		*/
		private function isRegex($path) {

			$headChar 	= substr($path, 0, 1);
			$footerChar = substr($path, count($path) - 2, 1);

			if( ($headChar == "<") && ($footerChar == ">") ) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
		*	儲存regexp, argument name兩個參數
		*/
		private function saveParameters( $path ) {

			$parameters = substr($path, 1, count($path) - 2);
			$this->savePattern($parameters);
			$this->saveArgumentName($parameters);
		}

		/**
		*	儲存argument name
		*/
		private function saveArgumentName( $parameters ) {

			$isValidParameters = preg_match('/(\w+):/', $parameters, $matches);
			if($isValidParameters) {
				$this->argumentName = $matches[1];
			}
			else {
				throw new Exception("Argument not found on pattern path", 1);
			}
		}

		/**
		*	儲存regexp
		*/
		private function savePattern( $parameters ) {

			$pattern = preg_replace('/\w+:/', '', $parameters);
			if(strlen($pattern) > 0) {
				$this->pattern = "/$pattern/";
			}
			else {
				throw new Exception("Pattern not define on pattern path", 1);
			}
		}
	}



?>


























