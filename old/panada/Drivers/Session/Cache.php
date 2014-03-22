<?php
/**
 * Panada Objet Cache session handler.
 *
 * @package	Driver
 * @subpackage	Session
 * @author	Iskandar Soesman
 * @since	Version 0.3
 */
namespace Drivers\Session;
use Drivers\Session\Native, Resources;

class Cache extends Native
{    
    private $sessionStorageName = 'sessions_';
    
    public function __construct( $config )
    {    
	$this->sessionStorageName   = $config['storageName'].'_';
        $this->cache		    = new Resources\Cache( $config['driverConnection'] );
        
        session_set_save_handler (
	    array($this, 'sessionStart'),
	    array($this, 'sessionEnd'),
	    array($this, 'sessionRead'),
	    array($this, 'sessionWrite'),
	    array($this, 'sessionDestroy'),
	    array($this, 'sessionGc')
	);
        
        parent::__construct( $config );
    }
    
    /**
     * Required function for session_set_save_handler act like constructor in a class
     *
     * @param string
     * @param string
     * @return void
     */
    public function sessionStart($savePath, $sessionName)
    {
	//We don't need anythings at this time.
    }
    
    /**
     * Required function for session_set_save_handler act like destructor in a class
     *
     * @return void
     */
    public function sessionEnd()
    {
	//we also don't have do anythings too!
    }
    
    /**
     * Read session from db or file
     *
     * @param string $id The session id
     * @return string|array|object|boolean
     */
    public function sessionRead($id)
    {    
        return $this->cache->getValue($this->sessionStorageName.$id);
    }
    
    /**
     * Write the session data
     *
     * @param string
     * @param string
     * @return boolean
     */
    public function sessionWrite($id, $sessData)
    {
	if( $this->sessionRead($id) )
            return $this->cache->updateValue($this->sessionStorageName.$id, $sessData, $this->sesionExpire);
	else
            return $this->cache->setValue($this->sessionStorageName.$id, $sessData, $this->sesionExpire);
    }
    
    /**
     * Remove session data
     *
     * @param string
     * @return boolean
     */
    public function sessionDestroy($id)
    {
	return $this->cache->deleteValue($this->sessionStorageName.$id);
    }
    
    /**
     * Clean all expired record in db trigered by PHP Session Garbage Collection.
     * All cached session object will automaticly removed by the cache service, so we
     * dont have to do anythings.
     *
     * @return void
     */
    public function sessionGc($maxlifetime = 0)
    {
	//none
    }
}