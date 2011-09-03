<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class File extends AbsCompHtml {

  public function getComponente($value = ""){
		self::setValue($value);
		self::regTags();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."inputDre.tpl"));
		self::setCampos();
    }

}
?>