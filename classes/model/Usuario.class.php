<?php
/**
 * Classe modelo para a estrutura da sessão do usuario que estiver logado
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 1.0 - 03/02/2008
 */
class Usuario {
	private $_strLoginUsr;
	private $_strIdUsr;
	private $_strTipoIdUsr;
	private $_strNomeUsr;
	private $_dataLogin;
	private $_ipUsr;
	private $_strEmailUsr;
	private $_arrGrupoUsr;
	private $_arrDireitosUsr;
	private $_strHostRemoto;
	private $_strAreaAcesso;
	private $_strIdioma;
	private $_strTema;

    public function __construct() {
    }

    public function getIdUsuario(){
    	return $this->_strIdUsr;
    }
    public function setIdUsuario($idUser){
    	$this->_strIdUsr = $idUser;
    }

    public function getIdTipoUsuario(){
    	return $this->_strTipoIdUsr;
    }
    public function setIdTipoUsuario($idTipoUser){
    	$this->_strTipoIdUsr = $idTipoUser;
    }

    public function getLoginUsuario(){
    	return $this->_strLoginUsr;
    }
    public function setLoginUsuario($lognUser){
    	$this->_strLoginUsr = $lognUser;
    }

    public function getNomeUsuario(){
    	return $this->_strNomeUsr;
    }
    public function setNomeUsuario($nomeUser){
    	$this->_strNomeUsr = $nomeUser;
    }

    public function getDataLogin(){
    	return $this->_dataLogin;
    }
    public function setDataLogin($datLogin){
    	$this->_dataLogin = $datLogin;
    }

    public function getIpUsuario(){
    	return $this->_ipUsr;
    }
    public function setIpUsuario($strIp){
    	$this->_ipUsr = $strIp;
    }

    public function getHostUsuario(){
    	return $this->_strHostRemoto;
    }
    public function setHostUsuario($strHost){
    	$this->_strHostRemoto = $strHost;
    }

    public function getEmailUser(){
    	return $this->_strEmailUsr;
    }
    public function setEmailUser($strEmail){
    	$this->_strEmailUsr = $strEmail;
    }

    public function getGrupoUsuario(){
    	return $this->_arrGrupoUsr;
    }
    public function setGrupoUsuario($intGrupo){
    	$this->_arrGrupoUsr = $intGrupo;
    }

    public function getDireitosUsuario(){
    	return $this->_arrDireitosUsr;
    }
    public function setDireitosUsuario($intDireitos){
    	$this->_arrDireitosUsr = $intDireitos;
    }

    public function verUserVisit(){
    	if($this->_strIdUsr == "000" && $this->_strNomeUsr = "Visitante")
    		return false;
    	return true;
    }

    public function getAreaAcesso(){
    	return $this->_strAreaAcesso;
    }

    public function setAreaAcesso($strArea){
    	$this->_strAreaAcesso = $strArea;
    }

    public function getIdioma(){
    	return $this->_strIdioma;
    }

    public function setIdioma($strIdioma){
    	$this->_strIdioma = $strIdioma;
    }
    
    public function getTema(){
    	return $this->_strTema;
    }

    public function setTema($strTema){
    	$this->_strTema = $strTema;
    }
}
?>