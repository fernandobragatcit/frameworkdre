<?php
require_once (FWK_MODEL."AbsViewClass.class.php");
require_once (FWK_DAO."UsuarioCompDAO.class.php");
require_once (FWK_DAO."IdiomaDAO.class.php");
require_once (FWK_DAO."TemasDAO.class.php");
require_once (FWK_CONTROL."ControlForms.class.php");
require_once(FWK_DAO."UsuariosDAO.class.php");
require_once(FWK_MODEL."GruposUsuario.class.php");
require_once(FWK_CONTROL."ControlPerfil.class.php");
require_once(CLASSES_DAO."UsrEnderecoDAO.class.php");
require_once(CLASSES_DAO."UsrContatoDAO.class.php");
require_once(CLASSES_DAO."FotosDAO.class.php");
require_once(CLASSES_DAO."ServicoEquipDAO.class.php");
require_once(FWK_MODEL."DocumentoUsuario.class.php");

require_once(FWK_CONTROL."ControlSessao.class.php");

class ViewPerfil extends AbsViewClass {

	private $objDadosComp;
	private $objIdioma;
	private $objUsuario;
	private $objTemas;
	private $ctrlPerfil;
	private $envio;
	private $objFoto;

	public function executa($get, $post, $file) {
		switch ($get["a"]) {
			case "editaUsr" :
				self::postAlteraPerfilUsr($post, $file);
				break;
			case "formEditDadosUsr" :
				self::getFormEditUsr();
				break;
			case "formAltSenha" :
				self::getFormAltSenha();
				break;
			case "editaSenha" :
				self::postAltSenha($post, $file);
				break;
			default :
				self::registraTagsUsr();
				parent::getObjHttp()->escreEm("CORPO_PERFIL_USR", self::getTplHomePerfil());
				break;
		}
		parent::getObjHttp()->escreEm("CORPO", self::getCtrlPerfil()->getTplEstruturaPerfil());
	}


	private function postAlteraPerfilUsr($post,$file){
		try{

			$id = parent::getObjSessao()->getIdUsuario();
			$arrCamposUsr = self::getObjUsuario()->buscaCampos($id);
			$post["password_usuario"] = $arrCamposUsr["password_usuario"];

			self::getObjUsuario()->alterar($id,self::getXmlForm(),$post,$file);
			$post["id_usuario"] = $id;

			$arrEnd = self::getObjUsrEnderecoDAO()->verificaEndUsuario($id);

			if($arrEnd[0]==null){
				self::getObjUsrEnderecoDAO()->cadastrar(self::getXmlForm(),$post,$file);
			}else {
				self::getObjUsrEnderecoDAO()->alterar($id,self::getXmlForm(),$post, $file);
			}

			$post["id_endereco_usuario"] = self::getObjUsrEnderecoDAO()->getIdEndereco();

			$arrCont = self::getObjUsrContatoDAO()->verificaContatoUsuario($id);
			if($arrCont[0]==null){
				self::getObjUsrContatoDAO()->cadastrar(self::getXmlForm(),$post,$file);
			}else {
				self::getObjUsrEnderecoDAO()->alterar($id,self::getXmlForm(),$post, $file);
			}

			$post["id_contato_usuario"] = self::getObjUsrContatoDAO()->getIdContato();

			$arrDadosUsrtComp = self::getObjDadosComp()->getDadosCompById($id);

			if($arrDadosUsrtComp["id_foto"] != null){
				if($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null){
					self::getObjFoto()->alterar($arrDadosUsrtComp["id_foto"],self::getXmlForm(),$post,$file);
					$post["id_foto"] = self::getObjFoto()->getIdFoto();
				}else{
					$post["id_foto"] = $arrDadosUsrtComp["id_foto"];
				}
			}else{
				if($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null){
					self::getObjFoto()->cadastrar(self::getXmlForm(),$post,$file);
					$post["id_foto"] = self::getObjFoto()->getIdFoto();
				}else{
					$post["id_foto"] = null;
				}
			}

			self::getObjDadosComp()->alterar($arrDadosUsrtComp["id_usuario_comp"],self::getXmlForm(),$post,$file);

			self::vaiPara(self::getObjCrypt()->cryptData(__CLASS__), "c");
		}catch(CrudException $e){
			self::vaiPara(self::getObjCrypt()->cryptData(__CLASS__."&msgErro=Erro aqui!"),"c");
		}
	}

