<?php
require_once (FWK_CONTROL."ControlXML.class.php");
require_once (FWK_CONTROL."ControlDB.class.php");
require_once (FWK_CONTROL."ControlJS.class.php");
require_once (FWK_CONTROL."ControlCSS.class.php");
require_once (FWK_CONTROL."ControlSmarty.class.php");
require_once (FWK_CONTROL."ControlMensagens.class.php");
require_once (FWK_EXCEPTION."XMLException.class.php");
require_once (FWK_EXCEPTION."GridException.class.php");
require_once (FWK_UTIL."Cryptografia.class.php");
require_once (FWK_UTIL."FormataActions.class.php");
require_once (FWK_COMP."Button.class.php");
require_once (FWK_UTIL."FormataParametros.class.php");
require_once (FWK_UTIL."FormataDatas.class.php");
require_once(FWK_UTIL."FormataLink.class.php");

require_once(FWK_CONTROL."ControlUsuario.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

require_once(FWK_CONTROL."ControlConfiguracoes.class.php");

class ControlGrid {

	private $objXml = null;
	private $objSmarty = null;
	private $objBanco = null;
	private $objCrypt = null;
	private $strTplGrid = null;
	private $strCssGrid = null;
	private $strTituloGrid = null;
	private $utf8Decode;
	//objeto para o singleton
	private static $objCtrlGrid;

	private $objSessao;
	private $objUserSess;

	private $busca = null;
	private $arrFiltros = null;

	private function __construct() {
	}

	protected function getObjUsrSessao(){
		if($this->objUserSess == null){
			$objCtrlSess = new ControlSessao();
			$this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
		}
		return $this->objUserSess;
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

	public function setXmlGrid($xml){
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
		throw new XMLException("Não foi passado para o Grid o XML do mesmo.");
		return $this->objXML;
	}

	public static function getGrid(){
		if (!isset(self::$objCtrlGrid)) {
			$obj = __CLASS__;
			self::$objCtrlGrid = new $obj;
		}
		return self::$objCtrlGrid;
	}

	private function verificaMensagens() {
		$objFormatParam = new FormataParametros();
		$objFormatParam->setParametros(self::getObjCrypt()->decryptData($_GET['c']));
		$arrParams = $objFormatParam->getParametros();
		if (($strMens = $arrParams["msg"])) {
			$objMens = ControlMensagens::getMens();
			$objMens->exibeMens($strMens, "MENS_GRID");
		}
	}

	public function setTplGrid($strTplGrid){
		$this->strTplGrid = $strTplGrid;
	}

	/**
	 * TODO:
	 */
	public function getTplGrid(){
		if(!isset($this->strTplGrid) && $this->strTplGrid == ""){
			switch(self::getObjXml()->attributes()->categoria){
				case "basico":
					$this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaGrids",DEPOSITO_TPLS);
					if(!isset($this->strTplGrid) && $this->strTplGrid == "")
					$this->strTplGrid = FWK_HTML_GRID."gridDre.tpl";
					break;
				case "inventario":
					$this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaModuloGridsInv",DEPOSITO_TPLS);
					if(!isset($this->strTplGrid) && $this->strTplGrid == "")
					$this->strTplGrid = FWK_HTML_GRID."gridDre.tpl";
					break;
				case "formularios":
					$this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaModuloGrids",DEPOSITO_TPLS);
					if(!isset($this->strTplGrid) && $this->strTplGrid == "")
					$this->strTplGrid = FWK_HTML_GRID."gridDre.tpl";
					break;
				default:
					$this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null,"estruturaGrids",DEPOSITO_TPLS);
					if(!isset($this->strTplGrid) && $this->strTplGrid == "")
					$this->strTplGrid = FWK_HTML_GRID."gridDre.tpl";
					break;
			}
		}
		return $this->strTplGrid;
	}

	/**
	 * Método para exibir o grid
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	public function showGrid($pagAtual, $buscaGrid = null, $arrFiltro = null) {
		self::regDadosGrid($pagAtual, $buscaGrid, $arrFiltro);
		$strGrid = self::getObjSmarty()->fetch(self::getTplGrid());
		self::getObjSmarty()->assign("CORPO", $strGrid);
	}

	/**
	 * Fazer depois grid sem XML
	 */
	public function showGridNoXml($pagAtual){
		$strGrid = self::getObjSmarty()->fetch(self::getTplGrid());
		self::getObjSmarty()->assign("CORPO", $strGrid);
	}

	public function setUtf8Decode($booOption) {
		$this->utf8Decode = $booOption;
	}

	public function getUtf8Deecode(){
		return $this->utf8Decode;
	}

	/**
	 * Método para registrar as Tags do grid
	 *
	 * @author André Coura
	 * @author Matheus Vieira
	 * @since 1.1 - 10/08/2011
	 */
	private function regDadosGrid($pagAtual, $buscaGrid, $arrFiltro=null) {
		$this->busca = ($_POST["buscaGrid"])?addslashes($_POST["buscaGrid"]):$buscaGrid;
		self::trataFiltro($arrFiltro);
		self::registraCss();
		self::registraJs();
		self::getTituloGrid();
		self::regDadosPaginacao($pagAtual);
		self::regBtnsGrid();
		self::getObjSmarty()->assign("ARR_TITULOS", self::getColTitulos());
		$arrDadosGrid = self::getDadosDb(self::getInicioPaginacao($pagAtual));
		foreach ($arrDadosGrid as $key => $array){
			foreach ($array as $key2 => $valor){
				$arrDadosGrid[$key][$key2] = str_replace("&", "&amp;", $valor);
			}
		}
		self::getObjSmarty()->assign("NUM_DADOS_INI", count($arrDadosGrid) > 0 ? "TRUE" : "FALSE");
		self::getObjSmarty()->assign("ARR_DADOS", self::verTipoDados(self::doFieldFormat($arrDadosGrid)));
		self::getObjSmarty()->assign("LINK_BUSCAR", self::makeLinkPag(""));
		self::getObjSmarty()->assign("VALOR_BUSCA", $_POST["buscaGrid"]);
		self::getObjSmarty()->assign("URL_MOMENTO", self::makeLinkPag(""));
	}

	private function doFieldFormat($arrDadosGrid) {
		for ($i = 0; $i < count($arrDadosGrid); $i++) {
			if (self::getObjXml()->header || self::getObjXml()->header != "") {
				if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
					$cont = 0;
					foreach (self::getObjXml()->header->titulo as $titulo) {
						//verifica o formato do campo
						$arrDadosGrid[$i][$cont] = self::getFormatField((string)$titulo->attributes()->type,
						$arrDadosGrid[$i][$cont],(string)$titulo->attributes()->maxlenght);
						//verifica o Encoding do texto
						$arrDadosGrid[$i][$cont] = self::verificaEncoding($arrDadosGrid[$i][$cont], (string)$titulo->attributes()->encoding);
						$cont++;
					}
				}
			}
		}
		return $arrDadosGrid;
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
						$arrRetorno[$i][$j] = utf8_encode($arrDados[$i][$j]);
					}
				}
			}
			return $arrRetorno;
		} else {
			return $arrDados;
		}
	}

	/**
	 * Método para exibir, caso exista mais de uma pagina em paginação o grid
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function regDadosPaginacao($pagAtual) {
		self::getObjSmarty()->assign("NUMPAGS_GRID", self::numPags());
		self::getObjSmarty()->assign("NUMREGS_GRID", self::numRegs());
		self::getObjSmarty()->assign("PAGATUAL_GRID", ($pagAtual +1));
		self::getObjSmarty()->assign("PAGINACAO", (self::numPags() > 1) ? true : false);
		self::getObjSmarty()->assign("LINK_PRIMEIRAPAG", self::makeLinkPag("&p=0"));
		self::getObjSmarty()->assign("LINK_PAGANTERIOR", self::makeLinkPag("&p=" . ($pagAtual -1)));
		self::getObjSmarty()->assign("LINK_PROXIMAPAG", self::makeLinkPag("&p=" . ($pagAtual +1)));
		self::getObjSmarty()->assign("LINK_ULTIMAPAG", self::makeLinkPag("&p=" . (self::numPags() - 1)));
		$strPaginacao = self::getObjSmarty()->fetch(FWK_HTML_GRID . "paginacaoGrid.tpl");
		self::getObjSmarty()->assign("PAGINACAO_GRID", $strPaginacao);
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
		$objFormatParam = new FormataParametros();
		$objFormatParam->setParametros($_GET);
		$get = $objFormatParam->getParametros();
//		self::debuga($get);
		if(isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo!=""){
			$tipo = FormataLink::definiTipoLink((string)self::getObjXml()->attributes()->tipo);
		}
		if(isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria!=""){
			$categoria = (string)self::getObjXml()->attributes()->categoria;
		}
		if(isset($this->busca) && trim((string)self::getObjXml()->query->whereBusca) != ""){
			$mantemBusca = "&buscaGrid=".$this->busca;
		}
		if($this->arrFiltros[0] != ""){
			$filtros = "&filtros=";
			for($fi=0; $fi<count($this->arrFiltros); $fi++){
				$filtros .= str_replace(" = ", ":", $this->arrFiltros[$fi]).(($fi<count($this->arrFiltros)-1)?"/":"");
			}
		}

		return  "?".$tipo."=".self::getObjCrypt()->cryptData(($categoria!=""?$categoria."&f=":"").self::getClassGrid()."&".((string)self::getObjXml()->attributes()->actionBusca?(string)self::getObjXml()->attributes()->actionBusca:"a=lista") . ($get["id"]!=""?"&id=".$get["id"]:"") . ($mantemBusca!=""?$mantemBusca:"") . ($filtros!=""?$filtros:""). $params);
		//return "?c=" . self::getObjCrypt()->cryptData(self::getClassGrid() . "&a=lista" . $params);
	}

	/**
	 * Método que busca no XML o nome da classe referida para ser adicionada na paginação
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getClassGrid() {
		if (!self::getObjXml()->attributes()->classe || self::getObjXml()->attributes()->classe == "")
		throw new GridException("XML Inválido, faltando campo \"classe\" referente ao nome da classe do XML.");
		return self::getObjXml()->attributes()->classe;
	}

	/**
	 * Método para verificar qtos registro tem o grid
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function numRegs() {
		$arrConsulta = self::getObjBanco()->GetAll(self::getQuery());
		return count($arrConsulta);
	}

	/**
	 * Método para verificar qtos páginas tem o grid
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
	 * Registro do CSS referente ao grid
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function registraCss() {
		$objCtrlCss = ControlCSS::getCSS();
		$objCtrlCss->addCss(self::getCssGrid());
	}

	public function setCssGrid($strCssGrid){
		$this->strCssGrid = $strCssGrid;
	}

	public function getCssGrid(){
		return $this->strCssGrid;
	}

	/**
	 * Registro do JSs referente ao grid
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function registraJs() {
		$objCtrlCss = ControlJS::getJS();
		$objCtrlCss->addJs(FWK_JS . "MochiKit/MochiKit.js");
		$objCtrlCss->addJs(FWK_JS . "actionsGrid.js");
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
		$strParam2 = "";
		$strValParam = "";
		$strValParam2 = "";
		if(isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo!=""){
			$tipo = FormataLink::definiTipoLink((string)self::getObjXml()->attributes()->tipo);
		}
		if(isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria!=""){
			$categoria = (string)self::getObjXml()->attributes()->categoria;
		}
		if(isset(self::getObjXml()->attributes()->param1) && self::getObjXml()->attributes()->param1 != "")
		$strParam = self::getObjXml()->attributes()->param1;
		if(isset(self::getObjXml()->attributes()->param2) && self::getObjXml()->attributes()->param2 != "")
			$strParam2 = self::getObjXml()->attributes()->param2;
		if(self::getVariavelWhere1() !="")
		$strValParam = self::getVariavelWhere1();
		if(self::getVariavelWhere2() !="")
			$strValParam2 = self::getVariavelWhere2();

		if (self::getObjXml()->header || self::getObjXml()->header != "") {
			if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
				foreach (self::getObjXml()->header->titulo as $actions) {
					foreach ($actions->attributes() as $attributes => $value) {
						switch ((string) $attributes) {
							case "actionEdit" :
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' class='iconEditar' title='Editar' alt='Editar' src='".URL_IMAGENS."icons/page_white_edit.png' />",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "actionDelete" :
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridConfirm($data, $value, self::getClassGrid(), "<img width='14' title='Deletar' alt='Deletar' src='".URL_IMAGENS."icons/page_white_delete.png' />", "Tem certeza que gostaria de deletar este registro?",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "actionStatus":
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Status' alt='Status' src='".URL_IMAGENS."icons/page_white_star.png' />",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "actionSelect":
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Selecionar' alt='Selecionar' src='".URL_IMAGENS."icons/page_white_magnify.png' />",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "actionReport":
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridImprime($data, $value, self::getClassGrid(), "<img width='14' title='Imprimir' alt='Imprimir' src='".URL_IMAGENS."icons/printer.png' />",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "actionPdf":
								if ($index == $cont) {
									$newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Download PDF' alt='Download PDF' src='".URL_IMAGENS."icons/page_white_acrobat.png' />",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
								}
								break;
							case "permsDel" :
								/*
								 * Como proceder aqui:
								 * permissão por direito: permsDel="d:168|a=deletar";
								 * permissão por grupo: permsDel="g:1,2|a=deletar";
								 *
								 * @author Matheus Vieira
								 * @since 1.0 - 10/11/2011
								 *
								 */
								$objCtrlSess = new ControlSessao();
								$objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
								$arrGruposUsr = $objUsuario->getGrupoUsuario();
								$arrDireitosUsr = $objUsuario->getDireitosUsuario();
								$permissao = false;

								$parametros = explode(":", (string)$value);
								$tipoPermissao = array_shift($parametros);

								$arrGridPerms = explode("|", $parametros[0]);
								$value = array_pop($arrGridPerms);
								$arrGrupoPerms = explode(",", $arrGrupoPerms[0]);


								if($tipoPermissao == "d"){
									foreach ($arrDireitosUsr as $direitoUsr){
										if(in_array($direitoUsr, $arrGridPerms))
											$permissao = true;
									}
								}elseif($tipoPermissao == "g"){
									foreach ($arrGruposUsr as $grupoUsr){
										if(in_array($grupoUsr, $arrGridPerms))
											$permissao = true;
									}
								}


								if ($index == $cont && $permissao == true) {
									$newData .= " " . $objForAction->gridConfirm($data, $value, self::getClassGrid(), "<img width='14' title='Deletar' alt='Deletar' src='".URL_IMAGENS."icons/page_white_delete.png' />", "Tem certeza que gostaria de deletar este registro?",$tipo,$categoria,$strParam,$strValParam, $strParam2,$strValParam2);
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
		$strQuery .= " WHERE 1 = 1 ";
		if (trim((string)self::getObjXml()->query->where) != "") {
			$strQuery .= " AND ";
			$string = trim((string)self::getObjXml()->query->where);
			$strQuery .= str_replace("#idReferencia#", self::getIdReferencia(),$string);
			if(self::getVariavelWhere1() != "")
				$strQuery .= " ".self::getVariavelWhere1()." ";
		}
		if (trim((string)self::getObjXml()->query->whereCondicao) != "") {
			if(self::getVariavelWhere2() != ""){
				$strQuery .= " AND ";
				$strQuery .= trim((string)self::getObjXml()->query->whereCondicao);
				$strQuery .= " ".self::getVariavelWhere2()." ";
			}
		}

		$idUsuario = self::getVariavelUsuario();

		if (trim((string)self::getObjXml()->query->whereBusca) != "") {
			if($this->busca != ""){
				$strQuery .= " AND ";
				$arrBusca = $this->busca;
				$arrBusca = explode('\"', $arrBusca);
				if(count($arrBusca) <= 1)
					$arrBusca = explode(" ", $this->busca);
				$arrBusca = FormataPost::limpaArray($arrBusca);
				sort($arrBusca);
				if(count($arrBusca)>0){
					for($i=0; $i<count($arrBusca); $i++){
						$strQuery .= ($i == 0)?"(":"";

						$strQuery .= str_replace("#BUSCA#", utf8_decode($arrBusca[$i]), trim((string)self::getObjXml()->query->whereBusca));

						$strQuery .= ($i != count($arrBusca)-1)?" OR ":"";
						$strQuery .= ($i == count($arrBusca)-1)?")":"";
					}
				}
			}
		}

		if($idUsuario)
			if (trim((string)self::getObjXml()->query->whereUsuario) != "") {
				$strQuery .= " AND ";
				$strQuery .= trim((string)self::getObjXml()->query->whereUsuario);
				$strQuery .= " ".$idUsuario." ";
			}
		if ($this->arrFiltros[0] != "") {
			$strQuery .= " AND ";
			for($fi=0; $fi<count($this->arrFiltros); $fi++){
				$strQuery .= $this->arrFiltros[$fi];
				$strQuery .= ($fi != count($this->arrFiltros)-1)?" AND ":"";
			}
		}
		if (self::getCtrlConfiguracoes()->getIdPortal()){
			$portal = (string)self::getObjXml()->attributes()->portal;
			if(strtolower((string)self::getObjXml()->attributes()->portal) == "true"){
				$strQuery .= " AND ";
				$strQuery .= trim((string)self::getObjXml()->query->campoPortal)." = ".self::getCtrlConfiguracoes()->getIdPortal();
			}
		}
		if (trim((string)self::getObjXml()->query->groupBy) != "") {
			$strQuery .= " GROUP BY ";
			$strQuery .= trim((string)self::getObjXml()->query->groupBy);
		}
		if (trim((string)self::getObjXml()->query->orderBy) != "") {
			$strQuery .= " ORDER BY ";
			$strQuery .= trim((string)self::getObjXml()->query->orderBy);
		}

//		self::debuga($strQuery);
		//trata valores especiais query

		return $strQuery;
	}

	private $variavelGridWhere1;

	public function setVariavelWhere1($varGrid){
		$this->variavelGridWhere1 = $varGrid;
	}

	public function getVariavelWhere1(){
		return $this->variavelGridWhere1;

	}

	private $variavelGridWhere2;

	public function setVariavelWhere2($varGrid){
		$this->variavelGridWhere2 = $varGrid;
	}

	public function getVariavelWhere2(){
		return $this->variavelGridWhere2;

	}

	private $variavelUsuario;

	public function setVariavelUsuario($varGrid){
		$this->variavelUsuario = $varGrid;
	}

	public function getVariavelUsuario(){
		return $this->variavelUsuario;

	}

	private function getTablesQuery() {
		return self::getObjXml()->query->from;
	}

	/**
	 * Método para setar o início da paginação da consulta SQL do grid
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
	 * Método para validação da estrutura do XML para a criação do grid
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function validaEstrutura() {
		try {
			self::verificaQuery();
			self::verificaFrom();
			self::getColTitulos();
			self::getClassGrid();
		} catch (GridException $e) {
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
		throw new GridException("XML Inválido, faltando campo \"query\" para a criação da consulta");
	}

	/**
	 * Método de verificação do parametro FROM do XML
	 *
	 * @author André Coura
	 * @since 1.0 - 09/07/2008
	 */
	private function verificaFrom() {
		if (!self::getObjXml()->query->from || self::getObjXml()->query->from == "")
		throw new GridException("XML Inválido, faltando campo \"from\" para a criação da consulta");
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
					if($titulo->attributes()->type != "QUERY"){
						$arrTitulos[] = array(self::getOrdenacao($titulo->attributes()->type), (string) $titulo, 'class' => (string)$titulo->attributes()->class);
					}else{
						$arrDados = self::getObjBanco()->getAll(self::getTitleQuery($titulo->query));
						for($i=0; $i<count($arrDados); $i++){
							$arrDados[$i][3] = self::getObjCrypt()->cryptData((string)$titulo->valor.":".$arrDados[$i][0]);
							for($fi=0; $fi<count($this->arrFiltros); $fi++){
								if(end(explode(" = ",$this->arrFiltros[$fi])) == $arrDados[$i][0])
									$arrDados[$i][4] = "selected=selected";
							}
						}
						$arrTitulos[] = array(
						"select", (string)$titulo->text, (string)$titulo->valor,
						(string)$titulo->todos,	$arrDados, self::getObjCrypt()->cryptData((string)$titulo->valor.":vazio"),
						'class' => (string)$titulo->attributes()->class);
					}
				}
			}
		} else {

			self::getTitulosDb();
		}
		//if (count($arrTitulos) != count(self::getTitulosDb())){
		//	return self::getTitulosDb();
		//}

