<?php
require_once(FWK_MODEL."Usuario.class.php");
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_EXCEPTION."UserException.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");

/**
 * Controle ações Usuário
 *
 * @author André Coura
 * @since 05/02/2008
 */
class ControlUsuario{

	private $objUsuario = null;
	private $objDB;
	private $objCripto;

	public function __construct(){

	}

	public function getObjUsuario(){
		if($this->objUsuario == null)
			$this->objUsuario  = new Usuario();
		return $this->objUsuario;
	}

	protected function getObjCripto(){
		if($this->objCripto == null)
			$this->objCripto = new Cryptografia();
		return $this->objCripto;
	}

	/**
	 * Método para adicionar um objeto usuário à sessão
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 */
	protected function logaUser($objUsuario){


	}

	/**
	 * Seta um objeto usuário a partir dos dados vindos do banco de dados
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 * @param Array arrDados vetor com os dados do usuário vindo do banco de dados
	 */
	public function setObjUsuario($arrDados){
		//print("<pre>");
		//print_r($arrDados);
		//die();
		self::getObjUsuario()->setLoginUsuario($arrDados["login_usuario"]);
    	self::getObjUsuario()->setIdTipoUsuario($arrDados["id_tipo_usuario"]);
    	self::getObjUsuario()->setIdUsuario($arrDados["id_usuario"]);
    	self::getObjUsuario()->setNomeUsuario($arrDados["nome_usuario"]);
    	self::getObjUsuario()->setEmailUser($arrDados["email_usuario"]);
    	self::getObjUsuario()->setIpUsuario($_SERVER["REMOTE_ADDR"]);
    	self::getObjUsuario()->setHostUsuario($_SERVER["REMOTE_HOST"]);
    	self::getObjUsuario()->setGrupoUsuario(self::getGruposUsuario($arrDados["id_usuario"]));
    	self::getObjUsuario()->setDataLogin(time());
    	self::getObjUsuario()->setIdioma($arrDados["idioma_usuario"]);
	}

	/**
	 * Método para verificar a existencia de um usuário por login no banco de dados
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 * @param String $strLogin: login do usuario
	 * @param String $strPassw: senha do usuario
	 */
	public function validaUsuarioDB($strEmail,$strPassw){
		$strQuery = "SELECT * FROM fwk_usuario where email_usuario = '".$strEmail."' AND ".
					   " password_usuario = '".self::getObjCripto()->cryptMd5($strPassw)."' ";
		$arrRet = ControlDB::getRow($strQuery,0);
		if(!isset($arrRet["id_usuario"]) && count($arrRet)<5 )
			throw new UserException(MSG_ERRO_LOGIN);
		self::setObjUsuario($arrRet);
		return self::getObjUsuario();
	}

	/**
	 * Método para verificar a existencia de um usuário por facebook no banco de dados
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 * @param String $strLogin: email do usuario
	 */
	public function validaUsuarioFB($strEmail){
		$strQuery = "SELECT * FROM fwk_usuario WHERE email_usuario = '".$strEmail."'";
		$arrRet = ControlDB::getRow($strQuery,0);
		if(!isset($arrRet["id_usuario"]) && count($arrRet)<5 )
			throw new UserException(MSG_ERRO_LOGIN);
		self::setObjUsuario($arrRet);
		return self::getObjUsuario();
	}

	protected function getGruposUsuario($idUsuario){
		$arrRet =  ControlDB::getAll("SELECT id_grupo FROM fwk_grupo_usuario
											WHERE id_usuario = '".$idUsuario."' ");
		$arrGurpos = array();
		if(is_array($arrRet) && count($arrRet) > 0)
			foreach ($arrRet as $arrIdGrupos) {
				$arrGurpos[] = $arrIdGrupos[0];
			}
		return $arrGurpos;
	}


	/**
	 * Método para verificar a existencia de um usuário por email no banco de dados
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 09/11/2010
	 * @param String $strLogin: email do usuario
	 */
	public function validaEmailDB($strEmail){
		$strQuery = "SELECT * FROM fwk_usuario where email_usuario = '".$strEmail."'";
		$arrRet = ControlDB::getRow($strQuery,0);
		if(!isset($arrRet["id_usuario"]) && count($arrRet)<5 ){
			throw new UserException(MSG_ERRO_EMAIL);
			return true;
		}else{
			$objUsuario["email_usuario"] = ($arrRet["email_usuario"]);
			$objUsuario["nome_usuario"]  = ($arrRet["nome_usuario"]);
			return $objUsuario;
		}
	}

	/**
	 * Método para salvar a nova senha no banco de dados
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 09/11/2010
	 * @param String $strPassw: nova senha
	 * @param String $strEmail: email digitado
	 */
	public function salvaSenhaDB($strPassw, $strEmail){

		$strQuery = "UPDATE fwk_usuario SET password_usuario = '".self::getObjCripto()->cryptMd5($strPassw)."' WHERE email_usuario = '".$strEmail."'";
		$arrRet = ControlDB::getRow($strQuery,0);
		return "salvo";
	}

}
?>