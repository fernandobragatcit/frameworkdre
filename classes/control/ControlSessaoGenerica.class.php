<?php

class ControlSessaoGenerica {

    private $strSession;

    public function __construct($strNomeSessao = null) {
		if (session_id() == "") {
			session_save_path(SERVIDOR_FISICO."framework/cache/");
	  		session_cache_expire(SESSION_TIME);
	  		session_cache_limiter("private");
	  		session_start();
		}
  		if($strNomeSessao)
  			self::setNomeSessao($strNomeSessao);
    }

    public function setNomeSessao($strSession){
    	$this->strSession = $strSession;
    }

    /**
     * Busca a Sessão corrente se ela existir
     *
     * @author André Coura <andreccls@gmail.com>
     * @since 1.0 - 22/02/2009
     * @param String $strSessao: nome da sessão a ser resgatada
     * @return Object
     */
    public function getObjSessao($strSessao=null){
    	if($strSessao)
    		return (!$_SESSION[$strSessao])?null:unserialize($_SESSION[$strSessao]);
    	return (!$_SESSION[$this->strSession])?null:unserialize($_SESSION[$this->strSession]);
    }

    /**
	 * Método para registrar um objeto na sessão
	 *
	 * @author André Coura
	 * @since 1.0 - 22/02/2009
	 * @param String Nome que terá a sessão
	 * @return Void
	 */
    public function setObjSessao($objAserSerializado, $strSession = null){
		if($strSession)
			$this->strSession = $strSession;
		session_name($this->strSession);
		$_SESSION[$this->strSession] = serialize($objAserSerializado);
    }

    /**
     * Verifica se a sessão passada esta criada
     * @author André Coura <andreccls@gmail.com>
     * @since 1.0 - 22/02/2009
     * @param String $strSessao: nome da sessao
     * @return boolean
     */
    public function verifSessao($strSessao=null){
    	if($strSessao)
			$this->strSession = $strSessao;
    	return self::getObjSessao($this->strSession)?true:false;
    }


	/**
	 * Método resposável por apagar/limpar a sessão criada
	 *
	 * @author André Coura <andreccls@gmail.com>
	 * @since 1.0 - 22/02/2009
	 * @param String nome da sessão a ser apagada
	 * @return void
	 */
    public function limpaSessao($strSessao=null){
    	if($strSessao)
	    	unset($_SESSION[$strSessao]);
    	else
    		unset($_SESSION[$this->strSession]);
    	session_destroy();
    }
}
?>