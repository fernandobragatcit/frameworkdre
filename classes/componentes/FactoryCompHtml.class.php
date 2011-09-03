<?php
/**
 * Factory responsável por instanciar um determinado componente HTML
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 22/07/2008
 */

class FactoryCompHtml {

	private $objFactored;
	private $strClassAtual;

	private $strTipo;
	private $strCategoria;

	private $prefixo = "";
	private $sufixo = "";

	private $idReferencia;

	public function __construct() {

	}

	/**
	 * Método para criação dos componentes do formulário
	 * Chamado na Classe: ControlForms método: regFormValues
	 *
	 * @author André Coura
	 * @since 1.0 - 25/01/2008
	 */
	public function buildComp($strComp, $value = "") {
		try {
			self::validaAttr($strComp);
		} catch (HtmlCompsException $e) {
			die($e->__toString());
		}
		$strClass = self::verificaComp((string) $strComp->attributes()->type);
		require_once ($strClass . ".class.php");
		$this->objFactored = new $strClass ($strComp);
		$this->objFactored->setIdReferencia(self::getIdReferencia());
		$this->objFactored->setParam1(self::getParamWhere1());
		if (self::getPrefixo() != "")
			$this->objFactored->setPrefixo(self::getPrefixo());
		if (self::getSufixo() != "")
			$this->objFactored->setSufixo(self::getSufixo());
		$this->objFactored->setTipo(self::getTipo());
		$this->objFactored->setCategoria(self::getCategoria());
		$this->objFactored->setClass($this->strClassAtual);
		$this->objFactored->getComponente($value);
	}

	public function getHtmlComp($sufixo = "") {
		if (self::getObjFactored() == null)
			die("FactoryCompHtml: Componente ainda não foi instanciado.");
		return self::getObjFactored()->getHtmlComp($sufixo);
	}

	public function getObjFactored() {
		return $this->objFactored;
	}

	public function buildBtn($strComp, $label = "") {
		require_once ("Button.class.php");
		$objBtn = new Button($strComp);
		$objBtn->setTipo(self::getTipo());
		$objBtn->setParam1(self::getParamWhere1());
		$objBtn->setCategoria(self::getCategoria());
		$objBtn->setClass($this->strClassAtual);
		$objBtn->getComponente($label);
	}

	public function setIdReferencia($intIdRef) {
		$this->idReferencia = $intIdRef;
	}

	public function getIdReferencia() {
		return $this->idReferencia;
	}

	private function verificaComp($strComp) {
		$strClass = (string) $strComp;
		if ($strClass == "Text" || $strClass == "text" || $strClass == "hidden" || $strClass == "cep" ||
			$strClass == "cpf" || $strClass == "cnpj" || $strClass == "telefone" || $strClass == "email" ||
			$strClass == "password" || $strClass == "inteiro" || $strClass == "longlat" || $strClass == "hora"
			|| $strClass == "integer" || $strClass == "double" || $strClass == "double" || $strClass == "site")
			return "Input";
		return ucfirst($strClass);
	}

	private $param1;

	public function setParamWhere1($param1 = null) {
		if ($param1 != null)
			$this->param1 = $param1;
	}

	public function getParamWhere1() {
		return $this->param1;
	}

	public function setClasseAtual($strClasse) {
		$this->strClassAtual = $strClasse;
	}

	/**
	 * Método de validação dos atributos passados no XMl
	 *
	 * @author André Coura
	 * @since 1.0 - 23/07/2008
	 */
	public function validaAttr($strComp) {

	}

	public function getTipo() {
		return $this->strTipo;
	}

	public function setTipo($tipo) {
		$this->strTipo = $tipo;
	}

	public function getCategoria() {
		return $this->strCategoria;
	}

	public function setCategoria($cat) {
		$this->strCategoria = $cat;
	}

	public function getSufixo() {
		return $this->sufixo;
	}

	public function getPrefixo() {
		return $this->prefixo;
	}

	public function setSufixo($strSufixo) {
		$this->sufixo = $strSufixo;
	}

	public function setPrefixo($strPrefixo) {
		$this->prefixo = $strPrefixo;
	}

}
?>