<?php

class CrudException extends Exception {

    public function __contruction($strMsg) {
    	parent::__construct($strMsg);
    }

	public function __toString() {
		$objSmarty = self::regTags();
		return $objSmarty->fetch(FWK_HTML_EXCEPTION."estruturaException.tpl");
	}

	public function getMensagem(){
		return $this->message;
	}

	private function regTags(){
		$objSmarty = ControlSmarty::getSmarty();
		$objSmarty->assign("MENS_EXCECAO",$this->message);
		$objSmarty->assign("FILE_EXCECAO",$this->file);
		$objSmarty->assign("NUM_EXCECAO",$this->line);
		return $objSmarty;
	}
}
?>