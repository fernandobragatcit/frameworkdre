<?php
require_once(FWK_CONTROL."ControlSmarty.class.php");

class ControlJS{

	private $strJs;
	private $objSmarty;
	private $strDinJs = "";
	private static $objJs;

    private function __construct() {
        $this->objSmarty = ControlSmarty::getSmarty();
        $this->strJs = "";
    }

    public static function getJS(){
        if (!isset(self::$objJs)) {
            $obj = __CLASS__;
            self::$objJs = new $obj;
        }
        return self::$objJs;
    }


    public function addJs($strNewJs){
		$intPos = strpos($this->strJs, $strNewJs);
			if($intPos > 0)
				return;


		$this->strJs.= "<script type=\"text/javascript\" src=\"".$strNewJs."\" charset=\"utf-8\"></script> "."\n";
		$this->objSmarty->assign("DRE_JS",$this->strJs);
	}

	public function addScriptTxt($strJs){
		$this->strJs .= $strJs;
	}

	public function getDinStrJs(){
		return $this->strDinJs;
	}

	public function setStrJs($strNewJs, $inicio = true){
		//remove o css do inicio da strig? mantendo a ultima?
		if($inicio)
			self::removeJs($strNewJs);
		else{ //mantem a do início
			$intPos = strpos($this->strDinJs, "<script type=\"text/javascript\" src=\"".$strNewJs."\" charset=\"utf-8\"></script> ");
			if($intPos > 0)
				return;
		}
		$this->strDinJs.= " <script type=\"text/javascript\" src=\"".$strNewJs."\" charset=\"utf-8\"></script> "."\n";
	}

	public function escreveJs(){
		$this->objSmarty->assign("DRE_DIN_JS",self::getDinStrJs());
	}

	public function removeJs($strJs){
		$this->strDinJs = str_replace("<script type=\"text/javascript\" src=\"".$strJs."\" charset=\"utf-8\"></script>", "", $this->strDinJs);
	}
}
?>