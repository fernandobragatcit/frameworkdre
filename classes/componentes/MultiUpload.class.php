<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class MultiUpload extends AbsCompHtml {

	public function getComponente($value = ""){
		self::regJsCompMUpload();
		self::setValue($value);
		self::regTags();
		self::regTagsMultiUpload();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."multiupload_dre.tpl"));
		self::setCampos();
    }


    private function regTagsMultiUpload(){
    	$this->objSmarty->assign("NUM_UPLOADS",NUM_UPLOADS);
    }

    private function regJsCompMUpload(){
    	ControlJS::getJS()->addJs(FWK_JS."componentes/actionsMUpload.js");
    }

}
?>