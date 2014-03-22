<?php
/**
 * Panada Image Modifier.
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

class Image
{    
    /**
     * @var string  Option for editng image the option is: resize | crop | resize_crop.
     */
    public $editType = PIMG_RESIZE;
    
    /**
     *@var boolean  Adjust width/heigh autmaticly for resizing proccess.
     */
    public $autoRatio = true;
    
    /**
     * @var integer New image width.
     */
    public $resizeWidth = 0;
    
    /**
     * @var integer New image height.
     */
    public $resizeHeight = 0;
    
    /**
     * @var integer Crope width size.
     */
    public $cropWidth = 0;
    
    /**
     * @var integer Crope height size.
     */
    public $cropHeight = 0;
    
    /**
     * @var string  Source file name.
     */
    public $fileName = '';
    
    /**
     * @var string  Source full path location.
     */
    public $filePath;
    
    /**
     * @var string  Source file info.
     */
    public $fileInfo = array();
    
    /**
     * @var string  Define new file name.
     */
    public $newFileName = '';
    
    /**
     * @var boolean Remove any space in file name.
     */
    public $stripSpaces = true;
    
    /**
     * @var string  Source folder location.
     */
    public $folder = '';
    
    /**
     * @var string  Source image type: gif, jpg or png.
     */
    public $imageType;
    
    /**
     * @var integer The value jpg compression. the range is 0 - 100.
     */
    public $jpegCompression = 90;
    
    /**
     * @var string  Folder to placed new edited image.
     */
    public $saveTo = '';
    
    /**
     * @var array   Initiate the error mesages.
     */
    public $errorMessages = array();
    
    
    /**
     * Class constructor
     */
    public function __construct()
    {    
        if( ! function_exists('imagecopyresampled') )
            throw new RunException('Image resizing function that required by Image Class is not available.');
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
    
    private function preErrorChecker()
    {    
        /**
         * Check is folder destionation has set.
         */
        if( empty($this->folder) ) {
            $this->errorMessages[] = 'No folder located. Please define the folder location.';
            return false;
        }
        
        /**
         * Does it folder exist?
         */
        if( ! is_dir($this->folder) ) {
            $this->errorMessages[] = 'The folder '.$this->folder.' doesn\'t exists please create it first.';
            return false;
        }
        
        /**
         * Does it folder writable?
         */
        if( ! is_writable($this->folder) ) {
            $this->errorMessages[] = 'The folder '.$this->folder.' is not writable.';
            return false;
        }
        
        /**
         * Does the file exist?
         */
        if( ! file_exists($this->filePath) ) {
            $this->errorMessages[] = 'File '.$this->filePath.' doesn\'t exists.';
            return false;
        }
        
        return true;
    }
    
    private function errorHandler()
    {    
        if( ! $this->fileInfo || ! $this->fileInfo[0] || ! $this->fileInfo[1] ) {
            $this->errorMessages[] = 'Unable to get image dimensions.';
            return false;
        }
        
        if ( ! function_exists( 'imagegif' ) && $this->imageType == IMAGETYPE_GIF || ! function_exists( 'imagejpeg' ) && $this->imageType == IMAGETYPE_JPEG || ! function_exists( 'imagepng' ) && $this->imageType == IMAGETYPE_PNG ) {
	    $this->errorMessages[] = 'Filetype not supported.';
            return false;
	}
        
        return true;
    }
    
    public function initFileInfo()
    {    
        $this->fileInfo = @getimagesize($this->filePath);
        $this->imageType   = $this->fileInfo[2];
    }
    
    public function edit($fileName)
    {    
	$this->fileName    = $fileName;
        $this->filePath    = $this->folder . '/' . $fileName;
        
        // Pre condition cheking.
        if( ! $this->preErrorChecker() )
            return false;
        
        @chmod($this->filePath, 0666);
	
        $this->initFileInfo();
        
        if ( ! $this->errorHandler() )
            return false;
	
        $image = $this->createImageFrom();
        
        if( ! empty($this->errorMessages) )
            return false;
        
        if ( function_exists('imageantialias') )
            imageantialias( $image, true );
        
        // Initial heigh and widht variable.
        $image_width        = $this->fileInfo[0];
        $image_height       = $this->fileInfo[1];
        
        $image_new_width    = $this->fileInfo[0];
        $image_new_height   = $this->fileInfo[1];
        
        if( $this->resizeWidth > 0 && $this->resizeHeight == 0 ) {
            
            $image_new_width    = $this->resizeWidth;
            $image_ratio        = $image_width / $image_new_width;
            
            if($this->autoRatio)
                $image_new_height = $image_height / $image_ratio;
        }
        
        if( $this->resizeHeight > 0 && $this->resizeWidth == 0 ) {
            
            $image_new_height   = $this->resizeHeight;
            $image_ratio        = $image_height / $image_new_height;
            
            if($this->autoRatio)
                $image_new_width = $image_width / $image_ratio;
        }
        
        if( $this->resizeHeight > 0 && $this->resizeWidth > 0 && $this->editType == 'resize' ) {
            
            $image_new_height   = $this->resizeHeight;
            $image_new_width  	= $this->resizeWidth;
        }
        
        //Resizing
        if($this->editType == 'resize' || $this->editType == 'resize_crop') {
            
            $imageEdited = imagecreatetruecolor( $image_new_width, $image_new_height);
            @imagecopyresampled( $imageEdited, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $this->fileInfo[0], $this->fileInfo[1] );
        }
        
        //Cropping process
        if($this->editType == 'crop' || $this->editType == 'resize_crop') {
            
            $imageEdited = ( isset($imageEdited) ) ? $imageEdited : $image;
            $cropped = imagecreatetruecolor($this->cropWidth, $this->cropHeight);
            imagecopyresampled($cropped, $imageEdited, 0, 0, ( ($image_new_width/2) - ($this->cropWidth/2) ), ( ($image_new_height/2) - ($this->cropHeight/2) ), $this->cropWidth, $this->cropHeight, $this->cropWidth, $this->cropHeight);
            $imageEdited = $cropped;
        }
        
        $this->createImage($imageEdited);
	
	if( ! empty($this->errorMessages) )
            return false;
        
	return true;
    }
    
    public function createImageFrom()
    {    
        // create the initial copy from the original file
        switch($this->imageType) {
            
            case IMAGETYPE_GIF:
                return imagecreatefromgif( $this->filePath );
                exit;
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg( $this->filePath );
                exit;
            case IMAGETYPE_PNG:
                return imagecreatefrompng( $this->filePath );
                exit;
            default:
                $this->errorMessages[] = 'Unrecognized image format.';
                return false;
        }
    }
    
    public function createImage($imageEdited)
    {    
        $file_extension = Upload::getFileExtension($this->fileName);
        $saveTo        = ( ! empty($this->saveTo) ) ? $this->saveTo : $this->folder;
        $new_filename   = ( ! empty($this->newFileName) )? $saveTo . '/' . $this->newFileName . '.' . $file_extension : $saveTo.'/'.$this->fileName;
        $new_filename   = ($this->stripSpaces) ? str_replace(' ', '_', $new_filename) : $new_filename;
        
        // move the new file
        if ( $this->imageType == IMAGETYPE_GIF ) {
            if ( ! imagegif( $imageEdited, $new_filename ) )
                $this->errorMessages[] = 'File path invalid.';
        }
        elseif ( $this->imageType == IMAGETYPE_JPEG ) {
            if (! imagejpeg( $imageEdited, $new_filename, $this->jpegCompression ) )
                $this->errorMessages[] = 'File path invalid.';
        }
        elseif ( $this->imageType == IMAGETYPE_PNG ) {
            if (! imagepng( $imageEdited, $new_filename ) )
                $this->errorMessages[] = 'File path invalid.';
        }
    }
    
    public function getErrorMessage()
    {    
        return $this->errorMessages;
    }
    
} // End Image Modifier Class
