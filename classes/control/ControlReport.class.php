<?php
require_once (FWK_CONTROL."ControlXML.class.php");
require_once (FWK_CONTROL."ControlDB.class.php");
require_once (FWK_CONTROL."ControlJS.class.php");
require_once (FWK_CONTROL."ControlCSS.class.php");
require_once (FWK_CONTROL."ControlSmarty.class.php");
require_once (FWK_CONTROL."ControlMensagens.class.php");
require_once (FWK_EXCEPTION."XMLException.class.php");
require_once (FWK_EXCEPTION."ReportException.class.php");
require_once (FWK_UTIL."Cryptografia.class.php");
require_once (FWK_UTIL."FormataActions.class.php");
require_once (FWK_COMP."Button.class.php");
require_once (FWK_UTIL."FormataParametros.class.php");
require_once (FWK_UTIL."FormataDatas.class.php");
require_once(FWK_UTIL."FormataLink.class.php");

require_once(FWK_CONTROL."ControlConfiguracoes.class.php");

class ControlReport {

	private $objXml = null;
	private $objSmarty = null;
	private $objBanco = null;
	private $objCrypt = null;
	private $strTplReport = null;
	private $strCssReport = null;
	private $strTituloReport = null;
	private $utf8Decode;
	//objeto para o singleton
	private static $objCtrlReport;

	private function __construct() {
	}

	protected function getObjBanco(){
		if($this->objBanco == null)
			$this->objBanco = ControlDB::getBanco();
		return $this->objBanco;
	}

	protected function getObjSmarty(){
		if($this->objSmarty == null)
			$this->objSmarty = ControlSmarty :: getSmarty();
		return $this->objSmarty;
	}

	protected function getObjCrypt(){
		if($this->objCrypt == null)
			$this->objCrypt = new Cryptografia();
		return $this->objCrypt;
	}

	public function setXmlReport($xml){
		try {
			$objCtrlXml = new ControlXML($xml);
			$this->objXML = $objCtrlXml->getXML();
		} catch (XMLException $e) {
			die($e->__toString());
		}
		self::validaEstrutura();
		self::verificaMensagens();
	}

	protected function getObjXml(){
		if($this->objXML == null)
			throw new XMLException("Não foi passado para o Report o XML do mesmo.");
		return $this->objXML;
	}

	public static function getReport(){
		if (!isset(self::$objCtrlReport)) {
            $obj = __CLASS__;
            self::$objCtrlReport = new $obj;
        }
        return self::$objCtrlReport;
	}

	private function verificaMensagens() {
		$objFormatParam = new FormataParametros();
		$objFormatParam->setParametros(self::getObjCrypt()->decryptData($_GET['c']));
		$arrParams = $objFormatParam->getParametros();
		if (($strMens = $arrParams["msg"])) {
			$objMens = ControlMensagens::getMens();
			$objMens->exibeMens($strMens, "MENS_REPORT");
		}
	}

	public function setTplReport($strTplReport){
		$this->strTplReport = $strTplReport;
	}

	/**
	 * TODO:
	 */
	public function getTplReport(){
		if(!isset($this->strTplReport) && $this->strTplReport == ""){
			switch(self::getObjXml()->attributes()->categoria){
				case "basico":
					$this->strTplReport = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaReports",DEPOSITO_TPLS);
					if(!isset($this->strTplReport) && $this->strTplReport == "")
						$this->strTplReport = FWK_HTML_REPORT."reportDre.tpl";
					break;
				case "inventario":
					$this->strTplReport = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaModuloReportsInv",DEPOSITO_TPLS);
					if(!isset($this->strTplReport) && $this->strTplReport == "")
						$this->strTplReport = FWK_HTML_REPORT."reportDre.tpl";
					break;
				case "formularios":
					$this->strTplReport = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaModuloReports",DEPOSITO_TPLS);
					if(!isset($this->strTplReport) && $this->strTplReport == "")
						$this->strTplReport = FWK_HTML_REPORT."reportDre.tpl";
					break;
				default:
					$this->strTplReport = FWK_HTML_REPORT."reportDre.tpl";
					break;
			}
		}
		return $this->strTplReport;
	}

