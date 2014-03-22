<?php
/**
 * Panada email API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>, Azhari Harahap <azhari@harahap.us>
 * @since	Version 0.1
 */
namespace Resources;

class Email
{    
    public
        /**
        * @var array   Define the reception array variable.
        */
        $rcptTo = array(),
        /**
        * @var array   Define the reception (cc) array variable.
        */
        $rcptCc = array(),
        /**
        * @var array   Define the reception (bcc) array variable.
        */
        $rcptBcc = array(),
        /**
        * @var string  Define email subject.
        */
        $subject = '',
        /**
        * @var string  Define email content.
        */
        $message = '',
        /**
        * @var string  Define email content type; plan or html.
        */
        $messageType = 'plain',
        /**
        * @var string  Define sender's email.
        */
        $fromEmail = '',
        /**
        * @var string  The sender name.
        */
        $fromName = '',
        /**
        * @var string  Mail application option. The option is: native (PHP mail function) or smtp.
        */
        $mailerType = 'native',
        /**
        * @var integer 1 = High, 3 = Normal, 5 = low.
        */
        $priority = 3,
        /**
        * @var string  SMTP server host.
        */
        $smtpHost = '',
        /**
        * @var integer SMTP server port.
        */
        $smtpPort = 25,
        /**
        * @var string | bool SMTP secure type.
        */
        $smtpSecure = false,
        /**
        * @var string  SMTP username.
        */
        $smtpUsername = '',
        /**
        * @var string  SMTP password.
        */
        $smtpPassword = '',
        /**
        * @var string  String to say "helo/ehlo" to smtp server.
        */
        $smtpEhloHost = 'localhost';
        
    
    private
        /**
        * @var string  Var for saving user email(s) that just converted from $rcptTo array.
        */
        $rcptToCtring = '',
        /**
        * @var string  Var for saving user email(s) that just converted from $rcptCc array.
        */
        $rcptCcString = '',
        /**
        * @var string  Var for saving user email(s) that just converted from $rcptBcc array.
        */
        $rcptBccString = '',
        /**
         * @var integer Define SMTP connection.
         */
        $smtpConnection = 0,
        /**
         * @var integer The SMTP connection timeout, in seconds.
         */
        $timeoutConnection = 30,
        /**
         * @var string Character set.
         */
        $charset = 'iso-8859-1',
        /**
         * @var string Character encoding.
         */
        $encoding = '8bit',
        /**
         * @var string  Enter character.
         */
        $breakLine = "\r\n",
        /**
         * @var array Group of debug messages.
         */
        $debugMessages = array(),
        /**
         * @var array  Attachment.
         */
        $attachment = array(),
        /**
         * @var string  Boundary
         */
        $boundary = 'Panada-Mail-',
        /**
         * @var string  Mailer useragent.
         */
        $panadaXMailer = 'Panada Mailer Version 0.4';
    
    
    public function __construct()
    {
        $this->boundary = $this->boundary.md5(time());
    }
    
    /**
     * Setter for option
     *
     * @param string | array $var
     * @param mix $value
     * @return object
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
     * FROM part
     * 
     * @param string | array
     * @return object
     */
    public function from($fromEmail = '', $fromName = '')
    {    
        $this->fromEmail    = $fromEmail;
        $this->fromName     = $fromName;
        
        return $this;
    }
    
    /**
     * TO part
     * 
     * @param string | array
     * @return object
     */
    public function to($rcptTo = '')
    {    
        $this->rcptTo = $this->strToArray($rcptTo);
        $this->rcptToCtring = implode(', ', $this->rcptTo);
        
        return $this;
    }
    
    /**
     * CC part
     * 
     * @param string | array
     * @return object
     */
    public function cc($rcptCc = '')
    {    
        if (!empty($rcptCc)){
            $this->rcptCc  = $this->strToArray($rcptCc);
            $this->rcptCcString   = implode(', ', $this->rcptCc);
        }
        
        return $this;
    }
    
    /**
     * BCC part
     * 
     * @param string | array
     * @return object
     */
    public function bcc($rcptBcc = '')
    {    
        if (!empty($rcptBcc)){
            $this->rcptBcc = $this->strToArray($rcptBcc);
            $this->rcptBccString = implode(', ', $this->rcptBcc);
        }
        
        return $this;
    }
    
