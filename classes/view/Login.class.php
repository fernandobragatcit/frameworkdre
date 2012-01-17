<?php
require_once (FWK_MODEL."AbsViewClass.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");
require_once(FWK_CONTROL."ControlLogin.class.php");

//require_once(FWK_MODEL."AbsModelDao.class.php");

class Login extends AbsViewClass {

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
				case ($get["a"] == "LoginFB") :
                    try {
                        $params = unserialize(parent::getObjCrypt()->decryptData($post["param"]));
                        $strParam = "";
                        if (count($params) > 1)
                            foreach ($params as $key => $valor) {
                                if ($valor != "login" && $valor != "Login")
                                    $strParam .= $key . "=" . parent::getObjCrypt()->cryptData($valor) . "&";
                            }
                        $objCtrlLogin = new ControlLogin();
                        $objCtrlLogin->verificaUsuarioFB($get);
						parent::getObjHttp()->irPag($strParam);
                    } catch (Exception $e) {
						parent::getObjSmarty()->assign("MENS_ERRO", $e->__toString());
						self::telaLogin($get,$post);
					}
                    break;
				case ($get["a"] == "FormRecuperaSenha") :
					try {
						self::telaRecuperaSenha($get,$post);
					} catch (Exception $e) {
						parent::getObjSmarty()->assign("MENS_ERRO", $e->__toString());
					}
					break;
				case ($get["a"] == "PostRecuperaSenha") :
					try {
						$params = unserialize(parent::getObjCrypt()->decryptData($post["param"]));
						$strParam = "";

						if(count($params)>1)
							foreach ($params as $key => $valor) {
								if($valor != "login" && $valor != "Login")
									$strParam .=  $key."=".parent::getObjCrypt()->cryptData($valor)."&";
							}
						//verifica se o email realmente esta cadastrado retorna 1 se falso e o email se verdadeiro
						$objCtrlLogin = new ControlLogin();
						$retorno = $objCtrlLogin->verificaEmail($post);

						if($retorno != 1){
							//gera a nova senha e salva no banco de dados.
							$novaSenha = self::geraSenha();
							$objCtrlLogin = new ControlLogin();
							$objCtrlLogin->salvaSenha($novaSenha, $retorno["email_usuario"]);
							//envia a nova senha por email.
							$enviado = self::enviaEmailUsr($retorno,$novaSenha);
						}
					} catch (Exception $e) {
						self::telaRecuperaSenhaErro();
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
	 */
	public function getTplFormRecuperaSenha() {
		if ($this->strTplEstruturaRecuperaSenha == ""){
			$strTplEstruturaRecuperaSenha = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"tplEstruturaRecuperaSenha");
			if(isset($strTplEstruturaRecuperaSenha) && $strTplEstruturaRecuperaSenha != "")
				$this->strTplEstruturaRecuperaSenha = $strTplEstruturaRecuperaSenha;
			else
				$this->strTplEstruturaRecuperaSenha = FWK_HTML_DEFAULT."formRecuperaSenha.tpl";
		}
		return $this->strTplEstruturaRecuperaSenha;
	}

	public function geraSenha(){
		  $CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
		  $max = strlen($CaracteresAceitos)-1;
		  $password = null;
		  for($i=0; $i < 8; $i++) {
		   $password .= $CaracteresAceitos{mt_rand(0, $max)};
		  }
		  return $password;
	}

