<?php
require_once(FWK_MODEL."AbsViewClass.class.php");
require_once(FWK_CONTROL."ControlForms.class.php");
require_once(FWK_CONTROL."ControlPost.class.php");
require_once(FWK_DAO."UsuariosProvDAO.class.php");
require_once(FWK_DAO."UsuariosDAO.class.php");

class ViewCadUsuarios extends AbsViewClass{

	private $objUsuarioProvDAO;
	private $objUsuarioDAO;
	private $objCtrlConfiguracoes;

    public function executa($get,$post,$file){
		switch($get["a"]){
			case "cadastra":
				self::cadastraUsuario($post);
				break;
			case "valida":
				self::validaUsuario($get["id"]);
				break;
			case "altera":

				break;
			case "concluiprov":
				parent::getObjSmarty()->assign("NOME_PORTAL", self::getCtrlConfiguracoes()->getStrTituloPortal());
				if($get["envio"] == "ok"){
					self::getObjSmarty()->assign("TITULO_CADASTRO", "Cadastro efetuado com sucesso!");
					self::concluiCadastro(self::getMsgConcluiCadUsuario());
				}else{
					self::getObjSmarty()->assign("TITULO_CADASTRO", "Erro!");
					self::concluiCadastro(self::getMsgErroConcluiCadUsuario());
				}
				break;
			default:
				//exibe o formulário
				self::getFormCadUser();
				break;
		}
		if(isset($get["msg"]) && $get["msg"] != "")
			self::exibeMens($get["msg"]);
	}

	private function exibeMens($strMens){
		self::getObjSmarty()->assign("MENS_SISTEMA", $strMens);
	}

	/**
	 * Método responsável pela chamada do formulário e exibilo na tela
	 * para cadastro dos usuários no portal.
	 */
	private function getFormCadUser(){
		$objCtrlForm = new ControlForm(self::getXmlForm());
		$objCtrlForm->setEstruturaForm(self::getEstruturaForm());
		$strFormCadUser = self::getTplFormCadUsuario();
		if(isset($strFormCadUser) && $strFormCadUser !=""){
			$objCtrlForm->setTplsFile(DEPOSITO_TPLS);
			$objCtrlForm->setTplsForm($strFormCadUser);
		}else{
			$objCtrlForm->setTplsFile(ADMIN_TPLS);
		}
		$objCtrlForm->setActionForm("".__CLASS__."&a=cadastra");
		$objCtrlForm->registraForm();
	}

	private function getTplFormCadUsuario(){
		$strTplForm = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"formCadUsuarios");
		if(isset($strTplForm) && $strTplForm !=""){
			return $strTplForm;
		}else
			return null;
	}

	private function getObjUsuarioProv(){
		if($this->objUsuarioDAOProvDAO == null)
			$this->objUsuarioDAOProvDAO = new UsuariosProvDAO();
		return $this->objUsuarioDAOProvDAO;
	}

	private function getObjUsuario(){
		if($this->objUsuarioDAO == null)
			$this->objUsuarioDAO = new UsuariosDAO();
		return $this->objUsuarioDAO;
	}

	private function cadastraUsuario($arrPost){
		try{
			self::getObjUsuario()->cadastrar(FWK_XML."formCadUsuarios.xml",$arrPost,null);
		}catch(CrudException $e){
			self::vaiPara("".__CLASS__."&msg=".$e->getMensagem());
		}
	}

	private function concluiCadastro($strTpl){
		parent::getObjSmarty()->assign("NOME_PORTAL", self::getCtrlConfiguracoes()->getStrTituloPortal());
		parent::getObjSmarty()->assign("LINK_LOGIN","?c=".parent::getObjCrypt()->cryptData("login"));
		parent::getObjHttp()->escreEm("MENS_CONCLUSAO",$strTpl);
		parent::getObjHttp()->escreEm("CORPO",self::getTplConcluiCadastro());
	}

	private function validaUsuario($strId){
		try{
			$strId = FormataString::retiraCharsInvalidos($strId);
			self::getObjUsuario()->validaCadastro($strId);
			self::getObjSmarty()->assign("TITULO_CADASTRO", "Validação de Usuário");
			self::concluiCadastro(self::getMsgValUsuario());
		}catch(DaoException $e){
			self::getObjSmarty()->assign("TITULO_CADASTRO", $e->getMensagem());
			self::concluiCadastro(self::getMsgErroValUsuario());
		}
	}

	private function getMsgValUsuario(){
		$strTplMsgValUser = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgValidaUsuario");
		if(isset($strTplMsgValUser) && $strTplMsgValUser != "")
			return $strTplMsgValUser;
		else
			return FWK_HTML_DEFAULT."msgValidaUsuario.tpl";
	}

	private function getMsgErroValUsuario(){
		$strTplMsgValUser = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgErroValidaUsuario");
		if(isset($strTplMsgValUser) && $strTplMsgValUser != "")
			return $strTplMsgValUser;
		else
			return FWK_HTML_DEFAULT."msgErroValidaUsuario.tpl";
	}

	private function getTplConcluiCadastro(){
		$strTplConcluiCadastro = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"concluiCadastro");
		if(isset($strTplConcluiCadastro) && $strTplConcluiCadastro != "")
			return $strTplConcluiCadastro;
		else
			return FWK_HTML_DEFAULT."concluiCadastro.tpl";
	}

	private function getMsgConcluiCadUsuario(){
		$strMsgConcluiCadUsuario = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgConcluiCadUsuario");
		if(isset($strMsgConcluiCadUsuario) && $strMsgConcluiCadUsuario != "")
			return $strMsgConcluiCadUsuario;
		else
			return FWK_HTML_DEFAULT."msgConcluiCadUsuario.tpl";
	}

	private function getMsgErroConcluiCadUsuario(){
		$strMsgErroCadUsuario = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgErroCadUsuario");
		if(isset($strMsgErroCadUsuario) && $strMsgErroCadUsuario != "")
			return $strMsgErroCadUsuario;
		else
			return FWK_HTML_DEFAULT."msgErroCadUsuario.tpl";
	}

	private function getEstruturaForm(){
		$strTplestruturaForm = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"estruturaForm");
		if(isset($strTplestruturaForm) && $strTplestruturaForm != "")
			return $strTplestruturaForm;
		else
			return null;
	}

	private function getXmlForm(){
		$strXmlForm = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null,"xmlForm",DEPOSITO_XMLS);
		if(isset($strXmlForm) && $strXmlForm != "")
			return $strXmlForm;
		else
			return FWK_XML."formCadUsuarios.xml";
	}

}
?>