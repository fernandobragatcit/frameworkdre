<?php
require_once(FWK_MODEL."AbsViewClass.class.php");
require_once(FWK_MODEL."Usuario.class.php");
require_once(FWK_CONTROL."ControlLogin.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(FWK_CONTROL."ControlFactory.class.php");
require_once(FWK_CONTROL."ControlCSS.class.php");
require_once(FWK_CONTROL."ControlGrid.class.php");
require_once(FWK_CONTROL."ControlJS.class.php");
require_once(FWK_UTIL."FormataParametros.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_VIEW."ViewMenu.class.php");

/**
 * Classe de criação do Admin Page
 *
 * @author André Coura
 * @since 1.0 - 01/07/2008
 */
class ViewAdminPage extends AbsViewClass{

	private $objCtrlSess;
	private $objUsuario;
	private $strCabecalho;
	private $strCssGrid;
	private $strRodape;
	private $strMenu;
	private $strTplLogin;
	private $strEstruturaLogin;
	private $strEstruturaAdmin;
	private $strRodapeLogin;
	private $adminPageFolder;
	private $adminPageHome;


	/**
	 * Método para executar o componente
	 *
	 * @author André Coura
	 * @since 1.0 - 03/07/2008
	 * @param Array $get variável global $_GET
	 * @param Array $post variável global $_POST
	 * @param String $tplAdmin template do corpo admin para o Login
	 */
	public function executa($get,$post,$file){
		try {
			switch(true){
				case (parent::getObjCrypt()->decryptData($get["c"]) == "Logout"):
					self::getObjCtrSessao()->dieSessao(SESSAO_FWK_DRE);
					parent::getObjHttp()->irPag("");
					break;
				case self::verificaSessao():
					self::getAdminPages($get,$post,$file);
					self::showTela(self::getEstruturaAdmin());
					break;
				default:
					self::getLoginPage($get,$post);
					self::showTela(self::getEstruturaLogin());
			}
		} catch (HtmlException $e) {
			die($e->__toString());
		}
	}

	public function getEstruturaAdmin(){
		if($this->strEstruturaAdmin == null)
			$this->strEstruturaAdmin = FWK_HTML_ADMINPAGE."AdminEstrutura.tpl";
		return $this->strEstruturaAdmin;
	}
	public function setEstruturaAdmin($strEstruturaAdmin){
		$this->strEstruturaAdmin = $strEstruturaAdmin;
	}

	public function getEstruturaLogin(){
		if($this->strEstruturaLogin == null)
			$this->strEstruturaLogin = FWK_HTML_ADMINPAGE."AdminEstrutura.tpl";
		return $this->strEstruturaLogin;
	}

	public function setEstruturaLogin($strEstruturaLogin){
		$this->strEstruturaLogin = $strEstruturaLogin;
	}

	/**
	 * Método para definir as estruturas das telas do sistema.
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getAdminPages($get,$post,$file){
		try{
			self::regsCabecalho();
			self::regsMenu();
			self::regsRodape();
			$arrGet = parent::getObjCrypt()->decryptData($get["c"]);
			$objFormatParam = new FormataParametros();
			$objFormatParam->setParametros($arrGet);
			$objFactoryTela = new ControlFactory();
			$objFactoryTela->setDirClassDefault(self::getAdminPageViewFolder());
			$objFactoryTela->setPagDefault(self::getAdminPageHome());
			ControlGrid::getGrid()->setCssGrid(self::getCssGrid());
			$objFactoryTela->buildAdminPage($objFormatParam->getParametros(),$post,$file);
		}catch(Exception $e){
			die($e->__toString());
		}
	}

	public function getAdminPageHome(){
		if($this->adminPageHome == null)
			$this->adminPageHome = "ViewHome";
		return $this->adminPageHome;
	}

	public function setAdminPageHome($admHome){
		$this->adminPageHome = $admHome;
	}

	public function getAdminPageViewFolder(){
		if($this->adminPageFolder == NULL)
			$this->adminPageFolder = ADMINPAGE_VIEW;
		return $this->adminPageFolder;
	}

	public function setAdminPageViewFolder($strAdminFolder){
		$this->adminPageFolder = $strAdminFolder;
	}

	/**
	 * Método para setar o tpl do cabeçalho
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 * @param String $strCabecalho
	 */
	public function setCabecalho($strCabecalho){
		$this->strCabecalho = $strCabecalho;
	}

	/**
	 * Método para setar o tpl do Menu
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 * @param String $strMenu
	 */
	public function setTplMenu($strMenu){
		$this->strMenu = $strMenu;
	}
	/**
	 * Método para setar o tpl do Menu
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 * @param String $strMenu
	 */
	public function getTplMenu(){
		return $this->strMenu;
	}

	/**
	 * Método para setar o tpl do rodape
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 * @param String $strRodape
	 */
	public function setRodape($strRodape){
		$this->strRodape = $strRodape;
	}

	public function getRodape(){
		return $this->strRodape;
	}

	/**
	 * Método para registrar a estrutura do cabeçalho
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 */
	private function regsCabecalho(){
		if($this->strCabecalho)
			try{
				parent::getObjHttp()->escreEm("CABECALHO",$this->strCabecalho);
			}catch(HtmlException $e){
				die($e->__toString());
			}
	}

	/**
	 * Método para registrar a estrutura do cabeçalho
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 */
	private function regsMenu(){
		$objMenu = new ViewMenu();
		$objMenu->setObjCss(parent::getObjCss());
		$objMenu->setObjJs(parent::getObjJs());
		$objMenu->setCssMenu(self::getCssMenu());
		$objMenu->setTplMenu(self::getTplMenu());
		$objUsuario = $this->objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		parent::getObjSmarty()->assign("MENU",$objMenu->geraMenu($objUsuario->getGrupoUsuario(), $objUsuario->getIdUsuario()));
	}

	public function setCssMenu($cssMenu){
		$this->cssMenu = $cssMenu;
	}

	public function getCssMenu(){
		return $this->cssMenu;
	}

	public function setCssGrid($strCssGrid){
		$this->strCssGrid = $strCssGrid;
	}

	public function getCssGrid(){
		return $this->strCssGrid;
	}

	/**
	 * Método para registrar a estrutura do rodapé
	 *
	 * @author André Coura
	 * @since 1.0 - 06/07/2008
	 */
	private function regsRodape(){
		if(self::getRodape())
			try{
				parent::getObjHttp()->escreEm("RODAPE",self::getRodape());
			}catch(HtmlException $e){
				die($e->__toString());
			}
	}

	/**
	 * Busca da tela de login para a área
	 *
	 * @author André Coura
	 * @since 1.0 - 03/07/2008
	 */
	private function getLoginPage($get,$post){
		switch(true){
			case (parent::getObjCrypt()->decryptData($get["c"]) == "Login"):
				try{
					$objCtrlLogin = new ControlLogin();
					$objCtrlLogin->verificaUsuario($post);
					parent::getObjHttp()->irPag("c=".parent::getObjCrypt()->cryptData(self::getAdminPageHome()));
				}catch(Exception $e){
					parent::getObjSmarty()->assign("MENS_ERRO",$e->__toString());
					self::telaLogin();
				}
				break;
			default:
				self::telaLogin();
				break;
		}
	}

	/**
	 * Exibe a tela de login na tela
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 */
	private function telaLogin(){
		parent::getObjSmarty()->assign("POST_LOGIN","?c=".parent::getObjCrypt()->cryptData("Login"));
		$this->getObjJs()->addJs(FWK_JS."validaLogin.js");
		try{
			parent::getObjHttp()->escreEm("RODAPE_LOGIN_ADMIN",self::getRodapeLogin());
			parent::getObjHttp()->escreEm("CORPO",self::getTplFormLogin());
		}catch(HtmlException $e){
			die("Tpl não encontrado: ".self::getTplFormLogin().$e->__toString());
		}
	}

	/**
	 * Busca o tpl referente ao
	 */
	public function getTplFormLogin(){
		if($this->strTplLogin == "")
			$this->strTplLogin = FWK_HTML_ADMINPAGE."AdminLogin.tpl";
		return $this->strTplLogin;
	}

	public function setTplFormLogin($strTplLogin){
		$this->strTplLogin = $strTplLogin;
	}

	public function setRodapeLogin($strRodapeLogin){
		$this->strRodapeLogin = $strRodapeLogin;
	}

	public function getRodapeLogin(){
		if($this->strRodapeLogin == null){
			if(self::getRodape() != null)
				$this->strRodapeLogin = self::getRodape();
			else
				$this->strRodapeLogin = FWK_HTML_ADMINPAGE."rodape.tpl";
		}
		return $this->strRodapeLogin;
	}

	/**
	 * Verifica se o usuário esta logado
	 *
	 * @author André Coura
	 * @since 1.0 - 03/07/2008
	 * @return boolean
	 */
	private function verificaSessao(){
		$this->objCtrlSess = new ControlSessao();
		$objUserSess = $this->objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		if($objUserSess)
			return $objUserSess->verUserVisit();
		return false;
	}

	public function getObjCtrSessao(){
		if($this->objCtrlSess == null)
			$this->objCtrlSess = new ControlSessao();
		return $this->objCtrlSess;
	}

}
?>