<?php

require_once (FWK_CONTROL . "ControlXML.class.php");
require_once (FWK_CONTROL . "ControlDB.class.php");
require_once (FWK_CONTROL . "ControlJS.class.php");
require_once (FWK_CONTROL . "ControlCSS.class.php");
require_once (FWK_CONTROL . "ControlSmarty.class.php");
require_once (FWK_CONTROL . "ControlMensagens.class.php");
require_once (FWK_EXCEPTION . "XMLException.class.php");
require_once (FWK_EXCEPTION . "GridException.class.php");
require_once (FWK_UTIL . "Cryptografia.class.php");
require_once (FWK_UTIL . "FormataActions.class.php");
require_once (FWK_COMP . "Button.class.php");
require_once (FWK_UTIL . "FormataParametros.class.php");
require_once (FWK_UTIL . "FormataDatas.class.php");
require_once(FWK_UTIL . "FormataLink.class.php");

require_once(FWK_CONTROL . "ControlUsuario.class.php");
require_once(FWK_CONTROL . "ControlSessao.class.php");
require_once(FWK_CONTROL . "ControlConfiguracoes.class.php");
require_once(FWK_DAO . "MetodosBuscaGridDAO.class.php");

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
    private $post = null;
    private $get = null;
    private $busca = null;
    private $arrFiltros = null;
    private $arrLegenda = null;
    private $dataCont = null;
    private $xmlWhere = null;
    private $xmlParam = null;
    private $dataIni = null;
    private $dataFim = null;

    private function __construct() {
        
    }

    protected function getObjUsrSessao() {
        if ($this->objUserSess == null) {
            $objCtrlSess = new ControlSessao();
            $this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
        }
        return $this->objUserSess;
    }

    protected function getObjBanco() {
        if ($this->objBanco == null)
            $this->objBanco = ControlDB::getBanco();
        return $this->objBanco;
    }

    protected function getObjSmarty() {
        if ($this->objSmarty == null)
            $this->objSmarty = ControlSmarty :: getSmarty();
        return $this->objSmarty;
    }

    protected function getObjCrypt() {
        if ($this->objCrypt == null)
            $this->objCrypt = new Cryptografia();
        return $this->objCrypt;
    }

    public function setXmlGrid($xml) {
        try {
            $objCtrlXml = new ControlXML($xml);
            $this->objXML = $objCtrlXml->getXML();
        } catch (XMLException $e) {
            die($e->__toString());
        }
        self::validaEstrutura();
        self::verificaMensagens();
    }

    protected function getObjXml() {
        if ($this->objXML == null)
            throw new XMLException("Não foi passado para o Grid o XML do mesmo.");
        return $this->objXML;
    }

    public static function getGrid() {
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

    public function setTplGrid($strTplGrid) {
        $this->strTplGrid = $strTplGrid;
    }

    public function setXmlWhere($xmlWhere) {
        $this->xmlWhere = $xmlWhere;
    }

    public function getXmlWhere() {
        return $this->xmlWhere;
    }

    public function setXmlParam($xmlParam) {
        $this->xmlParam = $xmlParam;
    }

    public function getXmlParam() {
        return $this->xmlParam;
    }

    public function setDataIni($x) {
        $this->dataIni = $x;
    }

    public function getDataIni() {
        return $this->dataIni;
    }

    public function setDataFim($x) {
        $this->dataFim = $x;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    /**
     * TODO:
     */
    public function getTplGrid() {
        if (!isset($this->strTplGrid) && $this->strTplGrid == "") {
            switch (self::getObjXml()->attributes()->categoria) {
                case "basico":
                    $this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null, "estruturaGrids", DEPOSITO_TPLS);
                    if (!isset($this->strTplGrid) && $this->strTplGrid == "")
                        $this->strTplGrid = FWK_HTML_GRID . "gridDre.tpl";
                    break;
                case "inventario":
                    $this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null, "estruturaModuloGridsInv", DEPOSITO_TPLS);
                    if (!isset($this->strTplGrid) && $this->strTplGrid == "")
                        $this->strTplGrid = FWK_HTML_GRID . "gridDre.tpl";
                    break;
                case "formularios":
                    $this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null, "estruturaModuloGrids", DEPOSITO_TPLS);
                    if (!isset($this->strTplGrid) && $this->strTplGrid == "")
                        $this->strTplGrid = FWK_HTML_GRID . "gridDre.tpl";
                    break;
                default:
                    $this->strTplGrid = self::getCtrlConfiguracoes()->getCustomTplSis(null, "estruturaGrids", DEPOSITO_TPLS);
                    if (!isset($this->strTplGrid) && $this->strTplGrid == "")
                        $this->strTplGrid = FWK_HTML_GRID . "gridDre.tpl";
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
    public function showGrid() {
        $arrGet = $this->getArrGet();
        self::regDadosGrid((empty($this->get["p"])) ? $arrGet["pag"] : $this->get["p"], (empty($this->get["buscaGrid"])) ? $arrGet["buscaGrid"] : $this->get["buscaGrid"]);
        $strGrid = self::getObjSmarty()->fetch(self::getTplGrid());
        self::getObjSmarty()->assign("CORPO", $strGrid);
    }

    /**
     * Fazer depois grid sem XML
     */
    public function showGridNoXml() {
        $strGrid = self::getObjSmarty()->fetch(self::getTplGrid());
        self::getObjSmarty()->assign("CORPO", $strGrid);
    }

    public function setUtf8Decode($booOption) {
        $this->utf8Decode = $booOption;
    }

    public function getUtf8Deecode() {
        return $this->utf8Decode;
    }

    /**
     * Método para registrar as Tags do grid
     *
     * @author André Coura
     * @author Matheus Vieira
     * @since 1.1 - 10/08/2011
     */
    private function regDadosGrid($pagAtual, $buscaGrid) {
        $this->busca = ($this->post["buscaGrid"]) ? addslashes($this->post["buscaGrid"]) : $buscaGrid;
        self::trataFiltro($arrFiltro);
        self::registraCss();
        self::registraJs();
        self::getTituloGrid();
        self::regFiltroGrid();
        self::regDadosPaginacao($pagAtual);
        self::regBtnsGrid();
        self::regMarcadorGrid();
        self::regInfosGrid();
        self::getObjSmarty()->assign("ARR_TITULOS", self::getColTitulos());
        $arrDadosGrid = self::getDadosDb(self::getInicioPaginacao($pagAtual));
        foreach ($arrDadosGrid as $key => $array) {
            foreach ($array as $key2 => $valor) {
                $arrDadosGrid[$key][$key2] = str_replace("&", "&amp;", $valor);
            }
        }
        self::regLegendas();
        self::getObjSmarty()->assign("NUM_DADOS_INI", count($arrDadosGrid) > 0 ? "TRUE" : "FALSE");
        self::getObjSmarty()->assign("ARR_DADOS", self::verTipoDados(self::doFieldFormat($arrDadosGrid)));
        self::getObjSmarty()->assign("LINK_BUSCAR", self::makeLinkPag("", true));
        self::getObjSmarty()->assign("VALOR_BUSCA", stripslashes($this->busca));
        self::getObjSmarty()->assign("URL_MOMENTO", self::makeLinkPag(""));
    }

    private function regLegendas() {
        if (is_array($this->arrLegenda)) {
            $legendas = null;
            foreach ($this->arrLegenda as $leg) {
                $legendas[] = $leg;
            }
            self::getObjSmarty()->assign("LEGENDA", $legendas);
        }
    }

    private function doFieldFormat($arrDadosGrid) {
        for ($i = 0; $i < count($arrDadosGrid); $i++) {
            if (self::getObjXml()->header || self::getObjXml()->header != "") {
                if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
                    $cont = 0;
                    foreach (self::getObjXml()->header->titulo as $titulo) {
                        //verifica o formato do campo
                        $arrDadosGrid[$i][$cont] = self::getFormatField((string) $titulo->attributes()->type, $arrDadosGrid[$i][$cont], (string) $titulo->attributes()->maxlenght);
                        //verifica o Encoding do texto
                        $arrDadosGrid[$i][$cont] = self::verificaEncoding($arrDadosGrid[$i][$cont], (string) $titulo->attributes()->encoding);
                        $cont++;
                    }
                }
            }
        }
        return $arrDadosGrid;
    }

    private function verificaEncoding($valor, $encoding) {
        switch ($encoding) {
            case "UFT8":
                return utf8_decode($valor);
                break;
            default:
                return $valor;
        }
    }

    private function getFormatField($tipoCampo, $valor, $maxLength = "") {
        switch ($tipoCampo) {
            case "DATE" :
            case "date" :
            case "date_br" :
            case "DATE_BR" :
                return FormataDatas::parseDataBR($valor);
            case "date_us" :
            case "DATE_US" :
                return FormataDatas::parseDataUSA($valor);
            case "STRING" || "string":
                if (isset($maxLength) && $maxLength != "") {
                    $intMaxlength = (int) $maxLength;
                    if ($intMaxlength > 0 && strlen($valor) > $intMaxlength)
                        return substr($valor, 0, $intMaxlength) . "...";
                }
            default :
                return $valor;
        }
    }

    private function verTipoDados($arrDados) {
        if (self::getUtf8Deecode()) {
            $arrRetorno = array();
            if (count($arrDados) > 0) {
                for ($i = 0; $i < count($arrDados); $i++) {
                    $arrRetorno[$i] = array();
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
        self::getObjSmarty()->assign("PAGATUAL_GRID", ($pagAtual + 1));
        self::getObjSmarty()->assign("PAGINACAO", (self::numPags() > 1) ? true : false);
        self::getObjSmarty()->assign("LINK_PRIMEIRAPAG", self::makeLinkPag("&p=0"));
        self::getObjSmarty()->assign("LINK_PAGANTERIOR", self::makeLinkPag("&p=" . ($pagAtual - 1)));
        self::getObjSmarty()->assign("LINK_PROXIMAPAG", self::makeLinkPag("&p=" . ($pagAtual + 1)));
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
    private function makeLinkPag($params, $busca = false) {
        $tipo = "c";
        $categoria = "";
        $objFormatParam = new FormataParametros();
        $objFormatParam->setParametros($_GET);
        $get = $objFormatParam->getParametros();

        if (isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo != "") {
            $tipo = FormataLink::definiTipoLink((string) self::getObjXml()->attributes()->tipo);
        }
        if (isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria != "") {
            $categoria = (string) self::getObjXml()->attributes()->categoria;
        }
        if (isset($this->busca) && trim((string) self::getObjXml()->query->whereBusca) != "") {
            $mantemBusca = "&buscaGrid=" . $this->busca;
        }

        if (isset(self::getObjXml()->filtro) && $this->post && $busca == false) {
            $filtros = "&filtros=" . serialize($this->post);
        }
        if (!empty($this->get["orderBy"])) {
            $orderBy = "&orderBy=" . $this->get["orderBy"];
        }

        if (trim((string) self::getObjXml()->parametroGetA)) {
            return "?" . $tipo . "=" . self::getObjCrypt()->cryptData(($categoria != "" ? $categoria . "&f=" : "") . self::getClassGrid() . "&" . ((string) self::getObjXml()->attributes()->actionBusca ? (string) self::getObjXml()->attributes()->actionBusca : "a=" . trim((string) self::getObjXml()->parametroGetA)) . ($get["id"] != "" ? "&id=" . $get["id"] : "") . ($mantemBusca != "" ? $mantemBusca : "") . ($orderBy != "" ? $orderBy : "") . ($filtros != "" ? $filtros : "") . $params);
        } else {
            return "?" . $tipo . "=" . self::getObjCrypt()->cryptData(($categoria != "" ? $categoria . "&f=" : "") . self::getClassGrid() . "&" . ((string) self::getObjXml()->attributes()->actionBusca ? (string) self::getObjXml()->attributes()->actionBusca : "a=lista") . ($get["id"] != "" ? "&id=" . $get["id"] : "") . ($mantemBusca != "" ? $mantemBusca : "") . ($orderBy != "" ? $orderBy : "") . ($filtros != "" ? $filtros : "") . $params);
        }

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
        if (trim((string) self::getObjXml()->tipoSelect->nome == "Union")) {
            $arrConsulta = self::getObjBanco()->GetAll(self::getQueryUnion());
            $total = count($arrConsulta);
        } else {
            if (trim((string) self::getObjXml()->query->groupBy) != "") {
                $arrConsulta = self::getObjBanco()->GetAll(self::getQuery(true));
                $total = count(FormataPost::colocaValoresEmSequenciaAposUmSelect($arrConsulta));
            } else {
                $arrConsulta = self::getObjBanco()->GetRow(self::getQuery(true));
                $total = $arrConsulta[0];
            }
        }
        return $total;
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

    public function setCssGrid($strCssGrid) {
        $this->strCssGrid = $strCssGrid;
    }

    public function getCssGrid() {
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
        $arrTitulos = array();
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
        if (isset(self::getObjXml()->attributes()->tipo) && self::getObjXml()->attributes()->tipo != "") {
            $tipo = FormataLink::definiTipoLink((string) self::getObjXml()->attributes()->tipo);
        }
        if (isset(self::getObjXml()->attributes()->categoria) && self::getObjXml()->attributes()->categoria != "") {
            $categoria = (string) self::getObjXml()->attributes()->categoria;
        }
        if (isset(self::getObjXml()->attributes()->param1) && self::getObjXml()->attributes()->param1 != "")
            $strParam = self::getObjXml()->attributes()->param1;
        if (isset(self::getObjXml()->attributes()->param2) && self::getObjXml()->attributes()->param2 != "")
            $strParam2 = self::getObjXml()->attributes()->param2;
        if (self::getVariavelWhere1() != "")
            $strValParam = self::getVariavelWhere1();
        if (self::getVariavelWhere2() != "")
            $strValParam2 = self::getVariavelWhere2();
        //self::debuga(self::getXmlParam());
        if (self::getXmlParam()) {
            $xmlParam = self::getXmlParam();
        } else {
            $xmlParam = "";
        }
        if (self::getObjXml()->header || self::getObjXml()->header != "") {
            if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
                foreach (self::getObjXml()->header->titulo as $actions) {
                    foreach ($actions->attributes() as $attributes => $value) {
                        switch ((string) $attributes) {
                            case "actionEdit" :
                                if ($index == $cont) {
                                    $this->arrLegenda["edit"] = array("label" => "Editar", "icone" => "<img width='12' class='iconEditar' title='Editar' alt='Icone Editar' src='" . URL_IMAGENS . "icons/page_white_edit.png' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' class='iconEditar' title='Editar' alt='Editar' src='" . URL_IMAGENS . "icons/page_white_edit.png' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2, $xmlParam);
                                }
                                break;
                            case "actionDelete" :
                                if (self::verificaPermissoesExcluir(self::getIdUsrSessao())) {
                                    if ($index == $cont) {
                                        $this->arrLegenda["delete"] = array("label" => "Deletar", "icone" => "<img width='12' title='Deletar' alt='Icone Deletar' src='" . URL_IMAGENS . "icons/page_white_delete.png' />");
                                        $newData .= " " . $objForAction->gridConfirm($data, $value, self::getClassGrid(), "<img width='14' title='Deletar' alt='Deletar' src='" . URL_IMAGENS . "icons/page_white_delete.png' />", "Tem certeza que gostaria de deletar este registro?", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                    }
                                }
                                break;
                            case "actionStatus":
                                if ($index == $cont) {
                                    $this->arrLegenda["status"] = array("label" => "Status", "icone" => "<img width='12' title='Status' alt='Icone Status' src='" . URL_IMAGENS . "icons/page_white_star.png' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Status' alt='Status' src='" . URL_IMAGENS . "icons/page_white_star.png' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                }
                                break;
                            case "actionSelect":
                                if ($index == $cont) {
                                    $this->arrLegenda["select"] = array("label" => "Selecionar", "icone" => "<img width='12' title='Selecionar' alt='Icone Selecionar' src='" . URL_IMAGENS . "icons/page_white_magnify.png' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Selecionar' alt='Selecionar' src='" . URL_IMAGENS . "icons/page_white_magnify.png' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                }
                                break;
                            case "actionReport":
                                if ($index == $cont) {
                                    $this->arrLegenda["report"] = array("label" => "Imprimir", "icone" => "<img width='12' title='Imprimir' alt='Icone Imprimir' src='" . URL_IMAGENS . "icons/printer.png' />");
                                    $newData .= " " . $objForAction->gridImprime($data, $value, self::getClassGrid(), "<img width='14' title='Imprimir' alt='Imprimir' src='" . URL_IMAGENS . "icons/printer.png' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                }
                                break;
                            case "actionPdf":
                                if ($index == $cont) {
                                    $this->arrLegenda["pdf"] = array("label" => "Download PDF", "icone" => "<img width='12' title='Download PDF' alt='Icone Download PDF' src='" . URL_IMAGENS . "icons/page_white_acrobat.png' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='Download PDF' alt='Download PDF' src='" . URL_IMAGENS . "icons/page_white_acrobat.png' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                }
                                break;
                            case "permsDel" :

                                /*
                                 * Como proceder aqui:
                                 * permissão por direito: a=deleta&amp;tipo=direito&amp;idsPerm=168,169";
                                 * permissão por grupo: a=deleta&amp;tipo=grupo&amp;idsPerm=1,2";
                                 * 
                                 * 		@param a = case do switch interno no Crud.
                                 * 		@param tipo = direito(atribuido por direito) | grupo(permitido por grupo).
                                 * 		@param idsPerm = ids que tem permissão, separados por virgula (id do direito no caso de [$tipo = direito] e id do grupo para [$tipo = grupo]) 
                                 * 				para verificacao com os dados na sessao do usuário.
                                 *
                                 * @author Matheus Vieira
                                 * @since 1.0 - 09/01/2012
                                 *
                                 */

                                $objCtrlSess = new ControlSessao();
                                $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
                                $permissao = false;
                                $aux = null;
                                $auxPos = null;

                                $aux = explode("&", (string) $value);
                                foreach ($aux as $pos) {
                                    $auxPos = explode("=", $pos);
                                    if ($auxPos[0] != "idsPerm")
                                        $parametros[$auxPos[0]] = $auxPos[1];
                                    else {
                                        $parametros[$auxPos[0]] = explode(",", $auxPos[1]);
                                    }
                                }

                                if ($parametros["tipo"] == "direito") {
                                    foreach ($objUsuario->getDireitosUsuario() as $direitoUsr) {
                                        if (in_array($direitoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }else {
                                    foreach ($objUsuario->getGrupoUsuario() as $grupoUsr) {
                                        if (in_array($grupoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }


                                if ($index == $cont && $permissao == true) {
                                    $this->arrLegenda["delete"] = array("label" => "Deletar", "icone" => "<img width='12' title='Deletar' alt='Icone Deletar' src='" . URL_IMAGENS . "icons/page_white_delete.png' />");
                                    $newData .= " " . $objForAction->gridConfirm($data, $value, self::getClassGrid(), "<img width='14' title='Deletar' alt='Deletar' src='" . URL_IMAGENS . "icons/page_white_delete.png' />", "Tem certeza que gostaria de deletar este registro?", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
                                }

                                break;
                            case "permsPers" :
                                /*
                                 * Como proceder aqui:
                                 * permissão por direito: a=listaInscritos&amp;title=Lista Inscrições&amp;icone=application_view_detail.png&amp;tipo=direito&amp;idsPerm=168,169";
                                 * 		@param a = case do switch interno no Crud.
                                 * 		@param title = Texto que aparecerá no atributo alt e title do link e imagem.
                                 * 		@param icone = nome do arquivo de imagem (14x14) dentro da pasta html/imagens/icons/.
                                 * 		@param tipo = direito(atribuido por direito) | grupo(permitido por grupo).
                                 * 		@param idsPerm = ids que tem permissão, separados por virgula (id do direito no caso de [$tipo = direito] e id do grupo para [$tipo = grupo]) 
                                 * 				para verificacao com os dados na sessao do usuário.
                                 *
                                 * @author Matheus Vieira
                                 * @since 1.0 - 09/01/2012
                                 *
                                 */
                                $objCtrlSess = new ControlSessao();
                                $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
                                $permissao = false;
                                $aux = null;
                                $auxPos = null;

                                $aux = explode("&", (string) $value);
                                foreach ($aux as $pos) {
                                    $auxPos = explode("=", $pos);
                                    if ($auxPos[0] != "idsPerm")
                                        $parametros[$auxPos[0]] = ($auxPos[0] != "title") ? $auxPos[1] : utf8_decode($auxPos[1]);
                                    else {
                                        $parametros[$auxPos[0]] = explode(",", $auxPos[1]);
                                    }
                                }

                                if ($parametros["tipo"] == "direito") {
                                    foreach ($objUsuario->getDireitosUsuario() as $direitoUsr) {
                                        if (in_array($direitoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }else {
                                    foreach ($objUsuario->getGrupoUsuario() as $grupoUsr) {
                                        if (in_array($grupoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }

                                //FormataString::debuga($this->get["p"],$this->get["filtros"],$this->get["buscaGrid"]);
                                //self::debuga($value, $parametros);

                                if ($index == $cont && $permissao == true) {
                                    $this->arrLegenda["personal"] = array("label" => utf8_encode($parametros["title"]), "icone" => "<img width='12' title='" . utf8_encode($parametros["title"]) . "' alt='Icone " . utf8_encode($parametros["title"]) . "' src='" . URL_IMAGENS . "icons/" . $parametros["icone"] . "' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='" . $parametros["title"] . "' alt='" . $parametros["title"] . "' src='" . URL_IMAGENS . "icons/" . $parametros["icone"] . "' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2, "", $this->get["p"], $this->get["filtros"], $this->get["buscaGrid"]);
                                }
                                break;


                            case "permsPers2" :
                                /*
                                 * Como proceder aqui:
                                 * permissão por direito: a=listaInscritos&amp;title=Lista Inscrições&amp;icone=application_view_detail.png&amp;tipo=direito&amp;idsPerm=168,169";
                                 * 		@param a = case do switch interno no Crud.
                                 * 		@param title = Texto que aparecerá no atributo alt e title do link e imagem.
                                 * 		@param icone = nome do arquivo de imagem (14x14) dentro da pasta html/imagens/icons/.
                                 * 		@param tipo = direito(atribuido por direito) | grupo(permitido por grupo).
                                 * 		@param idsPerm = ids que tem permissão, separados por virgula (id do direito no caso de [$tipo = direito] e id do grupo para [$tipo = grupo]) 
                                 * 				para verificacao com os dados na sessao do usuário.
                                 *
                                 * @author Matheus Vieira
                                 * @since 1.0 - 09/01/2012
                                 *
                                 */
                                $objCtrlSess = new ControlSessao();
                                $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
                                $permissao = false;
                                $aux = null;
                                $auxPos = null;

                                $aux = explode("&", (string) $value);
                                foreach ($aux as $pos) {
                                    $auxPos = explode("=", $pos);
                                    if ($auxPos[0] != "idsPerm")
                                        $parametros[$auxPos[0]] = ($auxPos[0] != "title") ? $auxPos[1] : utf8_decode($auxPos[1]);
                                    else {
                                        $parametros[$auxPos[0]] = explode(",", $auxPos[1]);
                                    }
                                }

                                if ($parametros["tipo"] == "direito") {
                                    foreach ($objUsuario->getDireitosUsuario() as $direitoUsr) {
                                        if (in_array($direitoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }else {
                                    foreach ($objUsuario->getGrupoUsuario() as $grupoUsr) {
                                        if (in_array($grupoUsr, $parametros["idsPerm"]))
                                            $permissao = true;
                                    }
                                }

                                //self::debuga($value, $parametros);

                                if ($index == $cont && $permissao == true) {
                                    $this->arrLegenda["personal2"] = array("label" => utf8_encode($parametros["title"]), "icone" => "<img width='12' title='" . utf8_encode($parametros["title"]) . "' alt='Icone " . utf8_encode($parametros["title"]) . "' src='" . URL_IMAGENS . "icons/" . $parametros["icone"] . "' />");
                                    $newData .= " " . $objForAction->gridAction($data, $value, self::getClassGrid(), "<img width='14' title='" . $parametros["title"] . "' alt='" . $parametros["title"] . "' src='" . URL_IMAGENS . "icons/" . $parametros["icone"] . "' />", $tipo, $categoria, $strParam, $strValParam, $strParam2, $strValParam2);
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
    private function getQuery($count = false) {
        //$count=false;
        $strQuery = "SELECT ";
        if ($count) {
            $strQuery .= " COUNT(*) as total ";
        } else {
            $strQuery .= self::getObjXml()->query->campos;
        }
        $strQuery .= " FROM ";
        $strQuery .= self::getTablesQuery();
        $strQuery .= " WHERE 1 = 1 ";
        if (self::getXmlWhere()) {
            $strQuery .= " AND " . self::getXmlWhere() . " ";
        }
        if ($this->post) {
            $filtro = FormataPost::verificaArraySeExisteAlgumValor($this->post);
        } else {
            $filtro = false;
        }
        ##########################LISTAGEM RESTRITA###################################################################################################
        //$filtro = true;
        // quem tiver tal permissão podera ver listagem sem restrição mas se filtrar poderá ver outros.
        if ($this->busca == "" && $filtro == false) {
            $objCtrlSess = new ControlSessao();
            $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
            if (self::getObjXml()->permissaoListByUsu) {
                if (in_array(self::getObjXml()->permissaoListByUsu->campo2, $objUsuario->getDireitosUsuario())) {
                    $strReplaceFun = (string) self::getObjXml()->permissaoListByUsu->replace2->para->funcao;
                    $strReplaceVal = self::$strReplaceFun();
                    $strReplace = str_replace(self::getObjXml()->permissaoListByUsu->replace2->de, $strReplaceVal, self::getObjXml()->permissaoListByUsu->where2);
                    $strQuery .= " AND " . $strReplace . " ";
                    //FormataString::debuga($strReplaceFun,$strReplaceVal,$strReplace);
                } else if (in_array(self::getObjXml()->permissaoListByUsu->campo1, $objUsuario->getDireitosUsuario())) {
                    if (self::getObjXml()->permissaoListByUsu->where1) {
                        $strReplaceFun = (string) self::getObjXml()->permissaoListByUsu->replace1->para->funcao;
                        $strReplaceVal = self::$strReplaceFun();
                        $strReplace = str_replace(self::getObjXml()->permissaoListByUsu->replace1->de, $strReplaceVal, self::getObjXml()->permissaoListByUsu->where1);
                        $strQuery .= " AND " . $strReplace . " ";
                    }
                }
            }
        } else {
            $objCtrlSess = new ControlSessao();
            $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
            if (self::getObjXml()->permissaoListByUsu) {
                if (!in_array(self::getObjXml()->permissaoListByUsu->campo2, $objUsuario->getDireitosUsuario())) {
                    if (in_array(self::getObjXml()->permissaoListByUsu->campo1, $objUsuario->getDireitosUsuario())) {
                        if (self::getObjXml()->permissaoListByUsu->wherebusca1) {
                            $strReplaceFun = (string) self::getObjXml()->permissaoListByUsu->replace1->para->funcao;
                            $strReplaceVal = self::$strReplaceFun();
                            $strReplace = str_replace(self::getObjXml()->permissaoListByUsu->replace1->de, $strReplaceVal, self::getObjXml()->permissaoListByUsu->wherebusca1);
                            $strQuery .= " AND " . $strReplace . " ";
                        }
                    }
                } else if (in_array(self::getObjXml()->permissaoListByUsu->campo2, $objUsuario->getDireitosUsuario())) {
                    $strReplaceFun = (string) self::getObjXml()->permissaoListByUsu->replace2->para->funcao;
                    $strReplaceVal = self::$strReplaceFun();
                    $strReplace = str_replace(self::getObjXml()->permissaoListByUsu->replace2->de, $strReplaceVal, self::getObjXml()->permissaoListByUsu->wherebusca2);
                    $strQuery .= " AND " . $strReplace . " ";
                }
            }
        }
        //##############################################################################################################################################
        if (trim((string) self::getObjXml()->query->where) != "") {
            $strQuery .= " AND ";
            $string = trim((string) self::getObjXml()->query->where);
            $strQuery .= str_replace("#idReferencia#", self::getIdReferencia(), $string);
            if (self::getVariavelWhere1() != "")
                $strQuery .= " " . self::getVariavelWhere1() . " ";
        }
        if (trim((string) self::getObjXml()->query->whereCondicao) != "") {
            if (self::getVariavelWhere2() != "") {
                $strQuery .= " AND ";
                $strQuery .= trim((string) self::getObjXml()->query->whereCondicao);
                $strQuery .= " " . self::getVariavelWhere2() . " ";
            }
        }

        unset($this->post["buscaGrid"]);
        if ($this->post != "" && isset(self::getObjXml()->filtro)) {
            if (!empty($this->post)) {
                self::getObjSmarty()->assign("ABRE_FILTRO", true);
            }
            foreach ($this->post as $key => $valor) {
                if ($valor != "") {
                    $valor = FormataString::retiraCaracterEspecial($valor);
                    foreach (self::getObjXml()->filtro->campos as $campo) {
                        if ((string) $campo->attributes()->name == $key) {
                            $auxOr = ($campo->attributes()->OR) ? "( " : "";
                            $auxOrEnd = ($campo->attributes()->OR) ? ") " : "";
                            $orAnd = ($campo->attributes()->OR) ? " OR " : " AND ";
                            if ((string) $campo->attributes()->type == "select") {
                                $strQuery .= " AND " . $auxOr . (string) $campo->attributes()->campoQuery . " = '" . $valor . "'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery2 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery3 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery4 . " = '" . $valor . "'";
                                }
                                $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                            } else if ((string) $campo->attributes()->type == "data") {
                                //se houver filtro intervalo entre datas, deve ter no grid data inicial e data final
                                if (self::getDataIni() == "") {
                                    self::setDataIni($valor);
                                } else if (self::getDataFim() == "") {
                                    self::setDataFim($valor);
                                }
                                if (self::getDataIni() != "" && self::getDataFim() != "") {
                                    $data1 = FormataDatas::parseDataSql(self::getDataIni());
                                    $data2 = FormataDatas::parseDataSql(self::getDataFim());
                                    $strQuery .= " AND " . $auxOr . "date(" . $campo->attributes()->campoQuery . ") 
                                        BETWEEN date('" . $data1 . "') AND date('" . $data2 . "') ";
                                    $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                                }
                            } else {
                                $aux = str_replace("\"", "", $valor);

                                $strQuery .= " AND (";
                                $strQuery .= $auxOr . (string) $campo->attributes()->campoQuery . " LIKE '%" . $aux . "%'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery3 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                $strQuery .= $auxOrEnd . ")";
                            }
                        }
                    }
                }
            }
        }

        //self::debuga($strQuery);

        if (trim((string) self::getObjXml()->query->whereBusca) != "") {
            if ($this->busca != "") {
                $arrBusca = $this->busca;
                $arrBusca = explode('\"', $arrBusca);
                if (count($arrBusca) <= 1)
                    $arrBusca = explode(" ", $this->busca);

                $arrBusca = FormataPost::limpaArray($arrBusca);
                sort($arrBusca);
                if (count($arrBusca) > 0) {
                    $strQuery .= " AND ";
                    for ($i = 0; $i < count($arrBusca); $i++) {
                        $strQuery .= ($i == 0) ? "(" : "";

                        $strQuery .= str_replace("#BUSCA#", utf8_decode($arrBusca[$i]), trim((string) self::getObjXml()->query->whereBusca));

                        $strQuery .= ($i != count($arrBusca) - 1) ? " OR " : "";
                        $strQuery .= ($i == count($arrBusca) - 1) ? ")" : "";
                    }
                }
            }
        }

        $idUsuario = self::getVariavelUsuario();
        if ($idUsuario)
            if (trim((string) self::getObjXml()->query->whereUsuario) != "") {
                $strQuery .= " AND ";
                $strQuery .= trim((string) self::getObjXml()->query->whereUsuario);
                $strQuery .= " " . $idUsuario . " ";
            }

        if (self::getCtrlConfiguracoes()->getIdPortal()) {
            $portal = (string) self::getObjXml()->attributes()->portal;
            if (strtolower((string) self::getObjXml()->attributes()->portal) == "true") {
                $campoPortal = trim((string) self::getObjXml()->query->campoPortal);
                $strQuery .= " AND ";
                $strQuery .= "(" . $campoPortal . " = " . self::getCtrlConfiguracoes()->getIdPortal() . " OR " . $campoPortal . " = " . PORTAL_SISTEMA . " )";
            }
        }
        if (trim((string) self::getObjXml()->query->groupBy) != "") {
            $strQuery .= " GROUP BY ";
            $strQuery .= trim((string) self::getObjXml()->query->groupBy);
        }
        if (trim((string) self::getObjXml()->query->orderBy) != "" || !empty($this->get["orderBy"])) {
            $strQuery .= " ORDER BY ";
            if (!empty($this->get["orderBy"])) {
                $arrOrdGet = unserialize($this->get["orderBy"]);
                $strQuery .= $arrOrdGet["campo"] . " " . $arrOrdGet["orderBy"];
                if (trim((string) self::getObjXml()->query->orderBy) != "") {
                    $strQuery .= ", ";
                }
            }
            if (trim((string) self::getObjXml()->query->orderBy) != "") {
                $strQuery .= trim((string) self::getObjXml()->query->orderBy);
            }
        }

        //if(!empty($this->get))
        //self::debuga($this->get, $arrOrdGet, $arrTitulos);		
        //trata valores especiais query
        //self::debuga($strQuery);
        return $strQuery;
    }

    /**
     * Método para retornar a query passada pelo xml do construtor
     *
     * @author André Coura
     * @since 1.0 - 09/07/2008
     */
    private function getQueryUnion($count = false) {
        //$count=false;
        $strQuery = "(SELECT ";

        $strQuery .= self::getObjXml()->query1->campos;

        $strQuery .= " WHERE 1 = 1 ";
        if (self::getXmlWhere()) {
            $strQuery .= " AND " . self::getXmlWhere() . " ";
        }


        if (trim((string) self::getObjXml()->query1->where) != "") {
            $strQuery .= " AND ";
            $string = trim((string) self::getObjXml()->query1->where);
            $strQuery .= str_replace("#idReferencia#", self::getIdReferencia(), $string);
        }
        if (trim((string) self::getObjXml()->query1->whereCondicao) != "") {
            if (self::getVariavelWhere2() != "") {
                $strQuery .= " AND ";
                $strQuery .= trim((string) self::getObjXml()->query1->whereCondicao);
                $strQuery .= " " . self::getVariavelWhere2() . " ";
            }
        }

        unset($this->post["buscaGrid"]);
        if ($this->post != "" && isset(self::getObjXml()->filtro)) {
            if (!empty($this->post)) {
                self::getObjSmarty()->assign("ABRE_FILTRO", true);
            }
            foreach ($this->post as $key => $valor) {
                if ($valor != "") {
                    $valor = FormataString::retiraCaracterEspecial($valor);
                    foreach (self::getObjXml()->filtro->campos as $campo) {
                        if ((string) $campo->attributes()->name == $key) {
                            $auxOr = ($campo->attributes()->OR) ? "( " : "";
                            $auxOrEnd = ($campo->attributes()->OR) ? ") " : "";
                            $orAnd = ($campo->attributes()->OR) ? " OR " : " AND ";
                            if ((string) $campo->attributes()->type == "select") {
                                $strQuery .= " AND " . $auxOr . (string) $campo->attributes()->campoQuery . " = '" . $valor . "'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery2 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery3 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery4 . " = '" . $valor . "'";
                                }
                                $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                            } else if ((string) $campo->attributes()->type == "data") {
                                //if($this->dataCont==null){
                                $proximo = array_values($this->post);

                                $proximoValor = $proximo[1];
                                $strQuery .= " AND " . $auxOr . "DATE_FORMAT(" . (string) $campo->attributes()->campoQuery . ",'%d/%m/%Y')" . " BETWEEN '" . $valor . "' AND '" . $proximoValor . "'";
                                $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                                //}
                                //$this->dataCont=1;
                            } else {
                                $aux = str_replace("\"", "", $valor);

                                $strQuery .= " AND (";
                                $strQuery .= $auxOr . (string) $campo->attributes()->campoQuery . " LIKE '%" . $aux . "%'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery3 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                $strQuery .= $auxOrEnd . ")";
                            }
                        }
                    }
                }
            }
        }
        if (trim((string) self::getObjXml()->query1->whereBusca) != "") {
            if ($this->busca != "") {
                $arrBusca = $this->busca;
                $arrBusca = explode('\"', $arrBusca);
                if (count($arrBusca) <= 1)
                    $arrBusca = explode(" ", $this->busca);

                $arrBusca = FormataPost::limpaArray($arrBusca);
                sort($arrBusca);
                if (count($arrBusca) > 0) {
                    $strQuery .= " AND ";
                    for ($i = 0; $i < count($arrBusca); $i++) {
                        $strQuery .= ($i == 0) ? "(" : "";

                        $strQuery .= str_replace("#BUSCA#", utf8_decode($arrBusca[$i]), trim((string) self::getObjXml()->query1->whereBusca));

                        $strQuery .= ($i != count($arrBusca) - 1) ? " OR " : "";
                        $strQuery .= ($i == count($arrBusca) - 1) ? ")" : "";
                    }
                }
            }
        }

        if (self::getCtrlConfiguracoes()->getIdPortal()) {
            $portal = (string) self::getObjXml()->attributes()->portal;
            if (strtolower((string) self::getObjXml()->attributes()->portal) == "true") {
                $campoPortal = trim((string) self::getObjXml()->query1->campoPortal);
                $strQuery .= " AND ";
                $strQuery .= "(" . $campoPortal . " = " . self::getCtrlConfiguracoes()->getIdPortal() . " OR " . $campoPortal . " = " . PORTAL_SISTEMA . " )";
            }
        }
        if (trim((string) self::getObjXml()->query1->groupBy) != "") {
            $strQuery .= " GROUP BY ";
            $strQuery .= trim((string) self::getObjXml()->query1->groupBy);
        }
        if (trim((string) self::getObjXml()->query1->orderBy) != "" || !empty($this->get["orderBy"])) {
            $strQuery .= " ORDER BY ";
            if (!empty($this->get["orderBy"])) {
                $arrOrdGet = unserialize($this->get["orderBy"]);
                $strQuery .= $arrOrdGet["campo"] . " " . $arrOrdGet["orderBy"];
                if (trim((string) self::getObjXml()->query1->orderBy) != "") {
                    $strQuery .= ", ";
                }
            }
            if (trim((string) self::getObjXml()->query1->orderBy) != "") {
                $strQuery .= trim((string) self::getObjXml()->query1->orderBy);
            }
        }
        $strQuery .=" ) ";
        //UNION######################################################################
        $strQuery.=" " . self::getObjXml()->tipoSelect->valor . " ";
        //UNION######################################################################

        $strQuery .= "(SELECT ";

        $strQuery .= self::getObjXml()->query2->campos;

        $strQuery .= " WHERE 1 = 1 ";
        if (self::getXmlWhere()) {
            $strQuery .= " AND " . self::getXmlWhere() . " ";
        }


        if (trim((string) self::getObjXml()->query2->where) != "") {
            $strQuery .= " AND ";
            $string = trim((string) self::getObjXml()->query2->where);
            $strQuery .= str_replace("#idReferencia#", self::getIdReferencia(), $string);
        }
        if (trim((string) self::getObjXml()->query2->whereCondicao) != "") {
            if (self::getVariavelWhere2() != "") {
                $strQuery .= " AND ";
                $strQuery .= trim((string) self::getObjXml()->query2->whereCondicao);
                $strQuery .= " " . self::getVariavelWhere2() . " ";
            }
        }

        unset($this->post["buscaGrid"]);
        if ($this->post != "" && isset(self::getObjXml()->filtro)) {
            if (!empty($this->post)) {
                self::getObjSmarty()->assign("ABRE_FILTRO", true);
            }
            foreach ($this->post as $key => $valor) {
                if ($valor != "") {
                    $valor = FormataString::retiraCaracterEspecial($valor);
                    foreach (self::getObjXml()->filtro->campos as $campo) {
                        if ((string) $campo->attributes()->name == $key) {
                            $auxOr = ($campo->attributes()->OR) ? "( " : "";
                            $auxOrEnd = ($campo->attributes()->OR) ? ") " : "";
                            $orAnd = ($campo->attributes()->OR) ? " OR " : " AND ";
                            if ((string) $campo->attributes()->type == "select") {
                                $strQuery .= " AND " . $auxOr . (string) $campo->attributes()->campoQuery . " = '" . $valor . "'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery2 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery3 . " = '" . $valor . "'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= $orAnd . (string) $campo->attributes()->campoQuery4 . " = '" . $valor . "'";
                                }
                                $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                            } else if ((string) $campo->attributes()->type == "data") {
                                //if($this->dataCont==null){
                                $proximo = array_values($this->post);

                                $proximoValor = $proximo[1];
                                $strQuery .= " AND " . $auxOr . "DATE_FORMAT(" . (string) $campo->attributes()->campoQuery . ",'%d/%m/%Y')" . " BETWEEN '" . $valor . "' AND '" . $proximoValor . "'";
                                $strQuery .= ($campo->attributes()->OR) ? ") " : "";
                                //}
                                //$this->dataCont=1;
                            } else {
                                $aux = str_replace("\"", "", $valor);

                                $strQuery .= " AND (";
                                $strQuery .= $auxOr . (string) $campo->attributes()->campoQuery . " LIKE '%" . $aux . "%'";
                                if ($campo->attributes()->campoQuery2 || $campo->attributes()->campoQuery2 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery3 || $campo->attributes()->campoQuery3 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery3 . " LIKE '%" . $aux . "%'";
                                }
                                if ($campo->attributes()->campoQuery4 || $campo->attributes()->campoQuery4 != "") {
                                    $strQuery .= " OR " . (string) $campo->attributes()->campoQuery2 . " LIKE '%" . $aux . "%'";
                                }
                                $strQuery .= $auxOrEnd . ")";
                            }
                        }
                    }
                }
            }
        }
        if (trim((string) self::getObjXml()->query2->whereBusca) != "") {
            if ($this->busca != "") {
                $arrBusca = $this->busca;
                $arrBusca = explode('\"', $arrBusca);
                if (count($arrBusca) <= 1)
                    $arrBusca = explode(" ", $this->busca);

                $arrBusca = FormataPost::limpaArray($arrBusca);
                sort($arrBusca);
                if (count($arrBusca) > 0) {
                    $strQuery .= " AND ";
                    for ($i = 0; $i < count($arrBusca); $i++) {
                        $strQuery .= ($i == 0) ? "(" : "";

                        $strQuery .= str_replace("#BUSCA#", utf8_decode($arrBusca[$i]), trim((string) self::getObjXml()->query2->whereBusca));

                        $strQuery .= ($i != count($arrBusca) - 1) ? " OR " : "";
                        $strQuery .= ($i == count($arrBusca) - 1) ? ")" : "";
                    }
                }
            }
        }

        if (self::getCtrlConfiguracoes()->getIdPortal()) {
            $portal = (string) self::getObjXml()->attributes()->portal;
            if (strtolower((string) self::getObjXml()->attributes()->portal) == "true") {
                $campoPortal = trim((string) self::getObjXml()->query2->campoPortal);
                $strQuery .= " AND ";
                $strQuery .= "(" . $campoPortal . " = " . self::getCtrlConfiguracoes()->getIdPortal() . " OR " . $campoPortal . " = " . PORTAL_SISTEMA . " )";
            }
        }
        if (trim((string) self::getObjXml()->query2->groupBy) != "") {
            $strQuery .= " GROUP BY ";
            $strQuery .= trim((string) self::getObjXml()->query2->groupBy);
        }
        if (trim((string) self::getObjXml()->query2->orderBy) != "" || !empty($this->get["orderBy"])) {
            $strQuery .= " ORDER BY ";
            if (!empty($this->get["orderBy"])) {
                $arrOrdGet = unserialize($this->get["orderBy"]);
                $strQuery .= $arrOrdGet["campo"] . " " . $arrOrdGet["orderBy"];
                if (trim((string) self::getObjXml()->query2->orderBy) != "") {
                    $strQuery .= ", ";
                }
            }
            if (trim((string) self::getObjXml()->query2->orderBy) != "") {
                $strQuery .= trim((string) self::getObjXml()->query2->orderBy);
            }
        }
        $strQuery .=" ) ";

        $strQuery = str_replace("#idReferencia#", self::getIdReferencia(), $strQuery);
        //if(!empty($this->get))
        //self::debuga($this->get, $arrOrdGet, $arrTitulos);		
        //trata valores especiais query
        //self::debuga($strQuery);
        return $strQuery;
    }

    private $variavelGridWhere1;

    public function setVariavelWhere1($varGrid) {
        $this->variavelGridWhere1 = $varGrid;
    }

    public function getVariavelWhere1() {
        return $this->variavelGridWhere1;
    }

    private $variavelGridWhere2;

    public function setVariavelWhere2($varGrid) {
        $this->variavelGridWhere2 = $varGrid;
    }

    public function getVariavelWhere2() {
        return $this->variavelGridWhere2;
    }

    private $variavelUsuario;

    public function setVariavelUsuario($varGrid) {
        $this->variavelUsuario = $varGrid;
    }

    public function getVariavelUsuario() {
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
        if (trim((string) self::getObjXml()->tipoSelect->nome == "Union")) {
            $strQuery = self::getQueryUnion();
            $strQuery .= " LIMIT " . $inicio . " , " . FWK_LIMIT_PAGINACAO;
        } else {
            $strQuery = self::getQuery();
            $strQuery .= " LIMIT " . $inicio . " , " . FWK_LIMIT_PAGINACAO;
        }
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
        $arrOrdGet = (!empty($this->get["orderBy"])) ? unserialize($this->get["orderBy"]) : null;
        $arrTitulos = array();
        if (self::getObjXml()->header || self::getObjXml()->header != "") {
            if (self::getObjXml()->header->titulo && self::getObjXml()->header->titulo != "") {
                foreach (self::getObjXml()->header->titulo as $titulo) {
                    $ord = "ASC";
                    if ($titulo->attributes()->ordena) {
                        if ((string) $titulo->attributes()->ordena == $arrOrdGet["campo"]) {
                            if ($arrOrdGet["orderBy"] == "DESC") {
                                $ico = "&nbsp;&nbsp;&and;";
                                $ord = "ASC";
                            } elseif ($arrOrdGet["orderBy"] == "ASC") {
                                $ico = "&nbsp;&nbsp;&or;";
                                $ord = "DESC";
                            } else {
                                $ico = "";
                                $ord = "ASC";
                            }
                        } else {
                            $ico = "";
                        }
                        $arrOrd = serialize(array("campo" => (string) $titulo->attributes()->ordena, "orderBy" => $ord));
                        $arrTitulos[] = array(
                            self::getOrdenacao($titulo->attributes()->type),
                            "<a href=\"" . self::makeLinkPag("&orderBy=" . $arrOrd) . "\" title=\"" . (string) $titulo . "\">" . (string) $titulo . $ico . "</a>",
                            'class' => (string) $titulo->attributes()->class
                        );
                    } else {
                        $arrTitulos[] = array(
                            self::getOrdenacao($titulo->attributes()->type),
                            (string) $titulo,
                            'class' => (string) $titulo->attributes()->class
                        );
                    }
                }
                //if(!empty($this->get))
                //self::debuga($this->get, $arrOrdGet, $arrTitulos);			
            }
        } else {
            self::getTitulosDb();
        }
        return $arrTitulos;
    }

    private function trataFiltro($arrFiltros = null) {
        $objFormatParam = new FormataParametros();
        $objFormatParam->setParametros($_GET);
        $get = $objFormatParam->getParametros();
        $this->arrFiltros = unserialize($get["filtros"]);
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
     * Busca os títulos dos campos do db
     *
     * @author André Coura
     * @since 1.0 - 10/07/2008
     */
    public function permite($valor) {



        //self::debuga($objUsuario->getDireitosUsuario(),$valor);
        $permissao = false;

        $permissao = true;

        return $permissao;
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
        $objCtrlSess = new ControlSessao();
        $objUsuario = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);

        // $i = 0;
        foreach (self::getObjXml()->buttons->campos as $botoes) {
            $arrValores = $botoes->attributes()->permissao;
            $btName = $botoes->attributes()->name;
            $int = (int) $arrValores;
            if (in_array($int, $objUsuario->getDireitosUsuario()) || $arrValores == "") {
                if ($btName == "Cadastrar") {
                    if (self::verificaPermissoesCadastrar(self::getIdUsrSessao())) {
                        $objFactoryComps->setClasseAtual(self::getObjXml()->attributes()->classe);
                        $objFactoryComps->setParamWhere1(self::getVariavelWhere1());
                        $objFactoryComps->setParamWhere2(self::getVariavelWhere2());
                        $objFactoryComps->setTipo(self::getObjXml()->attributes()->tipo);
                        $objFactoryComps->setCategoria(self::getObjXml()->attributes()->categoria);
                        $objFactoryComps->buildComp($botoes);
                        $strCampos .= $objFactoryComps->getObjFactored()->getHtmlComp();
                    }
                } else {
                    $objFactoryComps->setClasseAtual(self::getObjXml()->attributes()->classe);
                    $objFactoryComps->setParamWhere1(self::getVariavelWhere1());
                    $objFactoryComps->setParamWhere2(self::getVariavelWhere2());
                    $objFactoryComps->setTipo(self::getObjXml()->attributes()->tipo);
                    $objFactoryComps->setCategoria(self::getObjXml()->attributes()->categoria);
                    $objFactoryComps->buildComp($botoes);
                    $strCampos .= $objFactoryComps->getObjFactored()->getHtmlComp();
                }
            }
        }
        self::getObjSmarty()->assign("BUTTONS_GRID", $strCampos);
    }

    /**
     * Método para gerar o marcador do grid a partir do XML
     *
     * @author Fernando Braga
     * @since 3.0 - 08/01/2013
     */
    private function regMarcadorGrid() {
        if (self::getObjXml()->layout->marcador) {
            self::getObjSmarty()->assign("TITULO_MARCADOR", self::getObjXml()->layout->marcador->titulo);
            self::getObjSmarty()->assign("MARCADOR_GRID", self::getObjSmarty()->fetch(FWK_HTML_GRID . "marcadorGrid.tpl"));
        }
    }

    /**
     * Método para gerar o marcador do grid a partir do XML
     *
     * @author Fernando Braga
     * @since 3.0 - 08/01/2013
     */
    private function regInfosGrid() {

        if (self::getObjXml()->layout->infos->quantLabels) {
            for ($i = 1; $i <= self::getObjXml()->layout->infos->quantLabels; $i++) {
                $strLb = (String) "label" . $i;
                $strMlb = (String) "metodolabel" . $i;
                $metodo = (string) trim(self::getObjXml()->layout->infos->$strMlb);
                $arrLabel[] = self::getObjXml()->layout->infos->$strLb;
                $arrAttInfos = self::getArrAttrInfosGrid();
                $arrMetodoLabel[] = self::getObjMetodosBuscaGridDAO()->$metodo($arrAttInfos[$i - 1]);
            }
            self::getObjSmarty()->assign("ARRVALORES", $arrMetodoLabel);
            self::getObjSmarty()->assign("ARRLABEL", $arrLabel);
            self::getObjSmarty()->assign("INFOS_GRID", self::getObjSmarty()->fetch(FWK_HTML_GRID . "infosGrid.tpl"));
        }
    }

    /**
     * Método para gerar o filtro do grid a partir do XML
     *
     * @author Matheus Vieira
     * @since 1.0 - 01/02/2012
     */
    private function regFiltroGrid() {
        if (is_array($this->arrFiltros))
            $this->post = FormataPost::mergeArrayPost($this->post, $this->arrFiltros);

        if (isset(self::getObjXml()->filtro)) {
            $objFactoryCompsHtml = new FactoryCompHtml();
            $arrCampos = "";

            $cont = 0;
            foreach (self::getObjXml()->filtro->campos as $campo) {
                if ($campo->attributes()->type != "text" && $campo->attributes()->type != "select" && $campo->attributes()->type != "data")
                    throw new GridException("O atributo type=\"" . $campo->attributes()->type . "\" no campo " . (string) $campo->attributes()->label . " não é válido para filtro. No filtro é permitido apenas campos \"text\" e \"select\".");

                if (!$campo->attributes()->campoQuery || $campo->attributes()->campoQuery == "")
                    throw new GridException("Não foi passado o atributo \"campoQuery\" no campo " . (string) $campo->attributes()->label . " do filtro.");

                $objFactoryCompsHtml->setClasseAtual(self::getObjXml()->attributes()->classe);
                $objFactoryCompsHtml->buildComp($campo, utf8_decode($this->post[(string) $campo->attributes()->name]));
                $arrCampos[$cont]["label"] = (string) $campo->attributes()->label;
                $arrCampos[$cont]["campo"] = $objFactoryCompsHtml->getObjFactored()->getHtmlComp();
                $cont++;
            }

            self::getObjSmarty()->assign("CAMPOS_FILTRO", $arrCampos);
            $strLink = (isset(self::getObjXml()->attributes()->categoria)) ? (string) self::getObjXml()->attributes()->categoria . "&f=" : "";
            $strLink .= self::getClassGrid();

            $strTipo = ((string) self::getObjXml()->attributes()->tipo == "MODULO") ? "m" : "c";

            self::getObjSmarty()->assign("ACTION_FILTRO", "?" . $strTipo . "=" . self::getObjCrypt()->cryptData($strLink));
            self::getObjSmarty()->assign("EXIBE_FILTRO", true);
        }
    }

    private function getCtrlConfiguracoes() {
        if ($this->objCtrlConfiguracoes == null)
            $this->objCtrlConfiguracoes = new ControlConfiguracoes();
        return $this->objCtrlConfiguracoes;
    }

    private function getObjMetodosBuscaGridDAO() {
        if ($this->objMetodosBuscaGridDAO == null)
            $this->objMetodosBuscaGridDAO = new MetodosBuscaGridDAO();
        return $this->objMetodosBuscaGridDAO;
    }

    private function getIdUsrSessao() {
        return self::getObjUsrSessao()->getIdUsuario();
    }

    public function setIdReferencia($intIdRef) {
        $this->idReferencia = $intIdRef;
    }

    public function getIdReferencia() {
        return $this->idReferencia;
    }

    /**
     * Método que recebe um array com os atributos de busca das funcoes especificadas na tag info do grid xml
     *
     * @author Fernando Braga
     * @since 3.0 - 09/01/2013
     */
    public function setArrAttrInfosGrid($arrAttInfos) {
        $this->arrAttrInfosGrid = $arrAttInfos;
    }

    public function getArrAttrInfosGrid() {
        return $this->arrAttrInfosGrid;
    }

    public function setArrPost($post) {
        if (!empty($post))
            $this->post = $post;
    }

    public function getArrPost() {
        return $this->post;
    }

    public function setArrGet($get) {
        if (!empty($get))
            $this->get = $get;
    }

    public function getArrGet() {
        return $this->get;
    }

    public function debuga() {
        $arrDados = func_get_args();
        print("<pre>");
        for ($i = 0; $i < count($arrDados); $i++) {
            print_r($arrDados[$i]);
            print"<br />";
        }
        die();
    }

    private function verificaPermissoesExcluir($idUser = null) {
        $status = false;
        $subDireitos = self::getPermissoesUsuario($idUser, self::getObjXml()->attributes()->direito);
        if (!empty($subDireitos)) {
            if (in_array(EXCLUIR, $subDireitos)) {
                $status = true;
            }
        }
        return $status;
    }

    private function verificaPermissoesCadastrar($idUser = null) {
        $status = false;
        $subDireitos = self::getPermissoesUsuario($idUser, self::getObjXml()->attributes()->direito);
        //$subDireitos = FormataPost::colocaValoresEmSequenciaAposUmSelect($subDireitos);
        if (!empty($subDireitos)) {
            if (in_array(CADASTRAR, $subDireitos)) {
                $status = true;
            }
        }
        return $status;
    }

    protected function getPermissoesUsuario($idUsuario, $idDireito) {
        $strQuery = "SELECT id_sub_direito FROM fwk_sub_direitos_usuarios where id_usuario=" . $idUsuario . " AND id_direito=" . $idDireito . "
                        UNION DISTINCT
                        SELECT id_sub_direito FROM fwk_sub_direitos_grupo
                        WHERE id_direito=" . $idDireito . "
                        AND id_grupo in (SELECT id_grupo FROM fwk_grupo_usuario where id_usuario=" . $idUsuario . ")";

        $arrRet = ControlDB::getAll($strQuery);
        $arrDireitos = array();
        if (is_array($arrRet) && count($arrRet) > 0)
            foreach ($arrRet as $arrIdDireito) {
                $arrDireitos[] = $arrIdDireito[0];
            }
        //self::debuga($arrDireitos);
        return $arrDireitos;
    }

}

?>