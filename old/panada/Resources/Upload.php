<?php
/**
 * Panada Upload API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

if( ! defined('PIMG_RESIZE') )
    define('PIMG_RESIZE', 'resize');

if( ! defined('PIMG_CROP') )
    define('PIMG_CROP', 'crop');

if( ! defined('PIMG_RESIZE_CROP') )
    define('PIMG_RESIZE_CROP', 'resize_crop');

class Upload
{    
    /**
     * @var array   Define the $_FILES varible.
     */
    public $file;
    
    /**
     *@var object   Error message container.
     */
    public $error;
    
    /**
     * @var array   Initiate the error mesages.
     */
    public $errorMessages = array();
    
    /**
     * @var string  Folder location.
     */
    public $folderLocation = '';
    
    /**
     * @var string  Define file name manually.
     */
    public $setFileName = '';
    
    /**
     * @var boolean Need auto rename the file?
     */
    public $autoRename = false;
    
    /**
     * @var boolean Remove any space in file name.
     */
    public $stripSpaces = true;
    
    /**
     * @var integer Define maximum file size.
     */
    public $maximumSize = 0;
    
    /**
     * @var boolean Create subdirectory automaticly. The format is "destination_folder/year/month".
     */
    public $autoCreateFolder = false;
    
    /**
     * @var array   Collect the file information: name, extension, path etc...
     */
    public $getFileInfo = array();
    
    /**
     * @var string  Any files that are allowed.
     */
    public $permittedFileType = '';
    
    /**
     * @var object  Instance for Image modifier class (Library_image).
     */
    public $image;
    
    /**
     * @var string  Option to edit image base on Library_image class. The option is resize | crop | resize_crop
     */
    public $editImage = '';
    
    
    /**
     * Class constructor.
     *
     * @return void
     */
    function __construct()
    {    
        $this->initErrorMessages();
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
     * Do the Processing upload.
     *
     * @param array $_FILES variable
     * @return boolean
     */
    public function now($file)
    {    
        $this->file = $file;
        
        $this->errorHandler();
        
        if( ! empty($this->error) )
            return false;
        
        if( ! $this->upload() )
            return false;
        
        if( ! empty($this->editImage) ) {
            
            // Initiate Image class
            $this->image            = new Image;
            // See Image class line 65
            $this->image->folder    = $this->folderLocation;
            
            // Assign each config for Image class
            foreach($this->editImage as $key => $val)
                $this->image->$key = $val;
            
            if( ! $this->image->edit($this->getFileInfo['name']) ) {
               $this->_setErrorMessage(14);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * List of error messages.
     *
     * @return void
     */
    private function initErrorMessages()
    {    
        $this->errorMessages = array (
            1 => 'File upload failed due to unknown error.',
            2 => 'No folder located. Please define the folder location.',
            3 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            4 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            5 => 'The uploaded file was only partially uploaded.',
            6 => 'No file was uploaded.',
            7 => 'Missing a temporary folder.',
            8 => 'Failed to write file to disk.',
            9 => 'File upload stopped by extension.',
            10 => 'Folder you\'ve defined does not exist.',
            11 => 'Can\'t create new folder in your defined folder.',
            12 => 'Uploaded file not permitted.',
            13 => 'The uploaded file exceeds the maximum size.',
            14 => 'File uploaded, but editing image has failed with the following error(s): ',
            15 => 'Folder you specified not writeable.',
        ); 
    }
    
    /**
     * Set the error mesage.
     *
     * @param integer
     * @return void
     */
    private function _setErrorMessage($code)
    {    
        $image_error        = ($code == 14 && isset($this->image->errorMessages)) ? implode(', ', $this->image->errorMessages) : null;
        $handler            = new \stdClass;
        $handler->code      = $code;
        $handler->message   = $this->errorMessages[$code] . $image_error;
        $this->error        = $handler;
    }
    
    /**
     * Error checker before uploading proceed.
     *
     * @return void
     */
    private function errorHandler()
    {    
        /**
         * Check is folder destionation has set.
         */
        if( empty($this->folderLocation) ) {
            $this->_setErrorMessage(2);
            return false;
        }
        
        /**
         * Does it folder exist?
         */
        if( ! is_dir($this->folderLocation) ) {
            
            // Create a folder if not exits
            $arr = explode('/', $this->folderLocation);
            
            $path = '';
            if( substr($this->folderLocation, 0, 1) == '/' )
                $path = '/';
            
            foreach($arr as $name){
                
                if( empty($name) )
                    continue;
                
                $path .= $name.'/';
                
                if( ! is_dir($path) )
                    if( ! mkdir($path, 0777)) {
                        $this->_setErrorMessage(11);
                        //$this->_setErrorMessage(10);
                        return false;
                    }
            }
        }
        
        /**
         * Does it folder writable?
         */
        if( ! is_writable($this->folderLocation) ) {
            $this->_setErrorMessage(15);
            return false;
        }
        
        /**
         * Make sure the file size not more then user defined.
         */
        if( $this->maximumSize > 0 && $this->file['size'] > $this->maximumSize) {
            $this->_setErrorMessage(13);
            return false;
        }
        
        /**
        * Checking error in uploading proccess.
        */
        if($this->file['error']){
            
            switch($this->file['error']){
                
                case UPLOAD_ERR_INI_SIZE:
                    $this->_setErrorMessage(3);
                    return false;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->_setErrorMessage(4);
                    return false;
                case UPLOAD_ERR_PARTIAL:
                    $this->_setErrorMessage(5);
                    return false;
                case UPLOAD_ERR_NO_FILE:
                    $this->_setErrorMessage(6);
                    return false;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->_setErrorMessage(7);
                    return false;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->_setErrorMessage(8);
                    return false;
                case UPLOAD_ERR_EXTENSION:
                    $this->_setErrorMessage(9);
                    return false;
                default:
                    $this->_setErrorMessage(1);
            }
        }
        
        /**
         * Make sure this file are permitted.
         */
        if( ! empty($this->permittedFileType) ) {
            if ( ! preg_match( '!\.(' . $this->permittedFileType . ')$!i', $this->file['name'] ) ) {
                $this->_setErrorMessage(12);
                return false;
            }
        }
    }
    
    static function getFileExtension($file)
    {    
        return strtolower(end(explode('.', $file)));
    }
    
    /**
     * Do uploading.
     * ID: Lakukan ungguh.
     *
     * @return void
     */
    private function upload()
    {    
        $fileExtension = self::getFileExtension($this->file['name']);
        
        if($this->autoRename)
            $name = time() . rand() . '.' . $fileExtension;
        elseif( ! empty($this->setFileName) )
            $name = $this->setFileName . '.' .$fileExtension;
        else
            $name = $this->file['name'];
        
        // Remove space in file name.
        if( $this->stripSpaces)
            $name = str_replace(' ', '_', $name);
        
        // Save file extension.
        $this->getFileInfo['extension']   = $fileExtension;
        // Save file name.
        $this->getFileInfo['name']        = $name;
        // Save folder location.
        $this->getFileInfo['folder']      = $this->folderLocation;
        // Save mime type.
        $mime = self::getMimeTypes($name);
        $this->getFileInfo['mime']        = $mime['type'];
        
	$file_path  = $this->folderLocation . '/' . $name;
        
        if( move_uploaded_file($this->file['tmp_name'], $file_path) ) {
            return true;
        }
        else {
            $this->_setErrorMessage(1);
            return false;
        }
    }
    
    /**
     * Define file mime type. Original from Wordpress 3.0 get_allowed_mime_types() function in wp-includes/functions.php
     *
     * @param string
     * @return boolean|array
     */
    static function getMimeTypes($file_name = '')
    {    
        if( empty($file_name) )
            return false;
        
        $mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'tif|tiff' => 'image/tiff',
            'ico' => 'image/x-icon',
            'asf|asx|wax|wmv|wmx' => 'video/asf',
            'avi' => 'video/avi',
            'divx' => 'video/divx',
            'flv' => 'video/x-flv',
            'mov|qt' => 'video/quicktime',
            'mpeg|mpg|mpe' => 'video/mpeg',
            'txt|asc|c|cc|h' => 'text/plain',
            'csv' => 'text/csv',
            'tsv' => 'text/tab-separated-values',
            'rtx' => 'text/richtext',
            'css' => 'text/css',
            'htm|html' => 'text/html',
            'mp3|m4a|m4b' => 'audio/mpeg',
            'mp4|m4v' => 'video/mp4',
            'ra|ram' => 'audio/x-realaudio',
            'wav' => 'audio/wav',
            'ogg|oga' => 'audio/ogg',
            'ogv' => 'video/ogg',
            'mid|midi' => 'audio/midi',
            'wma' => 'audio/wma',
            'mka' => 'audio/x-matroska',
            'mkv' => 'video/x-matroska',
            'rtf' => 'application/rtf',
            'js' => 'application/javascript',
            'pdf' => 'application/pdf',
            'doc|docx' => 'application/msword',
            'pot|pps|ppt|pptx|ppam|pptm|sldm|ppsm|potm' => 'application/vnd.ms-powerpoint',
            'wri' => 'application/vnd.ms-write',
            'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel',
            'mdb' => 'application/vnd.ms-access',
            'mpp' => 'application/vnd.ms-project',
            'docm|dotm' => 'application/vnd.ms-word',
            'pptx|sldx|ppsx|potx' => 'application/vnd.openxmlformats-officedocument.presentationml',
            'xlsx|xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml',
            'docx|dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml',
            'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
            'swf' => 'application/x-shockwave-flash',
            'class' => 'application/java',
            'tar' => 'application/x-tar',
            'zip' => 'application/zip',
            'gz|gzip' => 'application/x-gzip',
            'exe' => 'application/x-msdownload',
            // openoffice formats
            'odt' => 'application/vnd.oasis.opendocument.text',
            'odp' => 'application/vnd.oasis.opendocument.presentation',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'odg' => 'application/vnd.oasis.opendocument.graphics',
            'odc' => 'application/vnd.oasis.opendocument.chart',
            'odb' => 'application/vnd.oasis.opendocument.database',
            'odf' => 'application/vnd.oasis.opendocument.formula',
            // wordperfect formats
            'wp|wpd' => 'application/wordperfect',
            // php formats
            'php|php4|php3|phtml' => 'application/x-httpd-php',
	    'phps' => 'application/x-httpd-php-source',
        );
        
        foreach ( $mimes as $ext_preg => $mime_match ) {
            $ext_preg = '!\.(' . $ext_preg . ')$!i';
            if ( preg_match( $ext_preg, $file_name, $ext_matches ) ) {
                $file['type'] = $mime_match;
                $file['ext'] = $ext_matches[1];
                break;
            }
	}
        
        return $file;
    }
    
    /**
     * Getter for getFileInfo property
     *
     * @return array
     */
    public function getFileInfo()
    {    
        return $this->getFileInfo;
    }
    
    /**
     * Getter for error property
     *
     * @return mix
     */
    public function getError($property = false)
    {    
        if( ! $property )
            return $this->error;
        
        return $this->error->$property;
    }
    
    /**
     * Setter for error message
     *
     * @param array $messages
     * @return object
     */
    public function setErrorMessage( $messages = array() )
    {    
        $this->errorMessages = array_replace($this->errorMessages, $messages);
        
        return $this;
    }
    
} //End Upload Class