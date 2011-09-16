<?php
require_once(FWK_UTIL."FormataCampos.class.php");
require_once(FWK_CONTROL."ControlUsuario.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");
require_once(FWK_EXCEPTION."FieldsException.class.php");
require_once(FWK_DAO."UsuariosDAO.class.php");

class ControlLogin {

	private $strLogin;
	private $strEmail;
	private $strPassw;


    public function __construct() {

    }

	/**
	 * Método para validar e consistir os campos para o login
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 */
    public function validaLoginFields($arrCampos){
		try{
			$ativo = self::verificaUsuarioAtivo($arrCampos["email"]);

			if(!$ativo)
				throw new FieldsException("Usuario ainda não validado pelo sistema! Por favor, ative sua conta através do link que foi mandado no seu e-mail.");

			FormataCampos::validaLogin($arrCampos["email"]);
			FormataCampos::validaSenha($arrCampos["passwd"]);
			$this->strEmail = $arrCampos["email"];
			$this->strPassw = $arrCampos["passwd"];
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }
    /**
     * Método para verificar se o usuário que está fazendo login, já está validado.
     */
    public function verificaUsuarioAtivo($emailUsuario){
		$dadosUsuario = self::getObjUsuario()->getDadosUsuariosByEmail($emailUsuario, 0);
		
		if($dadosUsuario["id_tipo_usuario"]==TIPO_USUARIO_PROV)
			return false;
		else
			return true;
    }


	/**
	 * Método para verificar o usuário no banco retornando-o se existir
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 * @return Usuario Objeto usuário
	 * @exception caso não exista o usuário no banco
	 */
    public function verificaUsuario($arrCampos){
		try{
			self::validaLoginFields($arrCampos);
			$objCtrlUsuario = new ControlUsuario();
			$objCtrlSessao = new ControlSessao();
			$objUsuario = $objCtrlUsuario->validaUsuarioDB($this->strEmail,$this->strPassw);
			$objCtrlSessao->setObjUsuario($objUsuario);
			$objCtrlSessao->setSessaoUsuario(SESSAO_FWK_DRE);
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }

	/**
	 * Método para verificar o usuário no banco retornando-o se existir
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 29/08/2011
	 * @return Usuario Objeto usuário
	 * @exception caso não exista o usuário no banco
	 */
    public function verificaUsuarioFB($arrCampos){
		try{
			self::validaEmailFields($arrCampos);
			$objCtrlUsuario = new ControlUsuario();
			$objCtrlSessao = new ControlSessao();
			$objUsuario = $objCtrlUsuario->validaUsuarioFB($this->strEmail);
			$objCtrlSessao->setObjUsuario($objUsuario);
			$objCtrlSessao->setSessaoUsuario(SESSAO_FWK_DRE);
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }



	/**
	 * Método para validar o campo do email
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 09/10/2010
	 */
    public function validaEmailFields($arrCampos){
		try{
			FormataCampos::validaLogin($arrCampos["email"]);
			$this->strEmail = $arrCampos["email"];
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }

    public function verificaEmail($arrCampos){
		try{
			self::validaEmailFields($arrCampos);
			$objCtrlUsuario = new ControlUsuario();
			return $objUsuario = $objCtrlUsuario->validaEmailDB($this->strEmail);
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }

    public function salvaSenha($senha, $strEmail){
		try{
			$this->strPassw = $senha;
			$objCtrlUsuario = new ControlUsuario();
			return $objCtrlUsuario->salvaSenhaDB($this->strPassw, $strEmail);
		}catch(FieldsException $e){
			throw new FieldsException($e->__toString());
		}
    }

    private function getObjUsuario(){
		if($this->objUsuarioDAO == null)
			$this->objUsuarioDAO = new UsuariosDAO();
		return $this->objUsuarioDAO;
	}
}
?>