//		self::debuga($arrTitulos);
		return $arrTitulos;
	}

	/**
	 * Método para retornar a query do titulo
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 17/08/2011
	 */
	private function getTitleQuery($query) {
		$strQuery = "SELECT DISTINCT";
		$strQuery .= $query->campos;
		$strQuery .= " FROM ";
		$strQuery .= $query->from;
		if (trim((string)$query->where) != "") {
			$strQuery .= " WHERE ";
			$strQuery .= trim((string)$query->where);
		}
		if (trim((string)$query->orderBy) != "") {
			$strQuery .= " ORDER BY ";
			$strQuery .= trim((string)$query->orderBy);
		}
		return $strQuery;
	}

	private function trataFiltro($arrFiltros = null){
		$arrWheres = array();
		$arrFiltro1 = array();
		if($arrFiltros[1] != ""){
			$arrFiltrosVindo = explode("/", $arrFiltros[1]);
			for($i=0; $i<count($arrFiltrosVindo); $i++){
				$filtroNovo = explode(":", $arrFiltrosVindo[$i]);
				$arrFiltro1[$filtroNovo[0]] = $filtroNovo[1];
			}
		}
		if($arrFiltros[0] != ""){
			$filtroNovo = explode(":", $arrFiltros[0]);
			$arrFiltro1[$filtroNovo[0]] = $filtroNovo[1];
			if($filtroNovo[1] == "vazio")
			unset($arrFiltro1[$filtroNovo[0]]);
		}
		foreach ($arrFiltro1 as $where => $valor){
			$arrWheres[] = $where." = ".$valor;
		}
		$this->arrFiltros = $arrWheres;
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
	 * Método para buscar o título do grid no XML
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function getTituloGrid() {
		if (!$this->strTituloGrid)
		self::getObjSmarty()->assign("TITULO_GRID", self::getObjXml()->attributes());
	}

	/**
	 * Método para setar o título do Grid via código
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	public function setTituloGrid($strTit) {
		$this->strTituloGrid = $strTit;
	}

	/**
	 * Método para gerar os botoes do grid a partir do XML
	 *
	 * @author André Coura
	 * @since 1.0 - 13/07/2008
	 */
	private function regBtnsGrid() {
		$objFactoryComps = new FactoryCompHtml();
		$strCampos = "";

		foreach (self::getObjXml()->buttons->campos as $botoes) {
			$objFactoryComps->setClasseAtual(self::getObjXml()->attributes()->classe);
			$objFactoryComps->setParamWhere1(self::getVariavelWhere1());
			$objFactoryComps->setParamWhere2(self::getVariavelWhere2());
			$objFactoryComps->setTipo(self::getObjXml()->attributes()->tipo);
			$objFactoryComps->setCategoria(self::getObjXml()->attributes()->categoria);
			$objFactoryComps->buildComp($botoes);
			$strCampos .= $objFactoryComps->getObjFactored()->getHtmlComp();
		}
		self::getObjSmarty()->assign("BUTTONS_GRID", $strCampos);
	}

	private function getCtrlConfiguracoes() {
		if ($this->objCtrlConfiguracoes == null)
		$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}

	private function getIdUsrSessao(){
		return self::getObjUsrSessao()->getIdUsuario();
	}

	public function setIdReferencia($intIdRef){
		$this->idReferencia = $intIdRef;
	}

	public function getIdReferencia(){
		return $this->idReferencia;
	}


	public function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print_r($arrDados[$i]);
			print"<br />";
		}
		die();
	}
}
?>