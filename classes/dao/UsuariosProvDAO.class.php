<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

/**
 * Classe responsável pelo cadastro do usuário provisório,
 * ou seja, o usuário que cadastrou no portal mas ainda não validou seu usuário.
 * Sendo assim é o primeiro cadastro.
 */
class UsuariosProvDAO extends AbsModelDao{

	public $_table = "fwk_usuario_prov";

	public $_id = "id_usuario";

    public function cadastrar($xml,$post,$file){
		try{
			if (self::testaEmail($post["email_usuario"]) == false){
				self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
				self::validaForm($xml,$post);
				self::salvaPostAutoUtf8($post);
				$this->chave = time();
		    	$this->password_usuario = self::getObjCripto()->cryptMd5($post["password_usuario"]);
		    	$this->idioma_usuario = self::getUsuarioSessao()->getIdioma();
		    	$this->data_cadastro = date("Y-m-d");
				self::salvar();
				self::enviaEmailUsr($post);
			}else
				self::vaiPara("formRecuperaSenha&a=FormRecuperaSenha&msg=E-mail já cadastrado, digite seu email para recuperar sua senha.");
				throw new DaoException("E-mail existente na base de dados");
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }
    
	public function getChaveUsuarioProv(){
		return $this->chave;
	}

    private function enviaEmailUsr($post){
    	try{
			$objMail = new PHPMailer();
			$objMail->SetLanguage("br");
			$objMail->IsMail();
			$objMail->IsHTML(true);
			$objMail->CharSet = "UTF-8";
			$objMail->From = parent::getCtrlConfiguracoes()->getStrEmailPortal();
			$objMail->FromName =  parent::getCtrlConfiguracoes()->getStrTituloPortal();

			$objMail->AddAddress($post["email_usuario"]);
			$objMail->Subject = "Confirmação de Cadastro ".parent::getCtrlConfiguracoes()->getStrEmailPortal();
			$objMail->Body = self::pagMail($post);
			if ($objMail->Send())
				self::vaiPara("ViewCadUsuarios&a=concluiprov&envio=ok");
			else
				self::vaiPara("ViewCadUsuarios&a=concluiprov&envio=erro");

		}catch(Exception $e){
			self::getObjForm()->registraFormValues($post,true);
			parent::getObjHttp()->escreEm("RESULT_CADASTRO",FWK_HTML_DEFAULT."erroCadUsuario.tpl");
		}
    }

    private function pagMail($post) {
		parent::getObjSmarty()->assign("SUBJECT", "Confirmação de Cadastro no Portal ".parent::getCtrlConfiguracoes()->getStrEmailPortal());
		parent::getObjSmarty()->assign("NOME_PORTAL", parent::getCtrlConfiguracoes()->getStrTituloPortal());
		parent::getObjSmarty()->assign("NOME_USUARIO", $post["nome_usuario"]);
		$strPort=($_SERVER["SERVER_PORT"]==80)?"":":".$_SERVER["SERVER_PORT"];
		parent::getObjSmarty()->assign("LINK_CADASTRO", "http://".$_SERVER["SERVER_NAME"].$strPort.
			$_SERVER["PHP_SELF"]."?c=".self::getObjCripto()->cryptData("ViewCadUsuarios&a=valida&chave=".
			$this->chave."&id=".$this->id_usuario));
		return parent::getObjSmarty()->fetch(FWK_HTML_EMAILS."msgMailCadUsuario.tpl");
	}

	public function getUsuarioPovByIdKey($strId, $strKey){
		$strQuery = "SELECT * FROM ".$this->_table." WHERE ".$this->_id."=".$strId." AND chave = ".$strKey;
		return ControlDb::getRow($strQuery,0);
	}

	public function excluiByProv($strId, $strKey){
		if(count(self:: getUsuarioPovByIdKey($strId, $strKey)) == 0)
			throw new DaoException("Usuário inexistente na base de dados");
		$strQuery = "DELETE FROM ".$this->_table." WHERE ".$this->_id."=".$strId." AND chave = ".$strKey;
		return ControlDb::getBanco()->Execute($strQuery);
	}

	public function getUsuarioPovByMail($email_usuario){
		$strQuery = "SELECT
						email_usuario
					FROM
						fwk_usuario_prov
					WHERE
						email_usuario = '".$email_usuario."'";

		if(!ControlDb::getRow($strQuery,0)){
			$strQuery = "SELECT
						email_usuario
					FROM
						fwk_usuario
					WHERE
						email_usuario = '".$email_usuario."'";
			return ControlDb::getRow($strQuery,0);
		}else
			return ControlDb::getRow($strQuery,0);
	}

	public function testaEmail($email_usuario){
		if(count(self:: getUsuarioPovByMail($email_usuario)) == 0)
			return false;
		else
			return true;

	}
}
?>