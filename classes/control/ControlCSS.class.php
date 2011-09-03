<?php
class ControlCSS{

	private $strCss = "";
	private $strDinCss = "";
	private $objSmarty;
	private $arrControle = array();
	private static $objCss;

	private function __construct() {
        $this->objSmarty = ControlSmarty::getSmarty();
        $this->strCss = "";
    }

    public static function getCSS(){
        if (!isset(self::$objCss)) {
            $obj = __CLASS__;
            self::$objCss = new $obj;
        }
        return self::$objCss;
    }

	public function addCss($strNewCss){
		$this->strCss.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$strNewCss."\"/> "."\n";
		$this->objSmarty->assign("DRE_CSS",self::getStrCss());
	}

	public function getStrCss(){
		return $this->strCss;
	}

	public function getDinStrCss(){
		return $this->strDinCss;
	}

	public function setStrCss($strNewCss){
		self::removeCss($strNewCss);
		$this->strDinCss.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$strNewCss."\"/> "."\n";
	}

	public function escreveCss(){
		$this->objSmarty->assign("DRE_DIN_CSS",self::getDinStrCss());
	}

	public function removeCss($strCss){
		$this->strDinCss = str_replace("<link rel=\"stylesheet\" type=\"text/css\" href=\"".$strCss."\"/>", "", $this->strDinCss);
	}

}
?>