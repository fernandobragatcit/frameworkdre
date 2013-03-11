<?php
require(FWK_FORM_CH . "configTagsChamado.php");
require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO_CH. "ChamadosDAO.class.php");


class CrudChamados extends AbsCruds {

     public function executa($get, $post, $file) {
        ControlJS::getJS()->addJs(FWK_JS_CH."acoesCh.js");
        parent::setTipo("MODULO");
        parent::setCategoria("formularios");
        self::setTipoForm("formularios");
        //fazer ler direto do XML para essa classe tambÃ©m futuramente
        parent::setClassModel(new ChamadosDAO());
        parent::setStringClass("" . __CLASS__ . "");
        $strLink = self::getObjCrypt()->cryptData(parent::getStrLinkClass());
        parent::getObjSmarty()->assign("LINK_CLASS", $strLink);
        switch ($get["a"]) {
            case "exibeCrm":
                self::exibeCrm();
                break;
            default:
                self::exibeCrm();
                break;
        }
    }

    public function exibeCrm() {
        //self::getLeads();
        self::debuga("dfdfdf");
        $mes = date('m');
        $ano = date('Y');
        $status = true;

        if ($_GET["data1"]) {
            $dataIni = FormataDatas::parseDataSql($_GET["data1"]);
            $data1 = $_GET["data1"];
            $dataCompIni = strtotime($dataIni);
        } else {
            $dataIni = $ano . "-" . $mes . "-01";
            $data1 = "01/" . $mes . "/" . $ano;
            $status = false;
        }
        if ($_GET["data2"]) {
            $dataFim = FormataDatas::parseDataSql($_GET["data2"]);
            $data2 = $_GET["data2"];
            $dataCompFim = strtotime($dataFim);
        } else {
            $data2 = date("d/m/Y");
            $dataFim = date("Y-m-d");
            $status = false;
        }
//        $arrCelula = Utf8Parsers::arrayUtf8Encode(self::getObjCelula()->buscaCampos($id, 0));
//        $arrAcessos = Utf8Parsers::matrizUtf8Encode(self::getClassModel()->buscaAcessos($id, $dataIni, $dataFim));
//        $arrFila = Utf8Parsers::matrizUtf8Encode(self::getObjColabCel()->getColaboradoresByIdCelula($id));
        $textoData = $data1 . " à " . $data2;
        self::getObjSmarty()->assign("DATA", $textoData);
        self::getObjSmarty()->assign("DATAINI", $data1);
        self::getObjSmarty()->assign("DATAFIM", $data2);

        self::getObjSmarty()->assign("TITULO_FORMS", "CRM IBS");
        //self::getObjSmarty()->assign("TITULO", "Novos Leads");

        $params = FORMS_VIEW;
        $params .= "&classe=CrudCrmIbs";
        $params .= "&metodo=exibeCrm";
        $params = self::getObjCrypt()->cryptData($params);
        self::getObjSmarty()->assign("PARAMS", $params);

        if ($_GET['jsoncallback']) {
            require_once(FWK_CONTROL . "ControlHttp.class.php");
            $objHttp = new ControlHttp(self::getObjSmarty());
            if ($_GET["aba"] == "leads") {
                $strTela = self::getLeads();
            } else if ($_GET["aba"] == "eventos") {
                $strTela = $strTela = self::getEventos();
            } else if ($_GET["aba"] == "lembretes") {
                $strTela = $strTela = self::getLembretes();
            } else if ($_GET["aba"] == "oportunidades") {
                $strTela = $strTela = self::getOportunidades();
            } else if ($_GET["aba"] == "contatos") {
                $strTela = $strTela = self::getContatos();
            } else {
                $strTela = parent::getObjSmarty()->fetch(FORMS_TPL . "ibsfgv/formCrmIbs.tpl");
            }
            print ($_GET["jsoncallback"] . "(" . self::getObjJson()->encode(array("resultado" => true, "retorno" => $strTela)) . ")");
        } else {
            $strTela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmDefault.tpl");
        }
        if ($objHttp) {
            $objHttp->escreEm("CORPO", FORMS_TPL . "formCrmIbs.tpl");
        } else {

            self::getObjSmarty()->assign("CONTEUDO", $strTela);
            self::getObjHttp()->escreEm("CORPO", FORMS_TPL . "formCrmIbs.tpl");
        }

//        self::getObjSmarty()->assign("NOME_REFERENCIAL", $arrCelula["nome_celula"]);
//        self::getObjSmarty()->assign("LINKFILTRO", "?m=" . self::getObjCrypt()->cryptData("formularios&f=CrudCrmIbs&a=exibeCrm&id=" . $arrCelula["id_celula"] . ""));
//        self::getObjSmarty()->assign("BTN_CANCELAR", "<a class='button' onclick=\"return vaiPara('?m=" . self::getObjCrypt()->cryptData("formularios&f=CrudCrmIbs") . "');\" name=\"Voltar\">Voltar</a>");
//        self::getObjHttp()->escreEm("CORPO", FORMS_TPL . "formCrmIbs.tpl");
    }

