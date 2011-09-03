<?php
require_once(BIB_MAILER);
require_once(BIB_MAILER_SMTP);
/**
 * Classe de parametrização e configuração de emails
 *
 * @author André Coura <andre@mg.com.br>
 * @since 1.0 - 04/11/2007
 */

 class ControlMail extends PHPMailer{
 	/**
 	 * Construtor da classe e seus par�metros
 	 *
 	 * @author Andr� Coura
 	 * @since 1.0 - 04/11/2007
 	 * @param Strin $strEmailDest email de destino
 	 * @param Strin $strNomeDest Nome do individuo que recebera o email
 	 * @param Strin $strCCMail email CC
 	 * @param Strin $strCCNome nome do destinatario CC
 	 * @param Strin $strBCCMail Email BCC
 	 * @param Strin $strBCCNome nome do individuo BCC
 	 */
 	public function __construct(){
		//$this->IsSMTP();
		//$this->Host = MAIL_SMTP;
		//$this->SMTPAuth = MAIL_AUT_SMTP;
		//$this->Username = MAIL_USUARIO;
		//$this->Password = MAIL_SENHA;
		//$this->WordWrap = MAIL_WORDWRAP;
		//$this->IsHTML(MAIL_HTML);
		//$this->Port = MAIL_PORT;
		//$this->SMTP_PORT = MAIL_PORT;
		//$this->From = REMETENTE_MAIL;
		//$this->FromName = REMETENTE_NOME;
		//$this->AddReplyTo(REPLY_MAIL,REPLY_NOME);

		//$this->AddAddress($strEmailDest,$strNomeDest);
		//if($strCCMail)
			//$this->AddCC($strCCMail,$strCCNome);
		//if($strBCCMail)
			//$this->AddBCC($strBCCMail,$strBCCNome);
			
			//$this->SetLanguage("br");
		$this->IsSMTP();
		$this->Host  = SERV_SMTP;
		$this->SMTPAuth = true;
		$this->CharSet  = "utf-8";
		$this->IsHTML(true);
		$this->From = END_MAIL;
		$this->FromName = NOME_MAIL;
 	}
 	/**
 	 * Envia o email passando o conteudo nos parametros
 	 *
 	 * @author André Coura <andreccls@gmail.com>
 	 * @since 1.0 - 04/11/2007
 	 * @param String $strAssunto Assunto da mensagem
 	 * @param String $strBody Corpo da mensagem HTML
 	 * @param String $strAltBody PlainText, para caso quem receber o email n�o aceite o corpo HTML
 	 */
 	public function escreveEmail($strAssunto,$strBody,$strAltBody=null){
		$this->Subject = $strAssunto;
		$this->Body = $strBody;
		if($strAltBody)
			$this->AltBody = $strAltBody;
		if(!$this->Send())
			return false;
		return true;
 	}
 	
 	public function mailPara($strEmailDest,$strNomeDest){
 		$this->AddAddress($strEmailDest,$strNomeDest);
 	}

 	public function getErros(){
 		return $this->ErrorInfo;
 	}
 }
?>