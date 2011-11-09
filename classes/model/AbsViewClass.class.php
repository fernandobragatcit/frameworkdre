<?php
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_CONTROL."ControlSmarty.class.php");
require_once(FWK_CONTROL."ControlJS.class.php");
require_once(FWK_CONTROL."ControlCSS.class.php");
require_once(FWK_EXCEPTION."HtmlException.class.php");
require_once(FWK_EXCEPTION."FieldsException.class.php");
require_once(FWK_UTIL."FormataInt.class.php");
require_once(FWK_UTIL."FormataCampos.class.php");
require_once(FWK_UTIL."FormataDatas.class.php");
require_once(FWK_UTIL."FormataString.class.php");
require_once(FWK_UTIL."Utf8Parsers.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(BIB_JSON);
require_once(BIB_CAPTCHA);
require_once(BIB_MAILER);

/**
 * Classe abstrata com todos os principais atributos a serem utilizados em todas as classes que herdaram
 *
 * esta para a construção de paginas dinâmicas
 *
 * @author André Coura
 * @since 2.0 - 25/05/2008
 *
 */

abstract class AbsViewClass{

	private $objSmarty;
	private $objCrypt;
	private $objJason;
	private $objHttp;
	private $objBanco;
	private $objCtrlJs;
	private $objCtrlCss;
	private $objCtrlSessao;
	private $objUserSess;

	private $strWireFrame;

	public function __construct($strClassDefault=""){ }

	protected function getObjCrypt(){
		if($this->objCrypt == null)
		$this->objCrypt = new Cryptografia();
		return $this->objCrypt;
	}

	public function getWireFrame(){
		return $this->strWireFrame;
	}

	public function setWireFrame($strWire){
		$this->strWireFrame = $strWire;
	}
	
	protected function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print("<BR>");
			print_r($arrDados[$i]);
		}
		die();
	}

	/**
	 * Classe abstrata que deve ser implementada em todas as classes filhas.
	 *
	 * @author Andre Coura
	 */
	abstract public function executa($get,$post,$file);

	protected function getObjSmarty(){
		if($this->objSmarty == null)
		$this->objSmarty = ControlSmarty::getSmarty();
		return $this->objSmarty;
	}

	protected function getObjHttp(){
		if($this->objHttp == null)
		$this->objHttp = new ControlHttp(self::getObjSmarty());
		return $this->objHttp;
	}

	protected function getObjJson(){
		if($this->objJason == null)
		$this->objJason = new Json();
		return $this->objJason;
	}

	protected function getObjDB(){
		if($this->objBanco == null)
		$this->objBanco = ControlDB::getBanco();
		return $this->objBanco;
	}

	protected function getObjCss(){
		if($this->objCtrlCss == null)
		$this->objCtrlCss = ControlCSS::getCSS();
		return $this->objCtrlCss;
	}

	protected function getObjJs(){
		if($this->objCtrlJs == null)
		$this->objCtrlJs = ControlJS::getJS();
		return $this->objCtrlJs;
	}

	public function setObjCss($css){
		$this->objCtrlCss = $css;
	}

	public function setObjJs($js){
		$this->objCtrlJs = $js;
	}

	protected function verificaInteiro($id=0) {
		try {
			$idNew = FormataInt :: parseInt($id);
		} catch (FieldsException $e) {
			die($e->__toString());
		}
		return $idNew;
	}

	protected function vaiPara($strLocal, $param = "m"){
		@header("Location: ?".$param."=".$strLocal);
	}

	protected function getObjSessao(){
		if($this->objCtrlSessao == null)
		$this->objCtrlSessao = new ControlSessao();
		return $this->objCtrlSessao->getObjSessao(SESSAO_FWK_DRE);
	}

	protected function getObjUsrSessao(){
		if($this->objUserSess == null){
			$objCtrlSess = new ControlSessao();
			$this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		}
		return $this->objUserSess;
	}


	public function showTela($strCorpo, $strEstrutura = "index.tpl") {
		if (!is_file($this->getObjSmarty()->template_dir . $strCorpo) && !is_file($strCorpo))
		throw new HtmlException("Não foi possível encontrar o arquivo " . $strCorpo . " para a criação da tela.");
		if (!is_file($this->getObjSmarty()->template_dir . $strEstrutura) && !is_file($strEstrutura))
		throw new HtmlException("Não foi possível encontrar o arquivo " . $strEstrutura . " para a criação da tela.");
		$this->getObjHttp()->exibeTela($strCorpo);
		$this->getObjSmarty()->display($strEstrutura);
	}
	
	//Matheus 10/08/2011
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
	
	protected function trataTelefonesNaoObrigatorio($campo) {
        if ($campo == "(__) ____-____") {
            $campo = "";
        }
        return $campo;
    }

}
?>