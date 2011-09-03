<?php

class HtmlCompsException {

    public function __construct($message, $code = 0) {
		parent :: __construct($message, $code);
	}

	public function __toString() {
		$objSmarty = ControlSmarty::getSmarty();
		$objSmarty->assign("MENS_EXCECAO",$this->message);
		$objSmarty->assign("FILE_EXCECAO","Grid Exceptions");

		return $objSmarty->fetch(FWK_HTML_EXCEPTION."estruturaException.tpl");
	}
}
?>