    /**
     * SUBJECT part
     * 
     * @param string | array
     * @return object
     */
    public function subject($subject = '')
    {    
        $this->subject = $subject;
        
        return $this;
    }

    /**
     * MESSAGE part
     * 
     * @param string | array
     * @return object
     */
    public function message($message = '')
    {    
        $this->message = $message;
        
        return $this;
    }
    
    /**
     * ATTACH part
     * 
     * @param string | array
     * @return object
     */
    public function attach($filename = '')
    {    
        $this->attachment[] = $filename;
        $this->messageType = 'attach';
        return $this;
    }

    /**
     * Main Panada method to send the email.
     *
     * @param string | array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string | array
     * @param string | array
     * @return boolean
     */
    public function mail($rcptTo = '', $subject = '', $message = '', $fromEmail = '', $fromName = '', $rcptCc = '', $rcptBcc = '')
    {    
        if( ! empty($rcptTo) ){
            $this->rcptTo = $this->strToArray($rcptTo);
            $this->rcptToCtring = implode(', ', $this->rcptTo);
        }
        
        if( ! empty($subject) )
            $this->subject = $subject;
        
        if( ! empty($message) )
            $this->message = $message;
        
        if( ! empty($fromEmail) )
            $this->fromEmail = $fromEmail;
        
        if( ! empty($fromName) )
            $this->fromName = $fromName;
        
        if (! empty($rcptCc) ){
            $this->rcptCc  = $this->strToArray($rcptCc);
            $this->rcptCcString = implode(', ', $this->rcptCc);
        }

        if ( ! empty($rcptBcc) ){
            $this->rcptBcc = $this->strToArray($rcptBcc);
            $this->rcptBccString = implode(', ', $this->rcptBcc);
        }
        
        if($this->smtpHost != '' || $this->mailerType == 'smtp') {
            
            $this->mailerType = 'smtp';
            return $this->smtpSend();
        }
        else {
            return $this->mailerNative();
        }
    }
    
    /**
     * Print the debug messages.
     *
     * @return string
     */
    public function printDebug($isEcho = true)
    {    
        
        if( ! $isEcho )
            return $this->debugMessages;
        
        echo implode('<br>', $this->debugMessages);
    }
    
    /**
     *  Make the email address string lower and unspace.
     *
     * @param string
     * @return array
     */
    private function cleanEmail($email)
    {    
        foreach($email as $email)
            $return[] = trim(strtolower($email));
        
        return $return;
    }
    
    /**
     *  Convert the email address to array.
     *
     * @param string | array
     * @return array
     */
    private function strToArray($email)
    {
        if( is_array($email) ) {
            return $this->cleanEmail($email);
        }
        else {
            
            $rcpt_break = explode(',', $email);
            
            if( count($rcpt_break) > 0 )
                return $this->cleanEmail($rcpt_break);
            else
                return $this->cleanEmail(array($email));
        }
    }
    
    /**
     * Built in mail function from PHP. This is the default function to send the email.
     *
     * @return booelan
     */
    private function mailerNative()
    {    
        if( ! mail($this->rcptToCtring, $this->subject, $this->message, $this->header()) ){
            $this->debugMessages[] = 'Error: Sending email failed';
            return false;
        }
        else {
            $this->debugMessages[] = 'Success: Sending email succeeded';
            return true;
        }
    }
    
    /**
     * Socket write command function.
     *
     * @param string
     * @return void
     */
    private function writeCommand($command)
    {    
        fwrite($this->smtpConnection, $command);
    }
    
    /**
     * Get string from smtp respnse.
     *
     * @return string
     */
    private function getSmtpResponse()
    {
        $return = '';
        
        while($str = fgets($this->smtpConnection, 515)) {
            
            $this->debugMessages[] = 'Success: ' . $str;
            
            $return .= $str;
            
            //Stop the loop if we found space in 4th character.
            if(substr($str,3,1) == ' ')
                break;
        }
        
        return $return;
    }
    
    /**
     * Open connection to smtp server.
     *
     * @return boolean
     */
    private function smtpConnect()
    {   
        //Connect to smtp server
        $this->smtpConnection = fsockopen(
                                    ($this->smtpSecure && $this->smtpSecure == 'ssl' ? 'ssl://' : '').$this->smtpHost,
                                    $this->smtpPort,
                                    $errno,
                                    $errstr,
                                    $this->timeoutConnection
                                );
       
        if( empty($this->smtpConnection) ) {
            
            $this->debugMessages[] = 'Error: Failed to connect to server! Error number: ' .$errno . ' (' . $errstr . ')';
            
            return false;
        }
        
        //Add extra time to get respnose from server.
        socket_set_timeout($this->smtpConnection, $this->timeoutConnection, 0);
        
        $response = $this->getSmtpResponse();
        $this->debugMessages[] = 'Success: ' . $response;
        
        return true;
    }
    
