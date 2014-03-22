<?php
/**
 * Hendle every runtime code execution errors.
 *
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @link	http://panadaframework.com/
 * @license	http://www.opensource.org/licenses/bsd-license.php
 * @since	version 1.0.0
 * @package	Resources
 */
namespace Resources;

class RunException extends \Exception
{    
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {    
        set_exception_handler( array($this, 'main') );
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString()
    {    
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
    
    public function main($exception)
    {    
        $message = $exception->getMessage();
        $file = false;
        $line = false;
        $traceAsString = $exception->getTraceAsString();
        
	foreach ($exception->getTrace() as $trace) {
	    if (isset($trace['file'])) {
		$file = $trace['file'];
		if (isset($trace['line'])) {
		    $line = $trace['line'];
		}
		break;
	    }
	}
	
        self::outputError($message, $file, $line, $traceAsString);
    }
    
    public static function errorHandlerCallback($errno, $message, $file, $line)
    {    
        if($errno == E_WARNING)
            return;
        
        self::outputError($message, $file, $line);
    }
    
    public static function outputError($message = null, $file = false, $line = false, $trace = false)
    {    
        // Message for log
        $errorMessage = 'Error '.$message.' in '.$file . ' line: ' . $line;
        
        // Write the error to log file
	@error_log($errorMessage);
        
        // Just output the error if the error source for view file or if in cli mode.
        if( (array_search( 'views', explode('/', $file) ) !== false) || (PHP_SAPI == 'cli') ){
            exit($errorMessage);
        }
        
        $code = array();
        
        if( ! $file )
            goto constructViewData;
        
        $fileString     = file_get_contents($file);
        $arrLine        = explode("\n", $fileString);
        $totalLine      = count($arrLine);
        $getLine        = array_combine(range(1, $totalLine), array_values($arrLine));
        $startIterate   = $line - 5;
        $endIterate     = $line + 5;
        
        if($startIterate < 0)
            $startIterate  = 0;
        
        if($endIterate > $totalLine)
            $endIterate = $totalLine;
        
        for($i = $startIterate; $i <= $endIterate; $i++){
            
            $html = '<span style="margin-right:10px;background:#CFCFCF;">'.$i.'</span>';
            
            if($line == $i )
                $html .= '<span style="color:#DD0000">'.$getLine[$i] . "</span>\n";
            else
                $html .= $getLine[$i] . "\n";
                
            $code[] = $html;
        }
        
        constructViewData:
        
        $data = array(
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'code' => $code,
            'trace' => $trace
        );
        
        header("HTTP/1.1 500 Internal Server Error", true, 500);
        
        \Resources\Controller::outputError('errors/500', $data);
        exit(1);
    }
    
    /**
     * EN: generates class/method backtrace
     * @param integer
     * @return array
     */
    public static function getErrorCaller($offset = 1)
    {    
	$caller = array();
        $bt = debug_backtrace(false);
	$bt = array_slice($bt, $offset);
        $bt = array_reverse( $bt );
	
        foreach ( (array) $bt as $call ) {
	    
	    if ( ! isset( $call['class'] ) )
		continue;
	    
            if ( @$call['class'] == __CLASS__ )
                continue;
	    
	    $function = $call['class'] . '->'.$call['function'];
        
	    if( isset($call['line']) )
		$function .= ' line '.$call['line'];
	    
            $caller[] = $function;
        }
	
        $caller = implode( ', ', $caller );
	
        return $caller;
    }
}