<?php
require_once (FWK_COMP."AbsCompHtml.class.php");
require_once (BIB_CAPTCHA);

class Captcha extends AbsCompHtml {

	public function getComponente($value = ""){
		self::setValue($value);
		self::regTags();
		self::captchaShow();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."captchaDre.tpl"));
		self::setCampos();
    }

    private function captchaShow(){
    	if(isset(self::getObjXmlCompDados()->styleCaptcha) && self::getObjXmlCompDados()->styleCaptcha != "")
    		parent::getObjSmarty()->assign("STYLE_IMG_CAPTCHA","style=\"".self::getObjXmlCompDados()->styleCaptcha."\"");
    	if(isset(parent::getObjXmlCompDados()->textoLinkCaptcha) && parent::getObjXmlCompDados()->textoLinkCaptcha !="")
    		parent::getObjSmarty()->assign("TEXTO_LINK_CAPTCHA",parent::getObjXmlCompDados()->textoLinkCaptcha);
    	else
    		parent::getObjSmarty()->assign("TEXTO_LINK_CAPTCHA","Recarregar Imagem");
    	parent::getObjSmarty()->assign("CAPTCHA_SHOW",CAPTCHA_SHOW);
    }
}
?>