    /**
     * Do login to smtp server.
     *
     * @return boolean
     */
    private function smtpLogin()
    {    
        //SMTP authentication command
        $this->writeCommand('AUTH LOGIN' . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        if($code != 334) {
            
            $this->debugMessages[] = 'Error: AUTH not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        // Send encoded username
        $this->writeCommand( base64_encode($this->smtpUsername) . $this->breakLine );
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        if($code != 334){
            
            $this->debugMessages[] = 'Error: Username not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        // Send encoded password
        $this->writeCommand( base64_encode($this->smtpPassword) . $this->breakLine );
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        if($code != 235) {
            
            $this->debugMessages[] = 'Error: Password not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
           
            return false;
        }
        
        return true;
    }
    
    /**
     * Close smtp connection.
     *
     * @return void
     */
    private function smtpClose()
    {    
        if( ! empty($this->smtpConnection) ) {
            fclose($this->smtpConnection);
            $this->smtpConnection = 0;
        }
    }
    
    /**
     * Initate the smtp ehlo function.
     *
     * @return boolean
     */
    private function makeEhlo()
    {    
        /**
         * IF smtp not accpeted EHLO then try HELO.
         */
        if( ! $this->smtpEhlo('EHLO') )
            if( ! $this->smtpEhlo('HELO') )
                return false;
        
        return true;
    }
    
    /**
     * Say ehlo to smtp server.
     *
     * @param string
     * @return boolean
     */
    private function smtpEhlo($hello)
    {    
        $this->writeCommand( $hello . ' ' . $this->smtpEhloHost . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        $this->debugMessages[] = 'Success: helo reply from server is: ' . $response;
        
        if($code != 250){
            
            $this->debugMessages[] = 'Error: '.$hello.' not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        return true;
    }
    
    /**
     * This is email from method.
     *
     * @return boolean
     */
    private function smtpFrom()
    {    
        $this->writeCommand("MAIL FROM:<" . $this->fromEmail . ">" . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        $this->debugMessages[] = 'Success: ' . $response;
        
        if($code != 250) {
            
            $this->debugMessages[] = 'Error: MAIL not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        return true;
    }
    
    /**
     * Email to method.
     *
     * @param string
     * @return boolean
     */
    private function smtpRecipient($to)
    {    
        $this->writeCommand("RCPT TO:<" . $to . ">" . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        $this->debugMessages[] = 'Success: ' . $response;
        
        if($code != 250 && $code != 251) {
            
            $this->debugMessages[] = 'Error: RCPT not accepted from server! Error number: ' .$code . ' (' . substr($response,4) . ')';
            
            return false;
        }
        
        return true;
    }
    
    /**
     * Create the email header.
     *
     * @return string
     */
    private function header()
    {    
        $fromName  = ($this->fromName != '') ? $this->fromName : $this->fromEmail;
        $headers['from']        = 'From: ' . $fromName . ' <' . $this->fromEmail . '>' . $this->breakLine;
		if (count($this->rcptCc) > 0)
			$headers['cc']	= 'Cc: ' . $this->rcptCcString . $this->breakLine;
		if (count($this->rcptBcc) > 0 && $this->mailerType == 'native')
			$headers['bcc']	= 'Bcc: ' . $this->rcptBccString . $this->breakLine;
        $headers['priority']    = 'X-Priority: '. $this->priority . $this->breakLine;
        $headers['mailer']      = 'X-Mailer: ' .$this->panadaXMailer . $this->breakLine;
        $headers['mime']        = 'MIME-Version: 1.0' . $this->breakLine;
		
		switch($this->messageType)
		{
			case 'plain':
			case 'html':
				$headers['cont_type']   = 'Content-type: text/'.$this->messageType.'; charset='.$this->charset.$this->breakLine;
			break;
			
			case 'attach':
				$headers['cont_type']   = 'Content-type: multipart/mixed; boundary='.$this->boundary . $this->breakLine;
			break;
		}
		
        if($this->mailerType == 'native') {
            $return = '';
            foreach($headers as $headers)
                $return .= $headers;
            
            return $return;
        }
        else {
            
            // Additional headers needed by smtp.
            $this->writeCommand('To: ' . $this->rcptToCtring . $this->breakLine);
            $this->writeCommand('Subject:' . $this->subject. $this->breakLine);

            foreach($headers as $key => $val) {

                if($key == 'cont_type')
                    $val = str_replace($this->breakLine, "\n\n", $val);
                
                $this->writeCommand($val);
            }
        }
    }
    
    /**
     * Send the mail data.
     *
     * @return boolean
     */
    private function smtpData()
    {    
        $this->writeCommand('DATA' . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        $this->debugMessages[] = 'Success: ' . $response;
        
        if($code != 354) {
            
            $this->debugMessages[] = 'Error: DATA command not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        $this->header();
        
        // Attachment?
		if (count($this->attachment) > 0)
		{
			$body = '--'.$this->boundary.$this->breakLine
				.'Content-type: text/plain; charset='.$this->charset.$this->breakLine
				.'Content-Transfer-Encoding: '.$this->encoding.$this->breakLine.$this->breakLine
				.$this->message.$this->breakLine.$this->breakLine;
			$this->writeCommand($body . $this->breakLine);
            if(!$this->smtpAttach()) return false;
		}
		else
		{
			$this->writeCommand($this->message . $this->breakLine);
		}
		
        //All messages have sent
        $this->writeCommand( $this->breakLine . '.' . $this->breakLine);
        
        $response = $this->getSmtpResponse();
        $code = substr($response, 0, 3);
        
        $this->debugMessages[] = 'Success: ' . $response;
        
        if($code != 250){
            
            $this->debugMessages[] = 'Error: DATA command not accepted from server! Error number: ' .$code . ' (' . substr($response, 4) . ')';
            
            return false;
        }
        
        return true;
    }
   
    /**
     * execute the smtp connection.
     *
     * @return boolean
     */
    private function doConnect()
    {    
        $connection = false;
        
        if( $this->smtpConnect() ) {
            
            $this->makeEhlo();
            
            if( ! empty($this->smtpUsername) ){
                if( ! $this->smtpLogin() )
                    $connection = false;
            }
            
            $connection = true;
        }
           
        if( ! $connection )
            return false;
        
        return $connection;
    }
    
    /**
     * Sending the data to smtp
     *
     * @return boolean
     */
    private function smtpSend()
    {   
        if(!$this->doConnect())
            return false;
        
        if( ! $this->smtpFrom())
            return false;
        
        foreach($this->rcptTo as $recipient)
            $this->smtpRecipient($recipient);

		if (count($this->rcptCc) > 0)
		{
        	foreach($this->rcptCc as $recipient)
            	$this->smtpRecipient($recipient);
		}
		
		if (count($this->rcptBcc) > 0)
		{
	        foreach($this->rcptBcc as $recipient)
	            $this->smtpRecipient($recipient);
		}

        if( ! $this->smtpData() )
            return false;
        
        $this->smtpClose();
        
        return true;
    }
    
    /**
     * Sending attachment to smtp
     *
     * @return boolean
     */
    private function smtpAttach()
    {
		$attachment = array();
		$attachmentCount = count($this->attachment);
		for ($i = 0; $i < $attachmentCount; $i++)
		{
			$filename = $this->attachment[$i];

			// Not exist?
			if (!file_exists($filename))
			{
				$this->debugMessages[] = 'Error: Attachment '.$filename.' not found' ;
				return false;
			}
			
			$fileContent = '';
			$fp = fopen($filename, "r");
			$fileContent = fread($fp, filesize($filename));
			fclose($fp);
			
			$attachment[$i] = '--'.$this->boundary.$this->breakLine
				.'Content-type: '.mime_content_type($filename).'; name='.basename($filename).$this->breakLine
				.'Content-Disposition: attachment; filename='.basename($filename).$this->breakLine
				.'Content-Transfer-Encoding: base64'.$this->breakLine.$this->breakLine;
			$attachment[$i] .= chunk_split(base64_encode($fileContent));
		}
		
		$attachmentBody = implode($this->breakLine, $attachment).$this->breakLine.'--'.$this->boundary.'--';
		$this->writeCommand($attachmentBody . $this->breakLine);
		return true;
    }
    
}