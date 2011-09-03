<?php
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");
require_once (FWK_EXCEPTION."FactoryException.class.php");
/**
 * Classe de controle de chamada de classes instanciadas dinâmicamente pelo sistema
 * chamada a partir de parâmetro
 *
 * @author André Coura
 * @since 2.0 - 25/05/2008
 */
class FactoryBuild {

	private $strTelaDefault;
	private $strDirClass;
	private $ctrlConfigs;

	//parametro do construtor retirado por questões de problemas em customizações
	public function __construct($dirClass = null) {
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

	public function buildMotor($get, $post, $file = "") {
		switch (true) {
			case (isset ($get) && $get["c"] != "") :
				$strClass = ucfirst($get["c"]);
				if (!is_file(self::getDirClassDefault().$strClass.".class.php"))
					throw new FactoryException("Não foi possível encontrar o arquivo: ".self::getDirClassDefault().$strClass.".class.php");
				require_once (self::getDirClassDefault().$strClass.".class.php");
				$objFactory = new $strClass (self::getPagDefault());
				$objFactory->executa($get, $post, $file);
				break;
			default :
				if (is_file(self::getDirClassDefault().self::getPagDefault().".class.php")){
					require_once (self::getDirClassDefault() .self::getPagDefault().".class.php");
					$objFactory = new ViewHome(self::getPagDefault());
				}else if(is_file(self::getDirClassDefault()."Home.class.php")){
					require_once (self::getDirClassDefault()."Home.class.php");
					$objFactory = new Home(self::getPagDefault());
				}else if(is_file(self::getDirClassDefault().self::getPagDefault().".class.php")){
					require_once (self::getDirClassDefault().self::getPagDefault().".class.php");
					$strClass = ucfirst(self::getPagDefault());
					$objFactory = new $strClass(self::getPagDefault());
				}else if(is_file(FWK_VIEW.self::getPagDefault().".class.php")){
					require_once (FWK_VIEW.self::getPagDefault().".class.php");
					$strClass = ucfirst(self::getPagDefault());
					$objFactory = new $strClass(self::getPagDefault());
				}else if(is_file(FWK_VIEW."ViewDefault.class.php")){
					require_once (FWK_VIEW."ViewDefault.class.php");
					$objFactory = new ViewDefault(self::getPagDefault());
				}else{
					throw new FactoryException("Não foi possível encontrar o arquivo: ".self::getDirClassDefault().self::getPagDefault().".class.php");
				}
				//registra as configurações da pasta
				self::getCtrlConfigs()->registraConfigs();
				//executa a classe da tela
				$objFactory->executa($get, $post, $file);
				break;
		}
	}

	public function buildAdminPage(& $get, & $post, & $file) {
		(isset ($get["c"]) && $get["c"] != "") ? $strClass = ucfirst($get["c"]) : $strClass = ucfirst(self::getPagDefault());
		if (is_file(self::getDirClassDefault().$strClass.".class.php")) {
			require_once (self::getDirClassDefault().$strClass.".class.php");
			$objFactory = new $strClass (self::getPagDefault());
			$objFactory->executa($get, $post, $file);
		}else if (is_file(self::getDirClassDefault().$strClass.".class.php")) {
			require_once (self::getDirClassDefault().$strClass.".class.php");
			$objFactory = new $strClass (self::getPagDefault());
			$objFactory->executa($get, $post, $file);
		}else if (is_file(FWK_VIEW.$strClass.".class.php")) {
			require_once (FWK_VIEW.$strClass.".class.php");
			$objFactory = new $strClass (self::getPagDefault());
			$objFactory->executa($get, $post, $file);
		}else if(isset ($get["tipo"]) && $get["tipo"]!=""){
			die($get["tipo"]);
		} else
			throw new FactoryException("Não foi possível encontrar o arquivo: ".self::getDirClassDefault().$strClass. ".class.php");
	}

}
?>