	private function getFormEditUsr() {
		$objCtrlForm = new ControlForm(self::getXmlForm());
		$objCtrlForm->setEstruturaForm(self::getCtrlPerfil()->getTplEstruturaPerfil());
		$strFormEditUser = self::getTplEditUsrPerfil();
		if(isset($strFormEditUser) && $strFormEditUser !=""){
			$objCtrlForm->setTplsFile(DEPOSITO_TPLS);
			$objCtrlForm->setTplsForm($strFormEditUser);
		}else{
			$objCtrlForm->setTplsFile(ADMIN_TPLS);
		}
		$objCtrlForm->setActionForm("".__CLASS__."&a=editaUsr");

		$id = parent::getObjSessao()->getIdUsuario();
		$arrDados = self::getObjUsuario()->buscaCampos($id, 0);
		$arrDados = array_merge($arrDados,self::getObjDadosComp()->getDadosCompById($id));

		$arrDados = array_merge($arrDados, self::getObjUsrContatoDAO()->buscaCampos($arrDados[id_contato_usuario]));
		$arrDados = array_merge($arrDados, self::getObjUsrEnderecoDAO()->buscaCampos($arrDados[id_endereco_usuario]));

		parent::getObjSmarty()->assign("sexo_usuario",$arrDados["sexo_usuario"]);

		$objCtrlForm->registraFormValues($arrDados,false);
	}

	public function getObjForm() {
		if ($this->objCtrlForm == null)
			$this->objCtrlForm = new ControlForm(self::getXmlForm());
		return $this->objCtrlForm;
	}

	private function getXmlForm(){
		$strXmlForm = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomForms(null,"xmlFormEditPerfil",DEPOSITO_XMLS);
		if(isset($strXmlForm) && $strXmlForm != "")
			return $strXmlForm;
		else
			return FWK_XML."formUsuarioPerfil.xml";
	}

	private function registraTagsUsr() {
		$arrDadosUsr = self::getObjDadosComp()->getDadosCompById(parent::getObjSessao()->getIdUsuario());
		$arrDadosUsr = Utf8Parsers::arrayUtf8Encode($arrDadosUsr);

		//$arrLinkMin["img"] = $arrDadosUsr[foto_usr];
       // $arrLinkMin["w"] = 100;
        //$arrLinkMin["h"] = 133;
        //$arrLinkMin["perfil"] = "S";

        //$arrDadosUsr[link_img] = self::getObjCrypt()->cryptData(serialize($arrLinkMin));

        parent::getObjSmarty()->assign("CIDADE_USUARIO", $arrDadosUsr["cidade_usuario"]);
		parent::getObjSmarty()->assign("PAIS_USUARIO", $arrDadosUsr["pais_usuario"]);
		parent::getObjSmarty()->assign("FOTO_USUARIO", $arrDadosUsr["link_img"]);
		parent::getObjSmarty()->assign("PROFISSAO_USUARIO", $arrDadosUsr["profissao_usuario"]);
		parent::getObjSmarty()->assign("SEXO_USUARIO", ($arrDadosUsr["sexo_usuario"] == "M" ? "Masculino" : "feminino"));
		parent::getObjSmarty()->assign("NASCIMENTO_USUARIO", FormataDatas::parseDataBR($arrDadosUsr["nascimento_usuario"]));

		$arrDadosUsr["id_foto"] = ($arrDadosUsr["id_foto"]==null || $arrDadosUsr["id_foto"] == "")?2:$arrDadosUsr["id_foto"];
		parent::getObjSmarty()->assign("ID_FOTO_USR", $arrDadosUsr["id_foto"]);

		parent::getObjSmarty()->assign("EMAIL_USUARIO", parent::getObjSessao()->getEmailUser());
		parent::getObjSmarty()->assign("IDIOMA_USUARIO", self::getObjIdioma()->getIdiomaBySigla(parent::getObjSessao()->getIdioma()));

		$arrTema = self::getObjTemas()->getTemaById($arrDadosUsr["id_tema"]);
		parent::getObjSmarty()->assign("TEMA_USUARIO", utf8_encode($arrTema["nome_tema"]));

		parent::getObjSmarty()->assign("LINK_EDIT_USUARIO", "?c=".self::getObjCrypt()->cryptData(__CLASS__."&a=formEditDadosUsr"));

	}

