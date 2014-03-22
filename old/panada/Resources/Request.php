<?php
/**
 * Panada Request/input Handler.
 * 
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

class Request
{    
    /**
     * Handler for HTTP GET request
     *
     * @param mix $key
     * @param int $filterType
     * @param int $flags
     * @return mix
     */
    public function get($key = false, $filterType = false, $flags = false)
    {    
        if( ! $key )
            return filter_var_array($_GET, $filterType);
        
        if( ! isset($_GET[$key]) )
            return false;
        
        if( $filterType )
           return filter_input(INPUT_GET, $key, $filterType, $flags);
        else
            return $_GET[$key];
        
    }
    
    /**
     * Handler for HTTP POST request
     *
     * @param mix $key
     * @param int $filterType
     * @param int $flags
     * @return mix
     */
    public function post($key = false, $filterType = false, $flags = false)
    {    
        if( ! $key )
            return filter_var_array($_POST, $filterType);
        
        if( ! isset($_POST[$key]) )
            return false;
        
        if( $filterType )
           return filter_input(INPUT_POST, $key, $filterType, $flags);
        else
            return $_POST[$key];
        
    }
    
    /**
     * Handler for HTTP POST request
     *
     * @param mix $key
     * @param int $filterType
     * @param int $flags
     * @return mix
     */
    public function cookie($key, $filterType = false, $flags = false)
    {    
        if( ! $key )
            return filter_var_array($_COOKIE, $filterType);
        
        if( ! isset($_COOKIE[$key]) )
            return false;
        
        if( $filterType )
           return filter_input(INPUT_COOKIE, $key, $filterType, $flags);
        else
            return $_COOKIE[$key];
    }
    
    /**
     * Strip any html tags and attributes defined by user
     *
     * @param string $str
     * @param string | array $allowtags
     * @param string | array $allowattributes
     * @return string
     */
    public function stripTagsAttributes($str, $allowtags = null, $allowattributes = null)
    {    
        /**
         * ID:  Ada kemungkinan dimana string yang diinput diconvert dulu menjadi htmlentities.
         *      Untuk menghindari hal ini, maka semua format htmlentities dikembalikan (docode) dulu ke format aslinya.
         *
         *      $str = html_entity_decode($str, ENT_QUOTES);
         */
        
        /**
         * ID:  Jika string < diikuti dengan tanda non-alpha selain tanda ?, maka ubah menjadi &lt; (htmlentities)
         *      Ini berguna jika string yang diinput berupa emotion code seperpti <*_*> atau tanda panah <=
         */
        // Original $str = preg_replace(array('/<\*/', '/<=/', '/_/'), '&lt;\\1', $str);
        $str = preg_replace(array('/<\*/', '/<=/'), '&lt;\\1', $str);
        
        /**
         * ID:  Hapus semua tag html dan php yang tidak didefinisikan dari input string.
         */
        $str = strip_tags($str, $allowtags);
        
        /**
         * ID:  Kembalikan string &lt; menjadi <
         */
        $str = str_replace('&lt;', '<', $str);
        
        /**
         * See original function at http://php.net/manual/en/function.strip-tags.php#91498
         */
        if ( ! is_null($allowattributes) ) {
            
            if( ! is_array($allowattributes) )
                $allowattributes = explode(",", $allowattributes);
                
            if( is_array($allowattributes) )
                $allowattributes = implode(")(?<!",$allowattributes);
                
            if ( strlen($allowattributes) > 0 )
                $allowattributes = "(?<!".$allowattributes.")";
                
            $str = preg_replace_callback("/<[^>]*>/i",create_function(
                '$matches',
                'return preg_replace("/ [^ =]*'.$allowattributes.'=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'   
            ),$str);
        }
        
        return $str;
    }
    
}