	/**
	 * Método para exibir o report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	public function showReport($pagAtual) {
		self::regDadosReport($pagAtual);
		$strReport = self::getObjSmarty()->fetch(self::getTplReport());
		self::getObjSmarty()->assign("CORPO", $strReport);
	}

	/**
	 * Fazer depois report sem XML
	 */
	public function showReportNoXml($pagAtual){


		$strReport = self::getObjSmarty()->fetch(self::getTplReport());
		self::getObjSmarty()->assign("CORPO", $strReport);
	}

	public function setUtf8Decode($booOption) {
		$this->utf8Decode = $booOption;
	}

	public function getUtf8Deecode(){
		return $this->utf8Decode;
	}

	/**
	 * Método para registrar as Tags do report
	 *
	 * @author André Coura
	 * @since 1.0 - 10/07/2008
	 */
	private function regDadosReport($pagAtual) {
		self::registraCss();
		self::registraJs();
		self::getTituloReport();
		self::regDadosPaginacao($pagAtual);
		self::regBtnsReport();
		self::getObjSmarty()->assign("ARR_TITULOS", self::getColTitulos());
		$arrDadosReport = self::getDadosDb(self::getInicioPaginacao($pagAtual));
		self::getObjSmarty()->assign("NUM_DADOS_INI", count($arrDadosReport) > 0 ? "TRUE" : "FALSE");
		self::getObjSmarty()->assign("ARR_DADOS", self::verTipoDados(self::doFieldFormat($arrDadosReport)));
	}

