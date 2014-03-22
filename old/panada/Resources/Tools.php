<?php
/**
 * Panada Tools Class.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license	http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

class Tools
{    
    /**
     * Set the header response status.
     *
     * @param int
     * @param string
     * @return void
     */
    public static function setStatusHeader($code = 200, $text = '')
    {    
	$status = array(
                200	=> 'OK',
                201	=> 'Created',
                202	=> 'Accepted',
                203	=> 'Non-Authoritative Information',
                204	=> 'No Content',
                205	=> 'Reset Content',
                206	=> 'Partial Content',
                300	=> 'Multiple Choices',
                301	=> 'Moved Permanently',
                302	=> 'Found',
                304	=> 'Not Modified',
                305	=> 'Use Proxy',
                307	=> 'Temporary Redirect',
                400	=> 'Bad Request',
                401	=> 'Unauthorized',
                403	=> 'Forbidden',
                404	=> 'Not Found',
                405	=> 'Method Not Allowed',
                406	=> 'Not Acceptable',
                407	=> 'Proxy Authentication Required',
                408	=> 'Request Timeout',
                409	=> 'Conflict',
                410	=> 'Gone',
                411	=> 'Length Required',
                412	=> 'Precondition Failed',
                413	=> 'Request Entity Too Large',
                414	=> 'Request-URI Too Long',
                415	=> 'Unsupported Media Type',
                416	=> 'Requested Range Not Satisfiable',
                417	=> 'Expectation Failed',
                500	=> 'Internal Server Error',
                501	=> 'Not Implemented',
                502	=> 'Bad Gateway',
                503	=> 'Service Unavailable',
                504	=> 'Gateway Timeout',
                505	=> 'HTTP Version Not Supported'
            );
	
        if (isset($status[$code]) AND $text == '')		
            $text = $status[$code];
        
        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;
	
        if (substr(php_sapi_name(), 0, 3) == 'cgi')
            header("Status: $code $text", true);
        elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0')
            header($server_protocol." $code $text", true, $code);
        else
            header("HTTP/1.1 $code $text", true, $code);
    }
    
    /**
     * Create random string
     *
     * @param integer
     * @param boolean
     */
    public static function getRandomString($length = 12, $specialChars = true)
    {    
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
	if ( $specialChars )
	    $chars .= '!@#$%^&*()';
        
	$str = '';
	for ( $i = 0; $i < $length; $i++ )
	    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        
	return $str;
    }
    
    /**
     * Thanks to djdykes {@link: http://snipplr.com/view.php?codeview&id=3491} for great array to xml class.
     * Encode an array into xml.
     *
     * @param array
     * @param string
     * @param string
     */
    public static function xmlEncode($data, $rootNodeName = 'data', $xml = null)
    {
	// Turn off compatibility mode as simple xml throws a wobbly if you don't.
	if (ini_get('zend.ze1_compatibility_mode') == 1){
	    ini_set ('zend.ze1_compatibility_mode', 0);
	}
	
	if ( is_null($xml) ){
	    $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
	}
	
	// Loop through the data passed in.
	foreach($data as $key => $value){
	    // No numeric keys in our xml please!
	    if (is_numeric($key)){
		// Make string key...
		$key = "unknownNode_". (string) $key;
	    }
	    
	    // Replace anything not alpha numeric
	    //$key = preg_replace('/[^a-z]/i', '', $key);
	    
	    // If there is another array found recrusively call this function
	    if (is_array($value)){
		$node = $xml->addChild($key);
		// Recrusive call.
		self::xmlEncode($value, $rootNodeName, $node);
	    }
	    else{
		// Add single node.
		$value = htmlentities($value);
		$xml->addChild($key,$value);
	    }
		
	}
	// Pass back as string. or simple xml object if you want!
	return $xml->asXML();
    }
    
    /**
     * Decoded xml variable or file or url into object.
     *
     * @param string
     * @param string $type Flag for data type option: object | array
     */
    public static function xmlDecode($xml, $type = 'object')
    {
	// Does it local file?
	if( is_file($xml) ) {
	    $xml = simplexml_load_file($xml);
	}
	// Or this is an url file ?
	elseif( filter_var($xml, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) ) {
	    $xml = simplexml_load_file($xml);
	}
	// Or it just a variable.
	else {
	    $xml = new SimpleXMLElement($xml);
	}
	
	switch ($type) {
	    case 'array':
		return (array) self::objectToArray($xml);
		exit;
	    default:
		return $xml;
		exit;
	}
	
    }
    
    /**
     * Convert an object into array
     * 
     * @param object
     */
    public static function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }
    
    /**
     * Convert an array into object
     *
     * @param array $var
     */
    public static function arrayToObject($var)
    {    
        return json_decode(json_encode($var));
    }
}