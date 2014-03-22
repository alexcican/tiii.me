<?php
/**
 * Panada Pagination class.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>. Thanks to Wordpress {@link http://codex.wordpress.org/Function_Reference/paginate_links}
 * @since	Version 0.1
 */
namespace Resources;

class Pagination
{    
    /**
     * @var boolean Show the number. If you set it to false, so it would return next and previous only.
     */
    public $showNumber = true;
    
    /**
     * @var string  http://example.com/allposts/%_% : %_% is replaced by format properties.
     */
    public $urlReference = '';
    
    /**
     * @var string  http://example.com/allposts/%#% : %#% is replaced by the page number.
     */
    public $format = '/%#%/';
    
    /**
     * @var integer Total number all record.
     */
    public $total = 1;
    
    /**
     * @var integer Limit record per page.
     */
    public $limit = 5;
    
    /**
     * @var integer Current page location: welcome/comments/1, welcome/comments/2, welcome/comments/3 etc...
     */
    public $current = 0;
    
    /**
     * @var boolean Show all page number and it link. I dont think you gonna like this.
     */
    public $showAll = false;
    
    /**
     * @var boolean If you wanna get the output without any href html tag, then set $noHref = true;. You will get something like this Array([link] => paging link [value] => paging value) for each item.
     */
    public $noHref = false;
    
     /**
      * @var boolean Show previous and next link.
      */
    public $prevNext = true;
    
    /**
     * @var string The "previous" html character/string.
     */
    public $prevText = '&laquo; Previous';
    
    /**
     * @var string The "next" html character/string.
     */
    public $nextText = 'Next &raquo;';
    
    /**
     * @var string The separator between one block number to enother.
     */
    public $groupSeparator = '...';
    
    /**
     * @var integer How many numbers on either end including the end.
     */
    public $endSize = 1;
    
    /**
     * @var integer How many numbers to either side of current not including current.
     */
    public $midSize = 2;
    
    
    /**
     * Class contructor
     */
    public function __construct()
    {    
        /**
         * Make sure the argument type only integer.
         */
        $this->total    = (int) $this->total;
        $this->limit    = (int) $this->limit;
        $this->current  = (int) $this->current;
        $this->endSize = (int) $this->endSize;
        $this->midSize = (int) $this->midSize;
    }
    
    /**
     * Setter for option
     *
     * @param string | array $var
     * @param mix $value
     * @return void
     */
    public function setOption($var, $value = false)
    {    
        if( is_string($var) )
            $this->$var = $value;
        
        if( is_array($var) )
            foreach($var as $key => $value)
                $this->$key = $value;
        
        return $this;
    }
    
    /**
     * Create pagination.
     *
     * @return array
     */
    public function getUrl()
    {
        $total    = ceil($this->total / $this->limit);
        if ( $total < 2 )
            return;
        
        $endSize   = (0 < $this->endSize) ? $this->endSize : 1; // Out of bounds?  Make it the default.
        $midSize   = (0 <= $this->midSize) ? $this->midSize : 2;
        
        $r          = '';
        $paging_url = array();
        $n          = 0;
        $dots       = false;
        
        if ( $this->prevNext && $this->current && 1 < $this->current ) {
           
            $link         = str_replace('%#%', $this->current - 1, $this->base);
            $paging_url[] = ( $this->noHref )? array('link' => $link, 'value' => $this->prevText) : '<a href="'.$link.'">'.$this->prevText.'</a>';
            
        }
        
        for ( $n = 1; $n <= $total; $n++ ) {
            
            if ( $n == $this->current ) {
                
                if($this->showNumber){
                    
                    $paging_url[] = ( $this->noHref )? array('link' => '', 'value' => $n) : '<span>'.$n.'</span>';
                    $dots = true;
                }
            }
            else {
                
                if ( $this->showAll || ( $n <= $endSize || ( $this->current && $n >= $this->current - $midSize && $n <= $this->current + $midSize ) || $n > $total - $endSize ) ) {
                    
                    $link = str_replace('%_%', ($n == 1)? '' : $this->format, $this->base);
                    $link = str_replace('%#%', $n, $link);
                    
                    if($this->showNumber){
                        
                        $paging_url[] = ( $this->noHref )? array('link' => $link, 'value' => $n) : '<a href="'.$link.'">'.$n.'</a>';
                        $dots = true;
                    }
                }
                elseif ( $dots && !$this->showAll ) {
                    
                    $paging_url[] = ( $this->noHref )? array('link' => '', 'value' => $this->groupSeparator) : '<span>'.$this->groupSeparator.'</span>';
                    $dots = false;
                }
            }
            
        }
        
        if ( $this->prevNext && $this->current && ( $this->current < $total || -1 == $total ) ) {
            
            $link           = str_replace('%_%', $this->format, $this->base);
            $link           = str_replace('%#%', $this->current + 1, $link);
            
            $paging_url[]   = ( $this->noHref )? array('link' => $link, 'value' => $this->nextText) : '<a href="'.$link.'">'.$this->nextText.'</a>';
        }
        
        return $paging_url;
    }
    
}