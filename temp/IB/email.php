<?

@define("INCLUDE_PATH",__DIR__ . "/pear");
define("EMAIL_DIAKTIFKAN", true);
define("SMTP_HOST","192.168.1.20");
define("SMTP_AUTH",false);
define("SMTP_USERNAME","support@modena.co.id");
define("SMTP_PASSWORD","000000");
define("CRLF","\n");
define("EMAIL_TEMPLATE", "template/email.html");
define("SUPPORT_EMAIL", "support@modena.co.id");

function send_email($target=SUPPORT_EMAIL,$subject="",$content="", $lampiran=array()){
		$message=file_get_contents(EMAIL_TEMPLATE);		

		$subject = $subject;
		$to["To"]=$target;
		$to["Bcc"]=SUPPORT_EMAIL;
		$recipient_header_to=$target;
		$from=SUPPORT_EMAIL;

		ini_set("include_path",INCLUDE_PATH);
		require_once "pear/Mail.php";
		require_once "pear/mime.php";		
		
		$mime=new Mail_mime(array('eol' => CRLF));
		$mime->setTXTBody(strip_tags($message));
		$mime->setHTMLBody($message);	
		if(count($lampiran)>0)
			foreach($lampiran as $key)$mime->addAttachment($key);
		
		$headers = array ('From' => $from,
			'To' => $recipient_header_to, 
			'Subject' => $subject);		
		$smtp=Mail::factory('smtp',
			array ('host' => SMTP_HOST,
			'auth' => SMTP_AUTH,
			'username' => SMTP_USERNAME,
			'password' => SMTP_PASSWORD));		
						
		$body=$mime->get();
		$headers_=$mime->headers($headers);		

		if( EMAIL_DIAKTIFKAN )
			$mail = $smtp->send($to, $headers_, $body);
		
		$pear=new PEAR;
		if ($pear->isError($mail))echo "Email Error. " . $mail->getMessage();
		restore_include_path ();
	}

?>