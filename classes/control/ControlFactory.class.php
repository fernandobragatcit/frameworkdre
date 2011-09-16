<?php
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");
require_once (FWK_EXCEPTION."FactoryException.class.php");
require_once (FWK_CONTROL."ControlSessao.class.php");

/**
 * Classe de controle de chamada de classes instanciadas dinâmicamente pelo sistema
 * chamada a partir de parâmetro
 *
 * @author André Coura
 * @since 2.0 - 25/05/2008
 */
class ControlFactory {

	private $strTelaDefault;
	private $strDirClass;
	private $ctrlConfigs;
	private $objCrypt;
	private $objCtrlSess;
	private $objClassFactory;

	public function __construct() {
	}

	/**
	 * Método para inicializar a classe responsável pelas configurações do ambiente(pasta)
	 *
	 * @author Andre
	 * @since 1.0 - 11/04/2010
	 */
	private function getCtrlConfigs(){
		if($this->ctrlConfigs == null)
			$this->ctrlConfigs = new ControlConfiguracoes();
		return $this->ctrlConfigs;
	}

	public function setArquivoConfig($strXml){
		self::getCtrlConfigs()->setConfigFile($strXml);
	}

	public function getArquivoConfig(){
		self::getCtrlConfigs()->getConfigFile();
	}

	public function setDirClassDefault($strDirClass){
		$this->strDirClass = $strDirClass;
	}

	public function getDirClassDefault(){
		if($this->strDirClass == null)
			$this->strDirClass = FWK_VIEW;
		return $this->strDirClass;
	}

	public function getPagDefault(){
		if($this->strTelaDefault == null)
			$this->strTelaDefault = "ViewDefault";
		return $this->strTelaDefault;
	}

	public function setPagDefault($newTelaDefault){
		$this->strTelaDefault = $newTelaDefault;
	}

	/**
	 * Método chamado na Classe Main método makeScreen()
	 *
	 * @author Andre
	 * @since 5.1 - 19/07/2010
	 */
	public function buildMotor($get, $post, $file = "") {
		try{
			switch (true) {
				case (self::verificaSessao() && isset ($get) && $get["c"] != "") :

					$strClass = ucfirst($get["c"]);
					if($strClass == "Logout"){
						$strClass = "Login";
						$get["a"] = self::getObjCrypt()->cryptData("Logout");
					}
					if (is_file(self::getDirClassDefault().$strClass.".class.php")){
						require_once (self::getDirClassDefault().$strClass.".class.php");
						$this->objClassFactory = new $strClass (self::getPagDefault());
					}else if(is_file(FWK_VIEW.$strClass.".class.php")){
						require_once (FWK_VIEW.$strClass.".class.php");
						$this->objClassFactory = new $strClass (FWK_VIEW);
					}
					else
						throw new FactoryException("Não foi possível encontrar o arquivo do framework: ".self::getDirClassDefault().$strClass.".class.php");

					//registra as configurações da pasta
					self::getCtrlConfigs()->registraConfigs();
					//registra os conteúdos das telas
					self::getCtrlConfigs()->registraConteudos();

					$this->objClassFactory->executa($get, $post, $file);
					break;
				case (!self::verificaSessao() && isset ($get) && $get["c"] != "") :

					if($get["c"] == "ViewCadUsuarios"){
						require_once (FWK_VIEW."ViewCadUsuarios.class.php");
						$this->objClassFactory = new ViewCadUsuarios(FWK_VIEW);
					}else{
						require_once (FWK_VIEW."Login.class.php");
						$this->objClassFactory = new Login(FWK_VIEW);
					}

					//registra as configurações da pasta
					self::getCtrlConfigs()->registraConfigs();
					self::getCtrlConfigs()->registraConteudos(true);
					$this->objClassFactory->executa($get, $post, $file);
					break;
				
				//classes de construção modular.
				case (self::verificaSessao() && isset($get) && $get["m"] != "") :
					$strClass = ucfirst($get["f"]);
					$strCaminho = PASTA_MODULOS.$get["m"]."/classes/view/";
					if (is_file($strCaminho.$strClass.".class.php")){
						//die($strCaminho.$strClass.".class.php");
						require_once ($strCaminho.$strClass.".class.php");
						$this->objClassFactory = new $strClass (self::getPagDefault());
						//registra as configurações da pasta
						self::getCtrlConfigs()->registraConfigs();
						self::getCtrlConfigs()->registraConteudos(true);
						$this->objClassFactory->executa($get, $post, $file);

						break;
					}else{
						throw new FactoryException("Classe chamada não existe ou informada incorretamente: ".$strCaminho.$strClass.".class.php");
					}

				//classes de construção modular.
				case (!self::verificaSessao() && isset($get) && ($get["f"] == "ViewFormAcesso" || $get["f"] == "ViewCadUsuarios" ) ):
					$strClass = ucfirst($get["f"]);
					$strCaminho = PASTA_MODULOS.$get["m"]."/classes/view/";
					if (is_file($strCaminho.$strClass.".class.php")){
						require_once ($strCaminho.$strClass.".class.php");
						$this->objClassFactory = new $strClass (self::getPagDefault());
						//registra as configurações da pasta
						self::getCtrlConfigs()->registraConfigs();
						self::getCtrlConfigs()->registraConteudos(true);
						$this->objClassFactory->executa($get, $post, $file);
						break;
					}else{
						throw new FactoryException("Classe chamada não existe ou informada incorretamente: ".$strCaminho.$strClass.".class.php");
					}
				//identificador de ShortUrl.
				case (isset($get) && $get["s"] != ""):
					$strClass = ucfirst($get["f"]);
					$strCaminho = PASTA_MODULOS.$get["m"]."/classes/view/";
					if (is_file($strCaminho.$strClass.".class.php")){
						require_once ($strCaminho.$strClass.".class.php");
						$this->objClassFactory = new $strClass (self::getPagDefault());
						//registra as configurações da pasta
						self::getCtrlConfigs()->registraConfigs();
						self::getCtrlConfigs()->registraConteudos(true);
						$this->objClassFactory->executa($get, $post, $file);
						break;
					}else{
						throw new FactoryException("Classe chamada não existe ou informada incorretamente: ".$strCaminho.$strClass.".class.php");
					}

				default :


					if (is_file(self::getDirClassDefault().self::getPagDefault().".class.php")){
						require_once (self::getDirClassDefault() .self::getPagDefault().".class.php");
						$this->objClassFactory = new ViewHome(self::getPagDefault());

					}else if(is_file(self::getDirClassDefault()."Home.class.php")){
						require_once (self::getDirClassDefault()."Home.class.php");
						$this->objClassFactory = new Home(self::getPagDefault());



					}else if(is_file(self::getDirClassDefault().self::getPagDefault().".class.php")){
						require_once (self::getDirClassDefault().self::getPagDefault().".class.php");
						$strClass = ucfirst(self::getPagDefault());
						$this->objClassFactory = new $strClass(self::getPagDefault());


					}else if(is_file(FWK_VIEW.self::getPagDefault().".class.php")){
						require_once (FWK_VIEW.self::getPagDefault().".class.php");
						$strClass = ucfirst(self::getPagDefault());
						$this->objClassFactory = new $strClass(self::getPagDefault());

						//die("here");
					}else if(is_file(FWK_VIEW."ViewDefault.class.php")){
						require_once (FWK_VIEW."ViewDefault.class.php");
						$this->objClassFactory = new ViewDefault(self::getPagDefault());
					}else{
						throw new FactoryException("Não foi possível encontrar o arquivo: ".self::getDirClassDefault().self::getPagDefault().".class.php");
					}


					//registra as configurações da pasta
					self::getCtrlConfigs()->registraConfigs();

					//registra os conteúdos das telas
					self::getCtrlConfigs()->registraConteudos();


					//die("estou aqui control factory linha 173");

					//executa a classe da tela
					$this->objClassFactory->executa($get, $post, $file);
					break;
			}
		}catch(Exception $e1){
			die("Exception: ".$e1->__toString());
		}catch(FactoryException $e2){
			die("FactoryException: ".$e2->__toString());
		}
	}

