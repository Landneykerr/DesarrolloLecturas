<?php
	require_once('PHPMailer_v5.1/class.phpmailer.php');
	require_once('PHPMailer_v5.1/class.smtp.php');
	//function MailTest(){

		

		$mail             = new PHPMailer();
		$body             = "Hola mundo gmail.";
		$body             = eregi_replace("[\]",'',$body);
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "Sypelc.Villavicencio.com"; // SMTP server
		$mail->SMTPDebug  = 2;                     		// enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->SMTPSecure = "tls";                 		// sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      		// sets GMAIL as the SMTP server
		$mail->Port       = 587;                   		// set the SMTP port for the GMAIL server
		$mail->Username   = "CTI.sypelc@gmail.com";  	// GMAIL username
		$mail->Password   = "Sypelcs0p0rt3";            // GMAIL password
		$mail->SetFrom('CTI-Sypelc', 'First Last');
		//$mail->AddReplyTo("name@yourdomain.com","First Last");
		$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
		$address = "julianpovedadaza@gmail.com";
		$mail->AddAddress($address, "Julian Poveda");
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} 
		else {
			echo "Message sent!";
		}
	//}

?>