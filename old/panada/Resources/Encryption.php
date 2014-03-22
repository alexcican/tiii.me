<?php
/**
 * Panada Encyption class.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

if( ! defined('PCRYPT_NONE') )
    define('PCRYPT_NONE', 'none');

if( ! defined('PCRYPT_BASE_64') )
    define('PCRYPT_BASE_64', 'base_64');

if( ! defined('PCRYPT_HEXA_DECIMAL') )
    define('PCRYPT_HEXA_DECIMAL', 'hexa_decimal');

class Encryption
{    
    /**
     * @var string  Encoding type. none | base_64 | hexa_decimal
     */
    public $encodeType;
    
    /**
     * @var string  The encrypt/decrypt key.
     */
    public $key;
    
    public function __construct($key = false, $encodeType = PCRYPT_BASE_64)
    {    
        $this->encodeType = $encodeType;
        
        if($key)
            $this->key = $key;
    }
    
    /**
     * Produce encryption without Mcrypt modul.
     *
     * @var string
     * @var string
    */
    public function encrypt($string)
    {    
        return $this->simpleEncrypt($string);
    }
    
    /**
     * Decryption method without Mcrypt modul.
     *
     * @var string
     * @var string
     * @return string
     * @access public
    */
    public function decrypt($string)
    {    
        return $this->simpleDecrypt($string);
    }
    
    /**
     * Create the ciphertext string.
     *
     * @param string
     * @return string
     * @access public
     */
    private function simpleEncrypt($string)
    {    
        $return = '';
        
        for($i=0; $i < strlen($string); $i++) {
            
            $str     = substr($string, $i, 1);
            $return .= chr( ord($str) + ord( substr($this->key, ($i % strlen($this->key))-1, 1) ) );
        }
      
        return $this->encode($return);
    }
    
    /**
     * Create the plain text string.
     *
     * @param string
     * @return string
     * @access public
     */
    private function simpleDecrypt($string)
    {    
        $return = '';
        
        $string = $this->decode($string);
      
        for($i=0; $i<strlen($string); $i++) {
            
            $str     = substr($string, $i, 1);
            $return .= chr( ord($str) - ord( substr($this->key, ($i % strlen($this->key))-1, 1) ) );
        }
        
        return $return;
    }
    
    /**
     * Encode the encypted string.   
     *
     * @param string
     * @return string
     * @access private
     */
    private function encode($string)
    {    
        if($this->encodeType == 'base_64')
            return base64_encode($string);
        elseif($this->encodeType == 'hexa_decimal')
            return $this->hexaEncode($string);
        else
            return $string;
    }
    
    /**
     * Decode the encypted string.
     *
     * @param string
     * @return string
     * @access private
     */
    private function decode($string)
    {    
        if($this->encodeType == 'base_64')
            return base64_decode($string);
        elseif($this->encodeType == 'hexa_decimal')
            return $this->hexaDecode($string);
        else
            return $string;
    }
    
    /**
     * Encode the binary into hexadecimal.
     *
     * @param string
     * @return string
     * @access private
     */
    private function hexaEncode($string)
    {    
        $string = (string) $string;
        return preg_replace("'(.)'e", "dechex(ord('\\1'))", $string);
    }
    
    /**
     * Decode the hexadecimal code into binary.
     *
     * @param string
     * @return string
     * @access private
     */
    private function hexaDecode($string)
    {    
        $string = (string) $string;
        return preg_replace("'([\S,\d]{2})'e", "chr(hexdec('\\1'))", $string);
    }
    
}