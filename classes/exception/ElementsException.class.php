<?php
require_once(FWK_CONTROL."ControlSmarty.class.php");

class ElementsException  extends Exception {

	private $nameXml;

    public function __contruction($strMsg,$nameXml) {
    	parent::__construct($strMsg);
    	$this->nameXml = $nameXml;
    }

	public function __toString() {
		$objSmarty = self::regTags();
		return $objSmarty->fetch(FWK_HTML_EXCEPTION."estruturaException.tpl");
	}

	private function regTags(){
		$objSmarty = ControlSmarty::getSmarty();
		$objSmarty->assign("MENS_EXCECAO",$this->message);
		$objSmarty->assign("FILE_EXCECAO",$this->nameXml);
		$objSmarty->assign("NUM_EXCECAO",$this->line);
		return $objSmarty;
	}

	public function getMensagem(){
		return $this->message;
	}
}
?>