	private function getCtrlPerfil(){
		if($this->ctrlPerfil == null)
			$this->ctrlPerfil = new ControlPerfil();
		return $this->ctrlPerfil;
	}

	private function getTplEditUsrPerfil() {
		$strTplPerfilHome = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null, "perfilUsuarioEditUsr");
		if (isset ($strTplPerfilHome) && $strTplPerfilHome != "")
			return $strTplPerfilHome;
		return FWK_HTML_DEFAULT."perfilUsuario_formEditUsr.tpl";
	}

	private function getTplHomePerfil() {

		$id = parent::getObjSessao()->getIdUsuario();

		$arrGruposUsuario = self::getObjGruposUsuario()->getGrupoUsuario($id);

		for ($i = 0; $i < sizeof($arrGruposUsuario); $i++) {
			if($arrGruposUsuario[$i][0] == "3"){
				$admin = false;
			}else{
				$admin = true;
			}
		}

		if($admin){
			parent::getObjSmarty()->assign("IDUSUARIO",$id);
			$strTplPerfilHome = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null, "perfilAdminHome");

			$arrIdDocumentos = self::getObjDocumentoUsuario()->getDocumentosUsuario($id);

			$auxServEquip = 0;
			for ($i = 0; $i < sizeof($arrIdDocumentos); $i++) {
				switch ($arrIdDocumentos[$i][1]) {
					case TIPODOC_SERVEQUIP:

						$arrConteudoServEquip[$auxServEquip] = Utf8Parsers::arrayUtf8Encode(self::getObjServicoEquipDAO()->getConteudoServEquip($arrIdDocumentos[$i][0], 0));
						$arrLinksServEquip[$auxServEquip][0] = $arrIdDocumentos[$i][0];
						$arrLinksServEquip[$auxServEquip][1] = self::organizaLinksServEquip($arrConteudoServEquip[$auxServEquip]);
						$auxServEquip++;
						break;

					default:
						break;
				}

			}
			parent::getObjSmarty()->assign("ARRSERVEQUIP", $arrConteudoServEquip);
			parent::getObjSmarty()->assign("ARRLINKSSERVEQUIP", $arrLinksServEquip);
//			self::debuga($arrLinksServEquip);
			parent::getObjSmarty()->assign("ARRDOCUMENTOS", $arrConteudoServEquip);
			if (isset ($strTplPerfilHome) && $strTplPerfilHome != ""){

				return $strTplPerfilHome;
			}
		}else{
			$strTplPerfilHome = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null, "perfilUsuarioHome");
			if (isset ($strTplPerfilHome) && $strTplPerfilHome != ""){
				$arrLink = self::getObjCrypt()->cryptData("MeusFavoritos");
				parent::getObjSmarty()->assign("LINK",$arrLink);
				return $strTplPerfilHome;
			}
		}

		return FWK_HTML_DEFAULT."perfilUsuario_home.tpl";
	}

	private function organizaLinksServEquip($arrConteudoServEquip){
		$idDocumento = $arrConteudoServEquip["id_documento"];
		$link="?m=".self::getObjCrypt()->cryptData("formularios&f=CrudTelaConfirmaReserva&a=form&idUsr=".parent::getObjSessao()->getIdUsuario()."&id=".$idDocumento);

		$arrLinks[0]["nome_link"] = "Reservas - Listagem";
		$arrLinks[0]["link"] = $link;

		switch ($arrConteudoServEquip["id_tipo_serv_equip"]) {
			case HOSPEDAGEM:
				$link="?m=".self::getObjCrypt()->cryptData("inventario&f=CrudHospedagem&a=step1&id=".$idDocumento);
				$arrLinks[1]["nome_link"] = "Inventário B1 - Hospedagem";
				$arrLinks[1]["link"] = $link;

				$link="?m=".self::getObjCrypt()->cryptData("formularios&f=CrudDadosComerciaisHotel&a=formHotel&id=".$idDocumento);
				$arrLinks[2]["nome_link"] = "Dados Comerciais";
				$arrLinks[2]["link"] = $link;
				break;
			case GASTRONOMIA:
				$link="?m=".self::getObjCrypt()->cryptData("inventario&f=CrudGastronomia&a=step1&id=".$idDocumento);
				$arrLinks[1]["nome_link"] = "Inventário B2 - Gastronomia";
				$arrLinks[1]["link"] = $link;

				$link="?m=".self::getObjCrypt()->cryptData("formularios&f=CrudDadosComerciaisGastro&a=formGastro&id=".$idDocumento);
				$arrLinks[2]["nome_link"] = "Dados Comerciais";
				$arrLinks[2]["link"] = $link;
				break;
			case TRANSPORTE:
				$link="?m=".self::getObjCrypt()->cryptData("inventario&f=CrudTransporte&a=step1&id=".$idDocumento);
				$arrLinks[1]["nome_link"] = "Inventário B4 - Transporte";
				$arrLinks[1]["link"] = $link;

				break;

			default:
				break;
		}


//		self::debuga($arrConteudoServEquip);

		return $arrLinks;
	}

	private function getObjServicoEquipDAO(){
		if($this->objServicoEquip == null)
			$this->objServicoEquip = new ServicoEquipDAO();
		return $this->objServicoEquip;
	}

	private function getObjDocumentoUsuario(){
		if($this->objDocumentoUsuario == null)
			$this->objDocumentoUsuario = new DocumentoUsuario();
		return $this->objDocumentoUsuario;
	}

	private function getObjDadosComp() {
		if ($this->objDadosComp == null)
			$this->objDadosComp = new UsuarioCompDAO();
		return $this->objDadosComp;
	}

	private function getObjUsuario() {
		if ($this->objUsuario == null)
			$this->objUsuario = new UsuariosDAO();
		return $this->objUsuario;
	}

	private function getObjGruposUsuario() {
		if ($this->objGruposUsuario == null)
			$this->objGruposUsuario = new GruposUsuario();
		return $this->objGruposUsuario;
	}

	private function getObjIdioma() {
		if ($this->objIdioma == null)
			$this->objIdioma = new IdiomaDAO();
		return $this->objIdioma;
	}

	private function getObjTemas() {
		if ($this->objTemas == null)
			$this->objTemas = new TemasDAO();
		return $this->objTemas;
	}

	private function getObjUsrEnderecoDAO() {
		if ($this->getObjUsrEndereco == null)
			$this->getObjUsrEndereco = new UsrEnderecoDAO();
		return $this->getObjUsrEndereco;
	}

	private function getObjUsrContatoDAO() {
		if ($this->getObjUsrContato == null)
			$this->getObjUsrContato = new UsrContatoDAO();
		return $this->getObjUsrContato;
	}




	/**
	 * Exibe a tela de alterar de senha
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 05/11/2010
	 */
	private function getFormAltSenha() {
		$objCtrlForm = new ControlForm(self::getXmlFormAltSenha());
		$objCtrlForm->setEstruturaForm(self::getCtrlPerfil()->getTplEstruturaPerfil());
		$strFormEditUser = self::getTplFormAltSenha();
		if(isset($strFormEditUser) && $strFormEditUser !=""){
			$objCtrlForm->setTplsFile(DEPOSITO_TPLS);
			$objCtrlForm->setTplsForm($strFormEditUser);
		}else{
			$objCtrlForm->setTplsFile(ADMIN_TPLS);
		}
		$objCtrlForm->setActionForm("".__CLASS__."&a=editaSenha");

		$id = parent::getObjSessao()->getIdUsuario();
		$arrDados = self::getObjUsuario()->buscaCampos($id, 0);
		$arrDados = array_merge($arrDados,self::getObjDadosComp()->getDadosCompById($id));

		parent::getObjSmarty()->assign("sexo_usuario",$arrDados["sexo_usuario"]);
		$objCtrlForm->registraFormValues(null,false);
	}

	/**
	 * Busca o tpl referente a tela de alterar senha
	 *
	 * TODO: fazer busca em arquivo de configuração (XML)
	 */
	 private function getTplFormAltSenha() {
		$strTplPerfilAltSenha = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null, "perfilUsuarioAltSenha");
		if (isset ($strTplPerfilAltSenha) && $strTplPerfilAltSenha != "")
			return $strTplPerfilAltSenha;
		return DEPOSITO_TPLS."perfilUsuario_formAltSenha.tpl";
	}

	private function getXmlFormAltSenha(){
		$strXmlFormAltSenha = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomForms(null,"formAltSenhaPerfil",DEPOSITO_XMLS);
		if(isset($strXmlFormAltSenha) && $strXmlFormAltSenha != "")
			return $strXmlFormAltSenha;
		else
			return DEPOSITO_XMLS."formAltSenhaPerfil.xml";
	}


	private function postAltSenha($post,$file){
			$id = parent::getObjSessao()->getIdUsuario();
			$arrCamposUsr = self::getObjUsuario()->buscaCampos($id);
			try{
				if(self::getObjCrypt()->cryptMd5($post["password_usuario_old_conf"]) != $arrCamposUsr["password_usuario"]){
					$this->envio = "erro";
					die("ta diferente senha velha");
				}else{
					if(self::getObjCrypt()->cryptMd5($post["password_usuario"]) != self::getObjCrypt()->cryptMd5($post["password_usuario_conf"])){
						$this->envio = "erro";
						die("ta diferente as novas senhas");
					}else{
						$post["password_usuario_old_conf"] = "";
						$post["password_usuario_conf"] = "";
						$post["id_usuario"] = $id;
						$post["id_tipo_usuario"] = $arrCamposUsr["id_tipo_usuario"];
    					$post["nome_usuario"] = $arrCamposUsr["nome_usuario"];
   						$post["password_usuario"] = self::getObjCrypt()->cryptMd5($post["password_usuario"]);
    					$post["email_usuario"] = $arrCamposUsr["email_usuario"];
    					$post["data_cadastro"] = $arrCamposUsr["data_cadastro"];
    					$post["idioma_usuario"] = $arrCamposUsr["idioma_usuario"];
    					$post["id_tema"] = $arrCamposUsr["id_tema"];

						self::getObjUsuario()->alterar($id,self::getXmlForm(),$post,$file);
						self::vaiPara(self::getObjCrypt()->cryptData(__CLASS__), "c");
//						$this->envio = "ok";
					}
				}
		}catch(CrudException $e){
			die();
			self::vaiPara(self::getObjCrypt()->cryptData(__CLASS__."&msgErro=Erro aqui!"),"c");
		}
	}

	private function concluiCadastro($strTpl){
		parent::getObjSmarty()->assign("NOME_PORTAL", self::getCtrlPerfil()->getCtrlConfiguracoes()->getStrTituloPortal());
		parent::getObjSmarty()->assign("LINK_LOGIN","?c=".parent::getObjCrypt()->cryptData("login"));
		parent::getObjHttp()->escreEm("MENS_CONCLUSAO",$strTpl);
		parent::getObjHttp()->escreEm("CORPO",self::getTplConcluiCadastro());
	}

	private function getMsgValUsuario(){
		$strTplMsgValUser = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgValidaUsuario");
		if(isset($strTplMsgValUser) && $strTplMsgValUser != "")
			return $strTplMsgValUser;
		else
			return FWK_HTML_DEFAULT."msgValidaUsuario.tpl";
	}

	private function getMsgErroValUsuario(){
		$strTplMsgValUser = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgErroValidaUsuario");
		if(isset($strTplMsgValUser) && $strTplMsgValUser != "")
			return $strTplMsgValUser;
		else
			return FWK_HTML_DEFAULT."msgErroValidaUsuario.tpl";
	}

	private function getTplConcluiCadastro(){
		$strTplConcluiCadastro = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null,"concluiCadastro");
		if(isset($strTplConcluiCadastro) && $strTplConcluiCadastro != "")
			return $strTplConcluiCadastro;
		else
			return FWK_HTML_DEFAULT."concluiCadastro.tpl";
	}

	private function getMsgConcluiCadUsuario(){
		$strMsgConcluiCadUsuario = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgConcluiCadUsuario");
		if(isset($strMsgConcluiCadUsuario) && $strMsgConcluiCadUsuario != "")
			return $strMsgConcluiCadUsuario;
		else
			return FWK_HTML_DEFAULT."msgConcluiCadUsuario.tpl";
	}

	private function getMsgErroConcluiCadUsuario(){
		$strMsgErroCadUsuario = self::getCtrlPerfil()->getCtrlConfiguracoes()->getCustomCadUsuarios(null,"msgErroCadUsuario");
		if(isset($strMsgErroCadUsuario) && $strMsgErroCadUsuario != "")
			return $strMsgErroCadUsuario;
		else
			return FWK_HTML_DEFAULT."msgErroCadUsuario.tpl";
	}

	private function getObjFoto(){
		if($this->objFoto == null){
			$this->objFoto = new FotosDAO();
		}
		return $this->objFoto;
	}




}
?>