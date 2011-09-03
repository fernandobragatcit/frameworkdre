<?php
require_once (FWK_MODEL."AbsViewClass.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");
require_once(FWK_CONTROL."ControlLogin.class.php");

class RecuperaSenha extends AbsViewClass {

	private $objCtrlSess;
	private $objUsuario;
	private $objCtrlConfiguracoes;

	public function executa($get, $post, $file) {

		try {
			switch (true) {
				case ($get["a"] == "Logout") :
					self::getObjCtrSessao()->dieSessao(SESSAO_FWK_DRE);
					parent::getObjHttp()->irPag("");
					break;
				case ($get["a"] == "Login") :
					try {
						$params = unserialize(parent::getObjCrypt()->decryptData($post["param"]));
						$strParam = "";
						if(count($params)>1)
							foreach ($params as $key => $valor) {
								if($valor != "login" && $valor != "Login")
									$strParam .=  $key."=".parent::getObjCrypt()->cryptData($valor)."&";
							}
						$objCtrlLogin = new ControlLogin();
						$objCtrlLogin->verificaUsuario($post);
						parent::getObjHttp()->irPag($strParam);
					} catch (Exception $e) {
						parent::getObjSmarty()->assign("MENS_ERRO", $e->__toString());
						self::telaLogin($get,$post);
					}
					break;
				case ($get["a"] == "RecuperaSenha") :
					try {
						self::telaRecuperaSenha($get,$post);

						$params = unserialize(parent::getObjCrypt()->decryptData($post["param"]));
//						print("<pre>");
//						print_r($get."<br>".$post."<br>");
//						die("ahdiaush");
						$strParam = "";
						if(count($params)>1)
							foreach ($params as $key => $valor) {
								if($valor != "login" && $valor != "Login")
									$strParam .=  $key."=".parent::getObjCrypt()->cryptData($valor)."&";
							}
						$objCtrlLogin = new ControlLogin();
						$objCtrlLogin->verificaUsuario($post);
						parent::getObjHttp()->irPag($strParam);
					} catch (Exception $e) {
						parent::getObjSmarty()->assign("MENS_ERRO", $e->__toString());
					}
					break;
				default :
					self::telaLogin($get,$post);
					break;
			}
		} catch (HtmlException $e) {
			die($e->__toString());
		}
	}

	/**
	 * Verifica se o usuário esta logado
	 *
	 * @author André Coura
	 * @since 1.0 - 03/07/2008
	 * @return boolean
	 */
	private function verificaSessao() {
		$this->objCtrlSess = new ControlSessao();
		$objUserSess = $this->objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		if ($objUserSess)
			return $objUserSess->verUserVisit();
		return false;
	}

	public function getObjCtrSessao() {
		if ($this->objCtrlSess == null)
			$this->objCtrlSess = new ControlSessao();
		return $this->objCtrlSess;
	}

	/**
	 * Exibe a tela de login na tela
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 */
	public function telaLogin($get,$post) {

		try {
			parent::getObjHttp()->escreEm("CORPO", self::getTplFormLogin());
		} catch (HtmlException $e) {
			die("ViewAdminPage()->telaLogin(): ".self::getTplFormLogin().$e->__toString());
		}
	}

	/**
	 * Busca o tpl referente ao login
	 *
	 * TODO: fazer busca em arquivo de configuração (XML)
	 */
	public function getTplFormLogin() {
		$this->getObjJs()->addJs(FWK_JS."validaLogin.js");
		if ($this->strTplLogin == ""){
			$strTplEstruturaLogin = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"tplLogin");
			if(isset($strTplEstruturaLogin) && $strTplEstruturaLogin != "")
				$this->strTplLogin = $strTplEstruturaLogin;
			else
				$this->strTplLogin = FWK_HTML_DEFAULT."formLogin.tpl";
		}
		return $this->strTplLogin;
	}

	private function getCtrlConfiguracoes(){
		if($this->objCtrlConfiguracoes == null)
			$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}

	/**
	 * Exibe a tela de recuperação de senha na tela
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 05/11/2010
	 */
	public function telaRecuperaSenha($get,$post) {

		try {
			parent::getObjHttp()->escreEm("CORPO", self::getTplFormRecuperaSenha());
		} catch (HtmlException $e) {
			die("ViewAdminPage()->telaRecuperaSenha(): ".self::getTplFormRecuperaSenha().$e->__toString());
		}
	}

	/**
	 * Busca o tpl referente a recuperação de senha
	 *
	 * TODO: fazer busca em arquivo de configuração (XML)
	 **/
	public function getTplFormRecuperaSenha() {
		//$this->getObjJs()->addJs(FWK_JS."validaLogin.js");
		if ($this->strTplEstruturaRecuperaSenha == ""){
			//die("asodihjaosijd");
			$strTplEstruturaRecuperaSenha = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"tplEstruturaRecuperaSenha");
			if(isset($strTplEstruturaRecuperaSenha) && $strTplEstruturaRecuperaSenha != "")
				$this->strTplEstruturaRecuperaSenha = $strTplEstruturaRecuperaSenha;
			else
				$this->strTplEstruturaRecuperaSenha = FWK_HTML_DEFAULT."formRecuperaSenha.tpl";
		}
		return $this->strTplEstruturaRecuperaSenha;
	}

}
?>