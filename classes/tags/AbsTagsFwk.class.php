<?php
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_UTIL."FormataLink.class.php");
require_once(FWK_UTIL."FormataPost.class.php");
require_once(FWK_UTIL."Utf8Parsers.class.php");
require_once(FWK_UTIL."FormataDatas.class.php");
require_once(FWK_UTIL."FormataString.class.php");
require_once(FWK_CONTROL."ControlSmarty.class.php");
require_once(FWK_CONTROL."ControlForms.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(FWK_CONTROL."ControlPost.class.php");
require_once(FWK_CONTROL."ControlCSS.class.php");
require_once(FWK_CONTROL."ControlJS.class.php");
require_once(FWK_CONTROL."ControlMail.class.php");
require_once(FWK_UTIL."FormataParametros.class.php");
require_once(FWK_EXCEPTION."TagsException.class.php");
require_once(BIB_JSON);


abstract class AbsTagsFwk {

	private $objSmarty;
	private $objHttp;
	private $objBanco;
	private $objCtrlJs;
	private $objCtrlCss;
	private $strCssGrid;
	private $objUserSess;
	private $objCrypt;
	private $strTipo;
	private $tipoObj;
	private $idObj;
	private $localObj;
	private $strCategoria;
	private $param1;
	private $param2;
	private $param3;
	private $param4;
	private $param5;
	private $arrParamsAjx;
	private $objCtrlForms;


	public function __construct(){
			ControlJS::getJS()->addJs(FWK_JS."jquery.galleriffic.js");
		//self::debuga(ControlJS::getJS());
	}

	public function setTipoObj($strTipoObj){
		self::getObjSmarty()->assign("TIPO_OBJ",$strTipoObj);
		$this->tipoObj = $strTipoObj;
	}

	public function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print_r($arrDados[$i]);
			print "<br>";
		}
		die();
	}

	protected function getObjJson(){
		if($this->objJason == null)
		$this->objJason = new Json();
		return $this->objJason;
	}

	public function getTipoObj(){
		return $this->tipoObj;
	}

	public function setIdObj($intIdObj){
		self::getObjSmarty()->assign("ID_OBJ",$intIdObj);
		$this->idObj = $intIdObj;
	}

	public function getIdObj(){
		return $this->idObj;
	}

	public function setLocalObj($strLocalObj){
		self::getObjSmarty()->assign("LOCAL_OBJ",$strLocalObj);
		$this->localObj = $strLocalObj;
	}

	public function getLocalObj(){
		return $this->localObj;
	}


	/**
	 * Método obrigatório para exibição da tag criada.
	 *
	 * @author Andre
	 * @since 1.0 - 04/08/2010
	 */
	abstract public function executeTag();





	protected function verificaSessao(){
		$objUserSess = self::getObjCtrSessao()->getObjSessao(SESSAO_FWK_DRE);
		if($objUserSess)
		return $objUserSess->verUserVisit();
		return false;
	}

	public function getObjCtrSessao(){
		if($this->objCtrlSess == null)
		$this->objCtrlSess = new ControlSessao();
		return $this->objCtrlSess;
	}

	protected function getObjSessaoAdmin(){
		if($this->objUserSess == null){
			$objCtrlSess = new ControlSessao();
			$this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		}
		return $this->objUserSess;
	}

	protected function getObjUsrSessao(){
		if($this->objUserSess == null){
			$objCtrlSess = new ControlSessao();
			$this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		}
		return $this->objUserSess;
	}


	public function getObjCss(){
		if($this->objCtrlCss == null)
		$this->objCtrlCss = ControlCSS::getCSS();
		return $this->objCtrlCss;
	}


	public function getObjJs(){
		if($this->objCtrlJs == null)
		$this->objCtrlJs = ControlJS::getJS();
		return $this->objCtrlJs;
	}

	public function getObjSmarty(){
		if($this->objSmarty == null)
		$this->objSmarty = ControlSmarty::getSmarty();
		return $this->objSmarty;
	}

	public function getObjHttp(){
		if($this->objHttp == null)
		$this->objHttp = new ControlHttp(self::getObjSmarty());
		return $this->objHttp;
	}

	public function getObjDB(){
		if($this->objBanco == null)
		$this->objBanco = ControlDB::getBanco();
		return $this->objBanco;
	}

	protected function getObjCrypt(){
		if($this->objCrypt == null){
			$this->objCrypt = new Cryptografia();
		}
		return $this->objCrypt;
	}


	protected function vaiPara($strLocal){
		$strLink = self::getObjCrypt()->cryptData((self::getCategoria()!=""?self::getCategoria()."&f=":"").$strLocal);
		header("Location: ?".FormataLink::definiTipoLink(self::getTipo())."=".$strLink);
	}


	public function getTipo(){
		if(!isset($this->strTipo) || $this->strTipo == "")
		$this->strTipo = "c";
		return $this->strTipo;
	}

	public function setTipo($tipo){
		$this->strTipo = $tipo;
	}

	public function getCategoria(){
		if(!isset($this->strCategoria) || $this->strCategoria == "")
		$this->strCategoria = "";
		return $this->strCategoria;
	}

	public function setCategoria($cat){
		self::getObjSmarty()->assign("CATEGORIA",$cat);
		$this->strCategoria = $cat;
	}

	public function getParam1(){
		return $this->param1;
	}
	public function setParam1($param1 = null){
		self::getObjSmarty()->assign("PARAM1",$param1);
		$this->param1 = $param1;
	}

	public function getParam2(){
		return $this->param2;
	}
	public function setParam2($param2 = null){
		self::getObjSmarty()->assign("PARAM2",$param2);
		$this->param2 = $param2;
	}

	public function getParam3(){
		return $this->param3;
	}
	public function setParam3($param3 = null){
		self::getObjSmarty()->assign("PARAM3",$param3);
		$this->param3 = $param3;
	}

	public function getParam4(){
		return $this->param4;
	}
	public function setParam4($param4 = null){
		self::getObjSmarty()->assign("PARAM4",$param4);
		$this->param4 = $param4;
	}

	public function getParam5(){
		return $this->param5;
	}
	public function setParam5($param5 = null){
		self::getObjSmarty()->assign("PARAM5",$param5);
		$this->param5 = $param5;
	}

	public function setParamsAjx($arrParams = null){
		$this->arrParamsAjx = $arrParams;
	}
	public function getParamsAjx(){
		return $this->arrParamsAjx;
	}


	/**
	 * Busca as respectivas áreas, lembrando que o ultimo elemento do array
	 *
	 * @author André Coura
	 * @since 1.0 - 15/04/2011
	 */
	public function getAreaUrl(){
		$strArea = "http://". $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$arrArea1 = explode("?",$strArea);
		$arrArea = explode("/",$arrArea1[0]);
		$arrTratado = array();
		foreach ($arrArea as $strArea) {
			if(isset($strArea) && trim($strArea) != ""){
				$arrTratado[]=$strArea;
			}
		}
		return $arrTratado;
	}

	public function getStrUrl($url = ""){
		if($url == "")
		$arrArea = self::getAreaUrl();
		else
		$arrArea = $url;
		array_shift($arrArea);
		return "http://".implode("/",$arrArea);
	}

	protected function getCtrlForms(){
		if($this->objCtrlForms == null){
			$this->objCtrlForms = new ControlForm();
		}
		return $this->objCtrlForms;
	}
	
	protected function getCtrlConfiguracoes(){
		if($this->objCtrlConfiguracoes == null)
		$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}
	
	protected function getCtrlMail(){
		if($this->objCtrlMail == null)
		$this->objCtrlMail = new ControlMail();
		return $this->objCtrlMail;
	}
}
?>