<?php
require_once (FWK_CONTROL."ControlSmarty.class.php");
require_once (FWK_CONTROL."ControlJS.class.php");

class ControlMensagens {

	static private $objMens;
	private $objSmarty;

	private function __construct() {
        $this->objSmarty = ControlSmarty::getSmarty();
    }

    public static function getMens(){
        if (!isset(self::$objMens)) {
            $obj = __CLASS__;
            self::$objMens = new $obj;
        }
        //self::regCssMens();
        self::regJsMens();
        return self::$objMens;
    }

    public function exibeMens($strMens,$location){
		$this->objSmarty->assign("CONTROL_MENS_DRE",$strMens);
		$strMens = $this->objSmarty->fetch(FWK_HTML_MENS."mensDre.tpl");
		$this->objSmarty->assign($location,$strMens);
    }

    private function regCssMens(){
		$objCtrlCss = ControlCSS::getCSS();
		$objCtrlCss->addCSS(FWK_CSS."DreMens.css");
    }

    private function regJsMens(){
    	$objCtrlJs = ControlJS::getJS();
		$objCtrlJs->addJS(FWK_JS."mensDre.js");
    }


}
?>