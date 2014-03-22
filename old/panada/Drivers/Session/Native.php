<?php
/**
 * Panada PHP native session handler.
 *
 * @package	Driver
 * @subpackage	Session
 * @author	Iskandar Soesman
 * @since	Version 0.1
 */
namespace Drivers\Session;
use Resources\Interfaces as Interfaces;

class Native implements Interfaces\Session
{    
    /**
    * @var integer	This variable set the maximum life in seconds of a session file on the server since last activity.
    */
    public $sesionExpire = 7200; //second or 2 hour
    
    /**
     * @var string	Change the default PHP session name (PHPSESSIONID) to Panada session name (PAN_SID).
     */
    public $sessionName = 'PAN_SID';
    
    /**
     * @var integer	Sets the session cookies to N seconds.
     */
    public $sessionCookieExpire = 0;
    
    /**
     * @var string	This session id.
     */
    public $sessionId;
    
    /**
     * @var string	Session cookie path.
     */
    public $sessionCookiePath = '/';
    
    /**
     * @var boolean	Define the cookie only working on https or not.
     */
    public $sessionCookieSecure = false;
    
    /**
     * @var string	Define the cookie domain.
     */
    public $sessionCookieDomain = '';
    
    /**
     * @var string	Where we store the session? file (PHP native) or db.
     */
    public $sessionStore = 'native';
    
    /**
     * Class constructor.
     *
     * Set costumized PHP Session parameter.
     *
     * @return void
     */
    
    public function __construct( $config )
    {
	$this->sesionExpire	    = $config['expiration'];
	$this->sessionName	    = $config['name'];
	$this->sessionCookieExpire  = $config['cookieExpire'];
	$this->sessionCookiePath    = $config['cookiePath'];
	$this->sessionCookieSecure  = $config['cookieSecure'];
	$this->sessionCookieDomain  = $config['cookieDomain'];
	$this->sessionStore	    = $config['driver'];
	
	\ini_set('session.gc_maxlifetime', $this->sesionExpire);
	
	\session_set_cookie_params(
	    $this->sessionCookieExpire,
	    $this->sessionCookiePath,
	    $this->sessionCookieDomain,
	    $this->sessionCookieSecure
	);
	
        \session_name($this->sessionName);
	
	if ( \session_id() == '' ){
	    
	    /**
	     * ID: Pada OS Debian/Ubuntu saat melakukan proses GC (garbage collection)
	     * Akan muncul error "failed: Permission denied". Hal ini karena secara default
	     * Debian hanya mengijinkan root untuk memodifikasi file session yang ada di
	     * dalam folder /var/lib/php5.
	     */
	    
	    @\session_start();
	    $this->sessionId = \session_id();
	}
        
    }
    
    /**
     * Get next time in second. Default is 300 sec or five minute.
     *
     * @return int
     */
    protected function upcomingTime($s = 300)
    {
	return \strtotime('+'.$s.' sec');
    }
    
    /**
     * Remove existing session file/record and cookie then replace it with new one but still with old values.
     *     However, automatic session regeneration isn't recommended because it can cause a race condition when
     *     you have multiple session requests while regenerating the session id (most commonly noticed with ajax
     *     requests). For security reasons it's recommended that you manually call regenerate() whenever a visitor's
     *     session privileges are escalated (e.g. they logged in, accessed a restricted area, etc).
     *     
     *
     * @return void
     */
    public function regenerateId()
    {
	\session_regenerate_id(true);
	$this->sessionId = \session_id();
    }
    
    /**
     * Save new session
     *
     * @param string|array
     * @param string|array|object
     * @return void
     */
    public function setValue($name, $value = '')
    {    
	if( \is_array($name) ) {
	    foreach($name AS $key => $val)
		$_SESSION[$key] = $val;
	}
	else {
	    $_SESSION[$name] = $value;
	}
    }

    /**
     * Get the session vale.
     *
     * @param string
     * @return string|array|object
     */
    public function getValue($name)
    {    
	if(isset($_SESSION[$name]))
	    return $_SESSION[$name];
	else
	    return false;
    }
    
    /**
     * Remove/unset the session value.
     *
     * @param string
     * @return void
     */
    public function deleteValue($name)
    {    
	unset($_SESSION[$name]);
    }
    
    /**
     * Complitly remove the file session at the server and the cookie file in user's browser.
     *
     * @return void
     */
    public function destroy( $setExpireHeader = false )
    {
	if( $setExpireHeader ){
	    header('Expires: Mon, 1 Jul 1998 01:00:00 GMT');
	    header('Cache-Control: no-store, no-cache, must-revalidate');
	    header('Cache-Control: post-check=0, pre-check=0', false);
	    header('Pragma: no-cache');
	    header('Last-Modified: ' . \gmdate( 'D, j M Y H:i:s' ) . ' GMT' );
	}
	
	$params = \session_get_cookie_params();
	
	\setcookie($this->sessionName, '', time() - 42000,
	    $params['path'], $params['domain'],
	    $params['secure'], $params['httponly']
	);
	
	\session_unset();
	\session_destroy();
    }
}