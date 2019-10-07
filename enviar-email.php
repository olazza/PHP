<?php 

	/*
	 *	Envio de e-mail
	 */
	function sendMyMail($content, $dados, $debug = 0) {

		$resp      = array();
		$msgSucess = (empty($dados['msgSucess'])) ? 'Recebemos o seu contato e em breve lhe retornaremos.' : $dados['msgSucess'];

		if (!DB_LOCAL)
		{
			$mail = new PHPMailer();
			$mail->setLanguage('br');
			$mail->isSMTP();
			$mail->SMTPDebug   = $debug;
			$mail->Debugoutput = 'html';
			$mail->Host        = EMAIL_HOST;
			$mail->Port        = 465;
			$mail->SMTPSecure  = 'ssl';
			$mail->SMTPAuth    = true;
			$mail->Username    = EMAIL_USER;
			$mail->Password    = EMAIL_PASS;
			$mail->CharSet     = 'UTF-8';
			$mail->setFrom($dados['fromEmail'], $dados['fromName']);
			$mail->addAddress($dados['toEmail'], $dados['toName']);
			$mail->addReplyTo($dados['replyEmail'], $dados['replyName']);

			if (!empty($dados['hiddenCopy'])) 
			{
				$mail->addBCC($dados['hiddenCopy']); //Cópia oculta
			}

			$mail->Subject = $dados['subject'];
			$mail->msgHTML($content);
			$mail->AltBody = strip_tags($content);

			if (!$mail->send()) 
			{
				$resp['erro'] = 1;
			    $resp['mens'] = "Mailer Error: " . $mail->ErrorInfo;
			} 
			else 
			{
			    $resp['erro'] = 0;
				$resp['mens'] = $msgSucess;
			}
		}
		else
		{
			$resp['erro'] = 0;
			$resp['mens'] = $msgSucess;
		}

		return $resp;
	}
	
?>