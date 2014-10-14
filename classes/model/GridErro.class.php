<?php
require_once(BIB_MAILER);

class GridErro{

	private $strFrom;
	private $strFromName;
	private $strSubject;
	private $objMail;
	private $strMensErro;

	public function __construct($mens) {
		$this->objMail = new PHPMailer();
	}

	public function setMensErro($strMensErro){
		$this->strMensErro = $strMensErro;
	}

	public function getMensErro(){
		return $this->strMensErro;
	}

	public function getFromMail(){
		return $this->strFrom;
	}

	public function getFromName(){
		return $this->strFromName;
	}

	public function getSubjectMail(){
		return $this->strSubject;
	}

	public function setFromMail($strFrom){
		$this->strFrom = $strFrom;
	}

	public function setFromName($strFromName){
		$this->strFromName = $strFromName;
	}

	public function setSubjectMail($strSubject){
		$this->strSubject = $strSubject;
	}

	public function sendMail(){
		$this->objMail->SetLanguage("br");
		$this->objMail->IsMail();
		$this->objMail->IsHTML(true);
		$this->objMail->From = self::getFromMail();
		$this->objMail->FromName =  self::getFromName();
		$this->objMail->AddAddress(EMAIL_BUG);
		$this->objMail->Subject = self::getSubjectMail();
		$this->objMail->Body = self::getMensErro();
		$this->objMail->Send();
	}


}
?>