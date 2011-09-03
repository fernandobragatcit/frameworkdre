<?php
require_once (FWK_MODEL."Usuario.class.php");
require_once (FWK_CONTROL."ControlSessaoGenerica.class.php");
require_once (FWK_CONTROL."ControlUsuario.class.php");

/**
 * Classe de controle de sessão do usuário
 *
 * @author André Coura
 * @since 1.0 - 04/02/2008
 */
class ControlSessao {

	private $_strSession;
	private $_objUsuario;

	public function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}

	}

	/**
	 * Busca a Sessão corrente se ela existir
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 03/02/2008
	 * @param String $strSessao nome da sessão a ser resgatada
	 */
	public function getObjSessao($strSessao) {
		self::verificaTempoSessao($strSessao);
		if (!$_SESSION[$strSessao])
			self::setVisitante($strSessao);
		return unserialize($_SESSION[$strSessao]);
	}

	private function verificaTempoSessao($strSessao) {
		if (!isset ($_SESSION[$strSessao]) && $_SESSION[$strSessao] == null)
			return;
		$objUsuario = unserialize($_SESSION[$strSessao]);
		if (((double) time() - (double) $objUsuario->getDataLogin()) > (double) TEMPO_SESSAO_FWK_DRE) {
			self::dieSessao($strSessao);
		} else {
			$objUsuario->setDataLogin(time());
			$_SESSION[$strSessao] = serialize($objUsuario);
		}
	}
	/**
	 * Define uma nova sessão
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 03/02/2008
	 * @param String $strSessao nome da sessão a ser criada
	 * @param Array vetor contendo os dados do usuário a ser registrado na sessão
	 */
	public function setUserSessao($strSessao, $arrDadosUser) {
		self::defineSessao($arrDadosUser);
		self::setSessaoUsuario($strSessao);
	}

	/**
	 * Define a sessão do visitante
	 *
	 * @author André Coura
	 * @since 1.0 - 03/07/2008
	 * @param String $strSessao nome da sessão a ser criada
	 * @param String nome da sessão
	 */

	public function setVisitante($strSessao) {
		self::defineSessaoVisitante();
		self::setSessaoUsuario($strSessao);
	}

	/**
	 * Define a sessão do usuario oriundo do facebook
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 07/04/2011
	 * @param String $strSessao nome da sessão a ser criada
	 * @param Array $arrDados dados do usuario a ser colocados na sessão
	 */
	public function setFacebook($arrDados,$strSessao) {
		self::defineSessaoFacebook($arrDados);
		self::setSessaoUsuario($strSessao);
	}

	public function setObjUsuario($objUsuario) {
		$this->_objUsuario = $objUsuario;
	}

	private function getObjUsuario() {
		if (!$this->_objUsuario)
			$this->_objUsuario = new Usuario();
		return $this->_objUsuario;
	}

	/**
	 * Define os dados que serão salvos na sessão
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 03/02/2008
	 * @return void
	 */
	public function defineSessao($arrDadosUser) {
		$objCtrlUsuario = new ControlUsuario();
		$objCtrlUsuario->setObjUsuario($arrDadosUser);
		self::setObjUsuario($objCtrlUsuario->getObjUsuario());
	}

	/**
	 * Método para registrar o objeto usuário serializado na sessão
	 *
	 * @author André Coura
	 * @since 1.0 - 05/07/2008
	 * @param String Nome que terá a sessão
	 * @return Void
	 */
	public function setSessaoUsuario($strSession) {
		$this->_strSession = $strSession;
		session_name($strSession);
		$_SESSION[$strSession] = serialize(self::getObjUsuario());
	}

	/**
	 * Define os dados de um visitante, ou usuário ainda não logado
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 03/07/2008
	 * @return void
	 */
	private function defineSessaoVisitante() {
		self::getObjUsuario()->setLoginUsuario("Visitante");
		self::getObjUsuario()->setIdTipoUsuario("0");
		self::getObjUsuario()->setIdUsuario("0");
		self::getObjUsuario()->setNomeUsuario("Visitante");
		self::getObjUsuario()->setEmailUser("visitante@email.com");
		self::getObjUsuario()->setIpUsuario($_SERVER["REMOTE_ADDR"]);
		self::getObjUsuario()->setHostUsuario($_SERVER["REMOTE_HOST"]);
		self::getObjUsuario()->setGrupoUsuario("0");
		self::getObjUsuario()->setDataLogin(time());
		self::getObjUsuario()->setIdioma("pt_br");
		self::getObjUsuario()->setTema("random");
	}
	/**
	 * Verifica se a sessão passada esta criada
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 04/02/2008
	 * @param String $strSessao nome da sessao
	 * @return bool
	 */
	public function verifSessao($strSessao) {
		return $_SESSION[$strSessao] ? true : false;
	}
	/**
	 * Mata a sessao aberta
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 04/02/2008
	 */
	public function dieSessao($strSessao) {
		unset ($_SESSION[$strSessao]);
		session_destroy();
		self::setVisitante($strSessao);
	}

	/**
	 * Define os dados de um usuario com login do facebook
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 07/04/2011
	 */
	private function defineSessaoFacebook($arrDados) {
		self::getObjUsuario()->setLoginUsuario($arrDados["email"]);
		self::getObjUsuario()->setIdTipoUsuario($arrDados["0"]);
		self::getObjUsuario()->setIdUsuario($arrDados["id"]);
		self::getObjUsuario()->setNomeUsuario($arrDados["name"]);
		self::getObjUsuario()->setEmailUser($arrDados["email"]);
		self::getObjUsuario()->setIpUsuario($_SERVER["REMOTE_ADDR"]);
		self::getObjUsuario()->setHostUsuario($_SERVER["REMOTE_HOST"]);
		self::getObjUsuario()->setGrupoUsuario("3");
		self::getObjUsuario()->setDataLogin(time());
		self::getObjUsuario()->setIdioma("pt_br");
		self::getObjUsuario()->setTema("random");
	}
}
?>