	private function doFieldFormat($arrDadosReport) {
		for ($i = 0; $i < count($arrDadosReport); $i++) {
			if (self::getObjXml()->header || self::getObjXml()->header != "") {
				if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
					$cont = 0;
					foreach (self::getObjXml()->header->titulo as $titulo) {
						//verifica o formato do campo
						$arrDadosReport[$i][$cont] = self::getFormatField((string)$titulo->attributes()->type,
						$arrDadosReport[$i][$cont],(string)$titulo->attributes()->maxlenght);
						//verifica o Encoding do texto
						$arrDadosReport[$i][$cont] = self::verificaEncoding($arrDadosReport[$i][$cont], (string)$titulo->attributes()->encoding);
						$cont++;
					}
				}
			}
		}
		return $arrDadosReport;
	}

	private function verificaEncoding($valor, $encoding){
		switch($encoding){
			case "UFT8":
				return utf8_decode($valor);
				break;
			default:
				return $valor;
		}
	}

	private function getFormatField($tipoCampo, $valor,$maxLength="") {
		switch ($tipoCampo) {
			case "DATE" :
			case "date" :
			case "date_br" :
			case "DATE_BR" :
				return FormataDatas::parseDataBR($valor);
			case "date_us" :
			case "DATE_US" :
				return FormataDatas::parseDataUSA($valor);
			case "STRING"||"string":
				if(isset($maxLength) && $maxLength != ""){
					$intMaxlength = (int)$maxLength;
					if($intMaxlength > 0 && strlen($valor) > $intMaxlength)
						return  substr($valor, 0, $intMaxlength)."...";
				}
			default :
				return $valor;
		}
	}

	private function verTipoDados($arrDados) {
		if (self::getUtf8Deecode()) {
			$arrRetorno = array ();
			if (count($arrDados) > 0) {
				for ($i = 0; $i < count($arrDados); $i++) {
					$arrRetorno[$i] = array ();
					for ($j = 0; $j < count($arrDados[$i]); $j++) {
						$arrRetorno[$i][$j] = $arrDados[$i][$j];
					}
				}
			}
			return $arrRetorno;
		} else {
			return $arrDados;
		}
	}

	/**
	 * Método para exibir, caso exista mais de uma pagina em paginação o report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function regDadosPaginacao($pagAtual) {
		self::getObjSmarty()->assign("NUMPAGS_REPORT", self::numPags());
		self::getObjSmarty()->assign("NUMREGS_REPORT", self::numRegs());
		self::getObjSmarty()->assign("PAGATUAL_REPORT", ($pagAtual +1));
		self::getObjSmarty()->assign("PAGINACAO", (self::numPags() > 1) ? true : false);
		self::getObjSmarty()->assign("LINK_PRIMEIRAPAG", self::makeLinkPag("&p=0"));
		self::getObjSmarty()->assign("LINK_PAGANTERIOR", self::makeLinkPag("&p=" . ($pagAtual -1)));
		self::getObjSmarty()->assign("LINK_PROXIMAPAG", self::makeLinkPag("&p=" . ($pagAtual +1)));
		self::getObjSmarty()->assign("LINK_ULTIMAPAG", self::makeLinkPag("&p=" . (self::numPags() - 1)));
		$strPaginacao = self::getObjSmarty()->fetch(FWK_HTML_REPORT . "paginacaoReport.tpl");
		self::getObjSmarty()->assign("PAGINACAO_REPORT", $strPaginacao);
	}

	/**
	 * Método para criar os links da paginação
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function makeLinkPag($params) {
		$tipo = "c";
		$categoria = "";
		if(isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo!=""){
			$tipo = FormataLink::definiTipoLink((string)self::getObjXml()->attributes()->tipo);
		}
		if(isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria!=""){
			$categoria = (string)self::getObjXml()->attributes()->categoria;
		}
		return  "?".$tipo."=".self::getObjCrypt()->cryptData(($categoria!=""?$categoria."&f=":"").self::getClassReport()."&a=lista" . $params);
		//return "?c=" . self::getObjCrypt()->cryptData(self::getClassReport() . "&a=lista" . $params);
	}

	/**
	 * Método que busca no XML o nome da classe referida para ser adicionada na paginação
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getClassReport() {
		if (!self::getObjXml()->attributes()->classe || self::getObjXml()->attributes()->classe == "")
			throw new ReportException("XML Inválido, faltando campo \"classe\" referente ao nome da classe do XML.");
		return self::getObjXml()->attributes()->classe;
	}

	/**
	 * Método para verificar qtos registro tem o report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function numRegs() {
		$arrConsulta = self::getObjBanco()->GetAll(self::getQuery());
		return count($arrConsulta);
	}

	/**
	 * Método para verificar qtos páginas tem o report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function numPags() {
		$numRegs = self::numRegs();
		$numPags = $numRegs / FWK_LIMIT_PAGINACAO;
		if ($numPags != (int) $numPags)
			$numPags++;
		return (int) $numPags;
	}

	/**
	 * Busca o início da paginação
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getInicioPaginacao($pagAtual) {
		$limite = $pagAtual * FWK_LIMIT_PAGINACAO;
		return $limite;
	}

	/**
	 * Registro do CSS referente ao report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function registraCss() {
		$objCtrlCss = ControlCSS::getCSS();
		$objCtrlCss->addCss(self::getCssReport());
	}

	public function setCssReport($strCssReport){
		$this->strCssReport = $strCssReport;
	}

	public function getCssReport(){
		return $this->strCssReport;
	}

	/**
	 * Registro do JSs referente ao report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function registraJs() {
		$objCtrlCss = ControlJS::getJS();
		$objCtrlCss->addJs(FWK_JS . "MochiKit/MochiKit.js");
		$objCtrlCss->addJs(FWK_JS . "actionsReport.js");
	}

	/**
	 * Método para buscar os dados do DB e gerar a paginação necessária
	 *
	 * @author André Coura
	 * @since 1.0 - 10/07/2008
	 */
	private function getDadosDb($inicio) {
		self::getObjBanco()->SetFetchMode(ADODB_FETCH_NUM);
		$arrDados = self::getObjBanco()->GetAll(self::getQueryPag($inicio));
		$arrData = self::verificaCampo($arrDados);
		return $arrData;
	}

	private function verificaCampo($arrData) {
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				for ($a = 0; $a < count($arrData[$i]); $a++) {
					$arrData[$i][$a] = self::verificaIndex($a, $arrData[$i][$a]);
				}
			}
		}
		return $arrData;
	}

	private function verificaIndex($index, $data) {
		$arrTitulos = array ();
		$objForAction = new FormataActions();
		$newData = "";
		$return = false;
		$cont = 0;
		$tipo = "c";
		$categoria = "";
		$strParam = "";
		$strValParam = "";
		if(isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo!=""){
			$tipo = FormataLink::definiTipoLink((string)self::getObjXml()->attributes()->tipo);
		}
		if(isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria!=""){
			$categoria = (string)self::getObjXml()->attributes()->categoria;
		}
		if(isset(self::getObjXml()->attributes()->param1) && self::getObjXml()->attributes()->param1 != "")
			$strParam = self::getObjXml()->attributes()->param1;
		if(self::getVariavelWhere1() !="")
			$strValParam = self::getVariavelWhere1();
		if (self::getObjXml()->header || self::getObjXml()->header != "") {
			if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
				foreach (self::getObjXml()->header->titulo as $actions) {
					foreach ($actions->attributes() as $attributes => $value) {
						switch ((string) $attributes) {
							case "actionEdit" :
								if ($index == $cont) {
									$newData .= " " . $objForAction->reportAction($data, $value, self::getClassReport(), "Editar",$tipo,$categoria,$strParam,$strValParam);
								}
								break;
							case "actionDelete" :
								if ($index == $cont) {
									$newData .= " " . $objForAction->reportConfirm($data, $value, self::getClassReport(), "Deletar", "Tem certeza que gostaria de deletar este registro?",$tipo,$categoria,$strParam,$strValParam);
								}
								break;
							case "actionStatus":
								if ($index == $cont) {
									$newData .= " " . $objForAction->reportAction($data, $value, self::getClassReport(), "Status",$tipo,$categoria,$strParam,$strValParam);
								}
								break;
							case "actionSelect":

								if ($index == $cont) {
									$newData .= " " . $objForAction->reportAction($data, $value, self::getClassReport(), "Selecionar",$tipo,$categoria,$strParam,$strValParam);
								}
								break;
							default :
								break;
						}
					}
					$cont++;
				}
			}
		}
		if ($newData != "")
			return $newData;
		return $data;
	}

	/**
	 * Método para retornar a query passada pelo xml do construtor
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function getQuery() {
		$strQuery = "SELECT ";
		$strQuery .= self::getObjXml()->query->campos;
		$strQuery .= " FROM ";
		$strQuery .= self::getTablesQuery();
		if (trim((string)self::getObjXml()->query->where) != "") {
			$strQuery .= " WHERE ";
			$strQuery .= trim((string)self::getObjXml()->query->where);
			if(self::getVariavelWhere1() != "")
				$strQuery .= " ".self::getVariavelWhere1()." ";
		}
		if (trim((string)self::getObjXml()->query->groupBy) != "") {
			$strQuery .= " GROUP BY ";
			$strQuery .= trim((string)self::getObjXml()->query->groupBy);
		}
		if (trim((string)self::getObjXml()->query->orderBy) != "") {
			$strQuery .= " ORDER BY ";
			$strQuery .= trim((string)self::getObjXml()->query->orderBy);
		}
		return $strQuery;
	}

	private $variavelReportWhere1;

	public function setVariavelWhere1($varReport){
		$this->variavelReportWhere1 = $varReport;
	}

	public function getVariavelWhere1(){
		return $this->variavelReportWhere1;

	}

	private function getTablesQuery() {
		return self::getObjXml()->query->from;
	}

	/**
	 * Método para setar o início da paginação da consulta SQL do report
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getQueryPag($inicio) {
		$strQuery = self::getQuery();
		$strQuery .= " LIMIT ".$inicio." , ".FWK_LIMIT_PAGINACAO;
		return $strQuery;
	}

	/**
	 * Método para validação da estrutura do XML para a criação do report
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function validaEstrutura() {
		try {
			self::verificaQuery();
			self::verificaFrom();
			self::getColTitulos();
			self::getClassReport();
		} catch (ReportException $e) {
			die($e->__toString());
		}
	}

	/**
	 * Método de verificação do parametro QUERY do XML
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function verificaQuery() {
		if (!self::getObjXml()->query->campos || self::getObjXml()->query->campos == "")
			throw new ReportException("XML Inválido, faltando campo \"query\" para a criação da consulta");
	}

	/**
	 * Método de verificação do parametro FROM do XML
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function verificaFrom() {
		if (!self::getObjXml()->query->from || self::getObjXml()->query->from == "")
			throw new ReportException("XML Inválido, faltando campo \"from\" para a criação da consulta");
	}

	/**
	 * Verifica a existencia dos Títulos dos campos consultados
	 * caso negativo, busca o nome da coluna mesmo.
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function getColTitulos() {
		$arrTitulos = array ();
		if (self::getObjXml()->header || self::getObjXml()->header != "") {
			if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
				foreach (self::getObjXml()->header->titulo as $titulo) {
					$arrTitulos[] = array (
					self::getOrdenacao($titulo->attributes()->type), (string) $titulo);
				}
			}
		} else {
			self::getTitulosDb();
		}
		if (count($arrTitulos) != count(self::getTitulosDb()))
			return self::getTitulosDb();
		return $arrTitulos;
	}

	/**
	 * Busca o método de ordenação adequado para o campo em questão
	 *
	 * @author André Coura
	 * @since 1.0 - 12/07/2008
	 */
	private function getOrdenacao($strType) {
		switch ((string) $strType) {
			case "DATE" :
			case "date" :
				return "mochi:format='isodate'";
				break;
			case "string" :
			case "STRING" :
				return "mochi:format='istr'";
				break;
			case "none" :
			default :
				return "";
		}
	}

	/**
	 * Busca os títulos dos campos do db
	 *
	 * @author André Coura
	 * @since 1.0 - 10/07/2008
	 */
	private function getTitulosDb() {
		self::getObjBanco()->SetFetchMode(ADODB_FETCH_ASSOC);
		$arrCampos = self::getObjBanco()->GetRow(self::getQuery());
		if (is_array($arrCampos) && count($arrCampos) > 0) {
			foreach ($arrCampos as $titulo => $valor)
				$arrTitulos[] = $titulo;
		}
		return $arrTitulos;
	}

	/**
	 * Método para buscar o título do report no XML
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getTituloReport() {
		if (!$this->strTituloReport)
			self::getObjSmarty()->assign("TITULO_REPORT", self::getObjXml()->attributes());
	}

	/**
	 * Método para setar o título do Report via código
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	public function setTituloReport($strTit) {
		$this->strTituloReport = $strTit;
	}

	/**
	 * Método para gerar os botoes do report a partir do XML
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function regBtnsReport() {
		$objFactoryComps = new FactoryCompHtml();
		$strCampos = "";

		foreach (self::getObjXml()->buttons->campos as $botoes) {
			$objFactoryComps->setClasseAtual(self::getObjXml()->attributes()->classe);
			$objFactoryComps->setParamWhere1(self::getVariavelWhere1());
			$objFactoryComps->setTipo(self::getObjXml()->attributes()->tipo);
			$objFactoryComps->setCategoria(self::getObjXml()->attributes()->categoria);
			$objFactoryComps->buildComp($botoes);
			$strCampos .= $objFactoryComps->getObjFactored()->getHtmlComp();
		}
		self::getObjSmarty()->assign("BUTTONS_REPORT", $strCampos);
	}

	private function getCtrlConfiguracoes() {
		if ($this->objCtrlConfiguracoes == null)
			$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}

}
?>