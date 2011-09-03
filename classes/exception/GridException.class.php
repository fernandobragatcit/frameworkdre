<?php

class GridException extends Exception {

    public function __contruction($strMsg) {
    	parent::__construct($strMsg);
    }

    /**
     * Override
     *
     * @return unknown
     */
	public function __toString() {
		$objSmarty = ControlSmarty::getSmarty();
		$objSmarty->assign("MENS_EXCECAO",$this->message);
		$objSmarty->assign("FILE_EXCECAO","Grid Exceptions");

		return $objSmarty->fetch(FWK_HTML_EXCEPTION."estruturaException.tpl");
	}

	public function getMensagem(){
		return $this->message;
	}


}
?>