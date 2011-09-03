<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class Textarea extends AbsCompHtml {

    public function getComponente($value = ""){
		self::regTags();
		self::setValorTxtArea($value);
    	self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."textAreaDre.tpl"));
    	self::setCampos();
    }

    private function setValorTxtArea($value){
		if($value != "")
    		$this->objSmarty->assign("TXT_VALUE",utf8_encode($value));
    	else if($value == "")
    		$this->objSmarty->assign("TXT_VALUE","");
    }
}
?>