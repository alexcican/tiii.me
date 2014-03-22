<?php
/**
 * Panada Restful class.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license	http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

class Rest
{    
    public
	$requestMethod,
	$responseStatus,
	$requestData		= array(),
	$setRequestHeaders	= array(),
	$responseOutputHeader	= false,
	$timeout		= 30,
	$curlSSLVerifypeer	= true,
	$requestHeaders		= array('User-Agent' => 'Panada PHP Framework REST API/0.3');
    
    public function __construct()
    {
	/**
	* Makesure Curl extension is enabled
	*/
	if( ! extension_loaded('curl') )
	    throw new RunException('Curl extension that required by Rest Resource is not available.');
	
	// Get the client request method
	$this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Getter for requstMethod
     *
     * @return string
     */
    public function getRequestMethod()
    {
	return $this->requestMethod;
    }
    
    /**
     * Getter for responseStatus
     *
     * @return int
     */
    public function getResponseStatus()
    {
	return $this->responseStatus;
    }
    
    /**
     * Getter for requestData
     *
     * @return array
     */
    public function getRequestData()
    {
	return $this->requestData;
    }
    
    /**
     * Get clent HTTP Request type.
     *
     * @return array
     */
    public function getRequest()
    {
	// Use PHP Input to get request PUT, DELETE, HEAD, TRACE, OPTIONS, CONNECT and PATCH
        
        return $_REQUEST;
    }
    
    /**
     * Get clent file(s) submited by POST or PUT.
     *
     * @return array
     */
    public function getFiles()
    {
	return $_FILES;
    }
    
    /**
     * Get client request headers
     *
     * @return array
     */
    public function getClientHeaders()
    {
	$headers = array();
	
	foreach ($_SERVER as $key => $val){
	    
	    if (substr($key, 0, 5) == 'HTTP_'){
		
		$key = str_replace('_', ' ', substr($key, 5));
		$key = str_replace(' ', '-', ucwords(strtolower($key)));
		
		$headers[$key] = $val;
	    }
	}
	
	return $headers;
    }  
    
    /**
     * See this trick at http://www.php.net/manual/en/function.curl-setopt.php#96056
     *
     * @return array
     */
    private function getPHPInput()
    {
	parse_str(file_get_contents('php://input'), $put_vars);
        return $put_vars;
    }
    
    /**
     * Set additional HTTP Request headers
     *
     * @param array $options
     * @return void
     */
    public function setRequestHeaders( $options = array() )
    {
	$this->requestHeaders = array_merge($this->requestHeaders, $options);
    }
    
    /**
     * Set HTTP Headers Authorization
     *
     * @param string $signature Secret string to access the server
     * @param string $type The Auth type eg: Basic, OAuth etc
     * @return void
     */
    public function setRequestAuthorization($signature, $type = 'Basic')
    {
	$this->requestHeaders = array_merge($this->requestHeaders, array('Authorization' => $type.' '.$signature) );
    }
    
    /**
     * Send HTTP Request to server
     *
     * @param string $uri The server's URL
     * @param string $method HTTP Request method type
     * @param array | string $data The data that need to send to server
     * @return booeal if false and string if true
     */
    public function sendRequest( $uri, $method = 'GET', $data = null )
    {
	foreach($this->requestHeaders as $key => $value)
	    $this->setRequestHeaders[] = $key.': '.$value;
	
	$method				= strtoupper($method);
        $urlSeparator			= ( parse_url( $uri, PHP_URL_QUERY ) ) ? '&' : '?';
        $uri				= ( $method == 'GET' && ! empty($data) ) ? $uri . $urlSeparator . (is_array($data) ? http_build_query($data) : $data) : $uri;
        $c				= curl_init();
	
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_URL, $uri);
	curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $this->curlSSLVerifypeer);
        
        if($this->responseOutputHeader)
            curl_setopt($c, CURLOPT_HEADER, true);
	
        if( $method != 'GET' ) {
	    
	    if( is_array($data) )
		$data = http_build_query($data);
	    
	    if( $method == 'POST' )
		curl_setopt($c, CURLOPT_POST, true);
	    
	    if( $method == 'PUT' || $method == 'DELETE' ) {
		$this->setRequestHeaders[] = 'Content-Length: ' . strlen($data);
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
	    }
	    
	    curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        }
	
	curl_setopt($c, CURLOPT_HTTPHEADER, $this->setRequestHeaders);
	curl_setopt($c, CURLINFO_HEADER_OUT, true);
	
        $contents = curl_exec($c);
	$this->responseStatus = curl_getinfo($c, CURLINFO_HTTP_CODE);
	$this->headerSent = curl_getinfo($c, CURLINFO_HEADER_OUT);
        
        curl_close($c);
	
        if($contents)
	    return $contents;
        
        return false;
    }
    
    /**
     * Set HTTP Response headers code
     *
     * @return void
     */
    public function setResponseHeader($code = 200)
    {
	Tools::setStatusHeader($code);
    }
    
    /**
     * The return data type
     *
     * @param array $data
     * @param string $format
     * @param string $ContentType
     * @return string
     */
    public function wrapResponseOutput($data, $format = 'json', $ContentType = 'application')
    {    
        header('Content-type: '.$ContentType.'/' . $format);
	
	if($format == 'xml')
	    return Tools::xmlEncode($data);
	else
	    return json_encode($data);
    }
    
}