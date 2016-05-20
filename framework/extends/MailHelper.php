<?php
/**
*  MailHelper code.
*
*  PHP version 5.3
*
*  @category MailHelper
*  @package mail
*  @author Rex Chen <rexchen@synctech-infinity.com>
*  @copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'extends/PHPMailer/PHPMailerAutoload.php' );
require_once( FRAMEWORK_PATH . 'extends/LoggerHelper.php' );

class MailHelper {
	
	private $log 		= NULL;

	private $logPath 	= TRADE_LOG_PATH;
	private $prefix 	= 'mail';

	private $host 		= 'mail.109life.com';
	private $port 		= 25;
	private $username 	= 'service@109life.com';
	private $password 	= 'eqz88888';
	private $mailFrom 	= 'service@109life.com';
    private $mailFromName = '團購系統';
    private $checkSalt    = 'OsneLSnc65';

    private $apiPath      = MAIL_API_PATH;

	public function __construct() {
		mb_internal_encoding('UTF-8');
	}

	private function error($text) {
		if(is_null($this->log)) {
			$this->log = new LoggerHelper($this->prefix, $this->logPath);
		}

		$this->log->error($text);
	}

	private function info($text) {
		if(is_null($this->log)) {
			$this->log = new LoggerHelper($this->prefix, $this->logPath);
		}

		$this->log->info($text);
	}

    /**
     * Validate checksum for REST API reqeust.
     * @param $checkSum
     * @return bool
     */
    public function isValidCheckSum($checkSum, $subject, $mailTo, $mailToName, $text='') {
        return ($this->generateCheckSum($subject, $mailTo, $mailToName, $text) == $checkSum);
    }

    /**
     * Generate checksum.
     * @param $subject
     * @param $mailTo
     * @param $mailToName
     * @param string $text
     * @return string
     */
    public function generateCheckSum($subject, $mailTo, $mailToName, $text='') {
        return md5($this->checkSalt . $subject . $mailTo . $mailToName . $text);
    }

    /**
     * Send mail by REST API.
     * @param $subject
     * @param $mailTo
     * @param $mailToName
     * @param string $text
     */
    public function sendByRestApi($subject, $mailTo, $mailToName, $text='') {
        $postString = json_encode(array(
            "subject" => $subject,
            "mailTo" => $mailTo,
            "mailToName" => $mailToName,
            "text" => $text,
            "checksum" => $this->generateCheckSum($subject, $mailTo, $mailToName, $text)
        ));

        $parts  = parse_url($this->apiPath);
        $port   = isset($parts['port']) ? $parts['port'] : 80;
        $fp     = fsockopen($parts['host'], $port, $errno, $errstr, 30);
        $output = '';

        $output .= "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $output .= "Host: " . $parts['host'] . "\r\n";
        $output .= "Content-Type: application/json\r\n";
        $output .= "Content-Length: " . strlen($postString) . "\r\n";
        $output .= "Connection: Close\r\n\r\n";

        if (isset($postString)) {
            $output.= $postString;
        }

        fwrite($fp, $output);
        fclose($fp);
    }

	/**
	*	Send mail by text data.
	*
	*/
	public function sendText($subject, $mailTo, $mailToName, $text='') {
		try {
			$mail 			= new PHPMailer;
			$mailFromName 	= $this->mailFromName;
			$subject 		    = mb_encode_mimeheader($subject, "UTF-8");
			$mailToName 	= mb_encode_mimeheader($mailToName, "UTF-8");
			$mailFromName 	= mb_encode_mimeheader($mailFromName, "UTF-8");

			$mail->isSMTP();
			
			$mail->SMTPDebug = 0;
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			
			//Ask for HTML-friendly debug output
			// $mail->Debugoutput = 'html';
			$mail->CharSet = "UTF-8";

			//Set the hostname of the mail server
			$mail->Host = $this->host;
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = $this->port;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication
			$mail->Username = $this->username;
			//Password to use for SMTP authentication
			$mail->Password = $this->password;
			//Set who the message is to be sent from
			$mail->setFrom($this->mailFrom, $mailFromName);
			//Set an alternative reply-to address
			// $mail->addReplyTo($mailTo, 'ReplayTo Name');
			//Set who the message is to be sent to
			$mail->addAddress($mailTo, $mailToName);
			//Set the subject line
			$mail->Subject = $subject;
			
			// $text = mb_encode_mimeheader($text, "UTF-8");
			$mail->msgHTML($text);
			
			//Replace the plain text body with one created manually
			// $mail->AltBody = 'This is a plain-text message body';

			// $mail->Body = $text;

			//Attach an image file
			// $mail->addAttachment('images/phpmailer_mini.png');

			//send the message, check for errors
			if (!$mail->send()) {			  
			    $errorMessage = $mail->ErrorInfo;
				$this->error("Send to $mailTo error : $errorMessage");
			} else {
			    $this->info("Send to $mailTo success. $text");
			}
		}
		catch(Exception $e) {
			$errorMessage = $e->getMessage();
			$this->error("Send to $mailTo exception : $errorMessage");
		}
		
	}
	
}


?>