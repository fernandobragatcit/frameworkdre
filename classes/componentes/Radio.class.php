<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class Radio extends AbsCompHtml {

    function getComponente($value = "") {
		parent::regTags();
		self::registraRadios($value);
		parent::setCampos();
    }
    private function registraRadios($value){
		if(isset($this->objXmlCompChilds->radios)){
			$cont=0;
			$arrDadosRadio = array();
			foreach ($this->objXmlCompChilds->radios->radio as $radio) {
				$arrDadosRadio[$cont][0] = (string)$this->objXmlComp->name;
				$arrDadosRadio[$cont][1] = (string)$radio->attributes()->value;
				$arrDadosRadio[$cont][2] = (string)$radio->attributes()->id;
				$arrDadosRadio[$cont][3] = (string)$radio->attributes()->style;
				$arrDadosRadio[$cont][4] = (string)$radio->attributes()->class;
				$arrDadosRadio[$cont][5] = (string)$radio->attributes()->label;
				$arrDadosRadio[$cont][6] = (string)$radio->attributes()->classLabel;
				$arrDadosRadio[$cont][7] = (string)$radio->attributes()->styleLabel;

				if((string)$value == (string)$radio->attributes()->value){
					$arrDadosRadio[$cont][8] = "checked=\"checked\"";
//					print("<PRE>");
//					print_r($arrDadosRadio);
				}
				$arrDadosRadio[$cont][9] = (string)$radio->attributes()->onclick;

				$cont++;
			}
//			print("<PRE>");
//			print_r($arrDadosRadio);

			$this->objSmarty->assign("ARR_OPTIONS",$arrDadosRadio);

			parent::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."radioGoupDRE.tpl"));
		}else{
			parent::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."radioDre.tpl"));
		}
    }

}
?>