    public function getLeads() {
        $intPag = trim($_GET["pag"]);
        $intPag = intval(FormataString::retiraCharsInvalidos($intPag));
//        if ($_GET['jsoncallback2']) {
//            self::debuga($intPag);
//        }
        self::getObjSmarty()->assign("TITULO", "Leads");
        $novos_cadastros = self::getObjLogCliente()->getDadosNovosCadastros($intPag, NUM_ELEMENTOS_LEADS);
        $totDados = self::getObjLogCliente()->getCountDadosNovosCadastros();
        foreach ($novos_cadastros as $i => $valor) {
            $novos_cadastros[$i]["link"]="?m=".self::getObjCrypt()->cryptData("formularios&f=CrudClientes&a=formAlt&id=".$valor["id_cliente"]);
            $novos_cadastros[$i]["telefones"] = $valor["tel1_contato"];
            $novos_cadastros[$i]["telefones"] .= ($valor["cel1_contato"]) ? " | " . $valor["cel1_contato"] : "";
        }

        $params = FORMS_VIEW;
        $params .= "&classe=CrudCrmIbs";
        $params .= "&metodo=getLeads";
        $params = self::getObjCrypt()->cryptData($params);
        //PAGINAÇÃO
        if ($totDados > NUM_ELEMENTOS_LEADS) {
            parent::getObjSmarty()->assign("PAGINACAO", true);
            $totPags = ceil($totDados / NUM_ELEMENTOS_LEADS);
            $intPag = ($intPag == 0 || $intPag == 1) ? 1 : $intPag;

            if ($intPag > 2)
                parent::getObjSmarty()->assign("PAG_PROXIMO", $params .= "&pag=" . ($intPag - 1));
            elseif ($intPag == 2)
                parent::getObjSmarty()->assign("PAG_PROXIMO", $params);

            if ($intPag < $totPags)
                parent::getObjSmarty()->assign("PAG_ANTERIOR", $params .= "&pag=" . ($intPag + 1));
        }
        
        //self::debuga($novos_cadastros);
        self::getObjSmarty()->assign("NOVOS_CADASTROS", $novos_cadastros);
        //self::debuga($novos_cadastros);
        $tela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmLeads.tpl");
        if ($_GET['jsoncallback2']) {
            print ($_GET["jsoncallback2"] . "(" . self::getObjJson()->encode(array("resultado" => true, "retorno" => $tela)) . ")");
        } else {
            return $tela;
        }
    }

    private function getLembretes() {

        self::getObjSmarty()->assign("TITULO", "Lembretes");
        $tela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmLembretes.tpl");
        return $tela;
    }

    private function getEventos() {
        self::getObjSmarty()->assign("TITULO", "Eventos");

        $tela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmEventos.tpl");
        return $tela;
    }

    private function getOportunidades() {
        self::getObjSmarty()->assign("TITULO", "Oportunidades");

        $tela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmOportunidades.tpl");
        return $tela;
    }

    private function getContatos() {
        self::getObjSmarty()->assign("TITULO", "Contatos");

        $tela = parent::getObjSmarty()->fetch(FORMS_TPL . "tagCrmContatos.tpl");
        return $tela;
    }

    private function getObjCelula() {
        if ($this->objCelula == null) {
            $this->objCelula = new CrudCrmIbsDAO();
        }
        return $this->objCelula;
    }

    private function getObjColabCel() {
        if ($this->objColabCel == null) {
            $this->objColabCel = new ColaboradoresCrudCrmIbsDAO();
        }
        return $this->objColabCel;
    }

    private function getObjCol() {
        if ($this->objCol == null) {
            $this->objCol = new ColaboradorDAO();
        }
        return $this->objCol;
    }

    private function getObjLogCliente() {
        if ($this->objLog == null) {
            $this->objLog = new LogDAO();
        }
        return $this->objLog;
    }

}

?>