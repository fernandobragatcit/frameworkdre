<?php
require_once(FWK_EXCEPTION."HtmlException.class.php");
/**
 * Classe de manipulação de eventos http
 */
class ControlHttp {

	private $objSmarty;
  /**
   * Construtor
   *
   * @author André Coura
   * @since 1.0 - 25/08/2007
   */
  public function ControlHttp(&$objSmarty) {
  	$this->objSmarty = $objSmarty;
  }
  /**
   * Conpilação da tela na TAG masty
   *
   * @author André Coura
   * @since 1.0 - 01/09/2007
   * @param String tela a ser exibida
   */
  public function exibeTela($tela){
  	$this->objSmarty->assign("EXIBICAO",$this->objSmarty->fetch($tela));
  }
  /**
   * Escreve um determinado HTML em um local especifico
   */
  public function escreEm($strLocalASerEscrito,$tela){
  	if(!is_file($this->objSmarty->template_dir.$tela) && !is_file($tela))
  		throw new  HtmlException("Não foi possível encontrar o arquivo ".$tela." para a criação da tela.");
  	$this->objSmarty->assign($strLocalASerEscrito,$this->objSmarty->fetch($tela));
  }
  /**
   * Redirecionado de paginas
   *
   * @author Andr� Coura
   * @since 1.0 - 01/09/2007
   * @param String pagina a ser direcionada
   */
  public function irPag($pag){
  	$host  = $_SERVER["HTTP_HOST"];
	$uri   = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
  	header("Location: http://$host$uri/?".$pag);
  }
  /**
   * Descriptografa o parametro passado por get
   *
   * @author Andr� Coura
   * @since 1.0 - 10/09/2007
   */
  public function getParam(){

  }
  /**
   * Criptografa o parametro passado por get
   *
   * @author Andr� Coura
   * @since 1.0 - 10/09/2007
   */
  public function setParam($strParam){

  }
}
?>