	private function enviaEmailUsr($arrDados, $senha){
    	try{
			$objMail = new PHPMailer();
			$objMail->SetLanguage("br");
			if(SMTP_ISSMTP){
				$objMail->IsSMTP();
				$objMail->Host = SMTP_SERV_HOST;
				$objMail->Port = SMTP_SERV_PORTA;
				$objMail->SMTPAuth = SMTP_AUTH;
				$objMail->Username = SMTP_SERV_USER;
				$objMail->Password = SMTP_SERV_PASS; //Senha da caixa postal
			}else{
				$objMail->IsMail();
			}
			$objMail->IsHTML(true);
			$objMail->CharSet = "UTF-8";
			$objMail->From = self::getCtrlConfiguracoes()->getStrEmailPortal();
			$objMail->FromName =  self::getCtrlConfiguracoes()->getStrTituloPortal();

			$objMail->AddAddress($arrDados["email_usuario"]);
			$objMail->Subject = "Nova senha de acesso. [".self::getCtrlConfiguracoes()->getStrTituloPortal()."]";
			$objMail->Body = self::pagMail($arrDados, $senha);
			if ($objMail->Send())
				self::telaRecuperaSenhaConfirm();
			else
				self::telaRecuperaSenhaErro();
		}catch(Exception $e){
			self::telaRecuperaSenhaErro();
		}
    }

    private function pagMail($arrDados, $senha) {
		parent::getObjSmarty()->assign("SUBJECT", "Nova senha de acesso. [".self::getCtrlConfiguracoes()->getStrTituloPortal()."]");
		parent::getObjSmarty()->assign("NOME_PORTAL", self::getCtrlConfiguracoes()->getStrTituloPortal());
		parent::getObjSmarty()->assign("NOME_USUARIO", $arrDados["nome_usuario"]);
		parent::getObjSmarty()->assign("EMAIL_USUARIO", $arrDados["email_usuario"]);
		parent::getObjSmarty()->assign("SENHA_USUARIO", $senha);

		return parent::getObjSmarty()->fetch(FWK_HTML_EMAILS."msgMailNovaSenha.tpl");
	}


	/**
	 * Exibe a tela de erro na recuperação de senha na tela
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 05/11/2010
	 */
	public function telaRecuperaSenhaErro() {
		try {
			parent::getObjHttp()->escreEm("CORPO", self::getTplFormRecuperaSenhaErro());
		} catch (HtmlException $e) {
		}
	}

	/**
	 * Busca o tpl referente ao erro na recuperação de senha
	 */
	public function getTplFormRecuperaSenhaErro() {
		if ($this->strTplEstruturaRecuperaSenhaErro == ""){
			$strTplEstruturaRecuperaSenhaErro = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgErroRecuperaSenha");
			if(isset($strTplEstruturaRecuperaSenhaErro) && $strTplEstruturaRecuperaSenhaErro != "")
				$this->strTplEstruturaRecuperaSenhaErro = $strTplEstruturaRecuperaSenhaErro;
			else
				$this->strTplEstruturaRecuperaSenhaErro = FWK_HTML_DEFAULT."msgErroRecuperaSenha.tpl";
		}
		return $this->strTplEstruturaRecuperaSenhaErro;
	}


	/**
	 * Exibe a tela de confirm na recuperação de senha na tela
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 05/11/2010
	 */
	public function telaRecuperaSenhaConfirm() {
		try {
			parent::getObjHttp()->escreEm("CORPO", self::getTplFormRecuperaSenhaConfirm());
		} catch (HtmlException $e) {
			die("ViewAdminPage()->telaRecuperaSenha(): ".self::getTplFormRecuperaSenhaErro().$e->__toString());
		}
	}

	/**
	 * Busca o tpl referente ao confirm na recuperação de senha
	 */
	public function getTplFormRecuperaSenhaConfirm() {
		if ($this->strTplEstruturaRecuperaSenhaConfirm == ""){
			$strTplEstruturaRecuperaSenhaConfirm = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgConcluiRecuperaSenha");
			if(isset($strTplEstruturaRecuperaSenhaConfirm) && $strTplEstruturaRecuperaSenhaConfirm != "")
				$this->strTplEstruturaRecuperaSenhaConfirm = $strTplEstruturaRecuperaSenhaConfirm;
			else
				$this->strTplEstruturaRecuperaSenhaConfirm = FWK_HTML_DEFAULT."msgConcluiRecuperaSenha.tpl";
		}
		return $this->strTplEstruturaRecuperaSenhaConfirm;
	}

}
?>