	/**
	 * Método responsável pela chamada do wireframe que irá criar a tela.
	 * Esse método é chamado na classe Main, método makeScreen
	 */
	public function getWireFrame($arrGet){
		$strWire = self::getCtrlConfigs()->getWireframePag();
		if((!isset($strWire) || $strWire == "" || $arrGet["c"]!="" || $arrGet["m"]!="") && self::verificaSessao())
			$strWire = FWK_HTML_AREAS."wire6.tpl";
		if(ucfirst($arrGet["c"]) == "Login")
			$strWire = FWK_HTML_AREAS."wire6.tpl";
			return $strWire;
	}

	public function buildAdminPage(& $get, & $post, & $file) {
		(isset ($get["c"]) && $get["c"] != "") ? $strClass = ucfirst($get["c"]) : $strClass = ucfirst(self::getPagDefault());
		if (is_file(self::getDirClassDefault().$strClass.".class.php")) {
			require_once (self::getDirClassDefault().$strClass.".class.php");
			$this->objClassFactory = new $strClass (self::getPagDefault());
			$this->objClassFactory->executa($get, $post, $file);
		}else if (is_file(self::getDirClassDefault().$strClass.".class.php")) {
			require_once (self::getDirClassDefault().$strClass.".class.php");
			$this->objClassFactory = new $strClass (self::getPagDefault());
			$this->objClassFactory->executa($get, $post, $file);
		}else if (is_file(FWK_VIEW.$strClass.".class.php")) {
			require_once (FWK_VIEW.$strClass.".class.php");
			$this->objClassFactory = new $strClass (self::getPagDefault());
			$this->objClassFactory->executa($get, $post, $file);
		}else if(isset ($get["tipo"]) && $get["tipo"]!=""){
			die($get["tipo"]);
		} else
			throw new FactoryException("Não foi possível encontrar o arquivo: ".self::getDirClassDefault().$strClass. ".class.php");
	}
	
	public function getObjClassFactory(){
		return $this->objClassFactory;
	}


	private function getObjCrypt(){
		if($this->objCrypt == null)
			$this->objCrypt = new Cryptografia();
		return $this->objCrypt;
	}

	public function getObjCtrSessao(){
		if($this->objCtrlSess == null)
			$this->objCtrlSess = new ControlSessao();
		return $this->objCtrlSess;
	}

	private function verificaSessao(){
		$objUserSess = self::getObjCtrSessao()->getObjSessao(SESSAO_FWK_DRE);
		if($objUserSess)
			return $objUserSess->verUserVisit();
		return false;
	}

}
?>