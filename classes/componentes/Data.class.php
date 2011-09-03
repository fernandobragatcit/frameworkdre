<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class Data extends AbsCompHtml {

    public function getComponente($value = ""){
		if($value != ""){
			self::registraData($value);
		}
		self::regTags();
//		self::addCssDataInput();
//		self::addJsDataInput();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."dateDre.tpl"));
		self::setCampos();
    }

    private function registraData($val){
    	$arrData = explode("-",$val);
    	$this->objSmarty->assign("VALUE_CALENDAR","TRUE");
    	$this->objSmarty->assign("DIA_COMP",self::validaData($arrData[2]));
    	$this->objSmarty->assign("MES_COMP",self::validaData($arrData[1]));
    	$this->objSmarty->assign("ANO_COMP",self::validaData($arrData[0]));
    	$this->objSmarty->assign("VALOR_DATA",$arrData[2]."/".$arrData[1]."/".$arrData[0]);
    }

    private function validaData($valor){
    	if(strlen($valor) == 2)
    		if($valor{0} == "0")
    			return $valor{1};
    	return $valor;
    }

    private function addCssDataInput(){
		$objCtrlCss = ControlCSS::getCSS();
//		$objCtrlCss->addCss(FWK_JS."vlaCalendar.v2.1/styles/vlaCal-v2.1.css");
//		$objCtrlCss->addCss(FWK_JS."vlaCalendar.v2.1/styles/vlaCal-v2.1-adobe_cs3.css");
//		$objCtrlCss->addCss(FWK_JS."vlaCalendar.v2.1/styles/vlaCal-v2.1-apple_widget.css");

		
    }

    private function addJsDataInput(){
		$objCtrlCss = ControlJS::getJS();
//		$objCtrlCss->addJs(FWK_JS."MooTools/mootools-1.2-core-nc.js");
//		$objCtrlCss->addJs(FWK_JS."vlaCalendar.v2.1/jslib/vlaCal-v2.1.js");
//		$objCtrlCss->addJs(FWK_JS."vlaCalendar.v2.1/implementacao.js");
		
    }


}
?>