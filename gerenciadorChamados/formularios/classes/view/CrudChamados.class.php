<?php

require(FWK_FORM_CH . "configTagsChamado.php");
require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO_CH . "ChamadosDAO.class.php");
require_once(FWK_DAO_CH . "SetorDAO.class.php");
require_once(CHA_MODEL . "AbsModelFormsCha.class.php");
require_once(FWK_MODEL_CH . "AbsModelFormsCha.class.php");

class CrudChamados extends AbsCruds {

    public function executa($get, $post, $file) {
        ControlJS::getJS()->addJs(FWK_JS_CH_URL . "acoesCh.js");
        ControlCSS::getCSS()->addCss(FWK_CSS_CH_URL . "styleChamados.css");
        parent::setTipo("MODULO");
        parent::setCategoria("formularios");
        self::setTipoForm("formularios");
        parent::setXmlForm(CHA_XML . "formCadastrarChamado.xml");
        //fazer ler direto do XML para essa classe tambÃ©m futuramente
        parent::setClassModel(new ChamadosDAO());
        parent::setStringClass("" . __CLASS__ . "");
        $strLink = self::getObjCrypt()->cryptData(parent::getStrLinkClass());
        parent::getObjSmarty()->assign("LINK_CLASS", $strLink);
        switch ($get["a"]) {
            case "exibeTelaChamados":
                self::exibeTelaChamados();
                break;
            case "salvarFormularioChamado":
                self::postCadastraChamado($get["id"], $post, $file);
                break;
            case "salvarFormularioSetor":
                self::postCadastraSetor($get["id"], $post, $file);
                break;
            default:
                self::exibeTelaChamados();
                break;
        }
    }

    public function exibeTelaChamados() {
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

        $textoData = $data1 . " à " . $data2;
        self::getObjSmarty()->assign("DATA", $textoData);
        self::getObjSmarty()->assign("DATAINI", $data1);
        self::getObjSmarty()->assign("DATAFIM", $data2);

        self::getObjSmarty()->assign("TITULO_FORMS", "SISTEMA DE CHAMADOS");

        $params = FWK_VIEW_CH;
        $params .= "&classe=CrudChamados";
        $params .= "&metodo=exibeTelaChamados";
        $params = self::getObjCrypt()->cryptData($params);
        self::getObjSmarty()->assign("PARAMS", $params);

        if ($_GET['jsoncallback']) {
            require_once(FWK_CONTROL . "ControlHttp.class.php");
            $objHttp = new ControlHttp(self::getObjSmarty());
            if ($_GET["aba"] == "chamados") {
                $strTela = self::getChamados();
            } else if ($_GET["aba"] == "abrechamados") {
                $strTela = $strTela = self::AbreChamados();
            } else if ($_GET["aba"] == "cadastrarsetor") {
                $strTela = $strTela = self::AbreSetor();
            } else if ($_GET["aba"] == "setor") {
                $strTela = $strTela = self::getSetor();
            } else if ($_GET["aba"] == "contatos") {
                $strTela = $strTela = self::getContatos();
            } else {
                $strTela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagChamados.tpl");
            }
            print ($_GET["jsoncallback"] . "(" . self::getObjJson()->encode(array("resultado" => true, "retorno" => $strTela)) . ")");
        } else {
            $strTela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagCrmDefault.tpl");
        }
        if ($objHttp) {
            $objHttp->escreEm("CORPO", FWK_TPLS_CH . "tagChamados.tpl");
        } else {

            self::getObjSmarty()->assign("CONTEUDO", $strTela);
            self::getObjHttp()->escreEm("CORPO", FWK_TPLS_CH . "tagChamados.tpl");
        }
    }

    public function getChamados() {
        $dadosChamados = self::getObjChamados()->getAllChamado();
        self::getObjSmarty()->assign("CHAMADOS", $dadosChamados);
        self::getObjSmarty()->assign("TITULO", "Lista de Chamados");
        $intPag = trim($_GET["pag"]);
        $intPag = intval(FormataString::retiraCharsInvalidos($intPag));
        $params = FWK_VIEW_CH;
        $params .= "&classe=CrudChamados";
        $params .= "&metodo=getChamados";
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
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaChamados.tpl");
        if ($_GET['jsoncallback2']) {
            print ($_GET["jsoncallback2"] . "(" . self::getObjJson()->encode(array("resultado" => true, "retorno" => $tela)) . ")");
        } else {
            return $tela;
        }
    }

    private function AbreChamados($msg = null) {
        //self::debuga($msg);
        $setor = self::getObjChamados()->getAllSetorChamados();
        $prioridade = self::getObjChamados()->getAllPrioridadeChamados();
        self::getObjSmarty()->assign("ARR_SETOR", $setor);
        self::getObjSmarty()->assign("ARR_PRIORIDADE", $prioridade);
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioChamado");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Cadastrar Chamados");
        if ($msg) {
            self::getObjSmarty()->assign("MSG", $msg);
        }

        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarChamado.tpl");
        return $tela;
    }

    public function postCadastraChamado($id, $post, $file) {
        if ($id) {
            $msg = "Chamado cadastrado com sucesso!";
        } else {
            $idUsuario = self::getIdUsrSessao();
            $post["id_usuario_solicitante"] = $idUsuario;
            $post["id_status"] = STATUS_DEFAULT;
            parent::getClassModel()->cadastrar(self::getXmlForm(), $post, $file);
            $msg = "Chamado cadastrado com sucesso!";
        }
        self::AbreChamados($msg);
    }

    private function AbreSetor($msg = null) {
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioSetor");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Cadastro de Setor");
        if ($msg) {
            self::getObjSmarty()->assign("MSG", $msg);
        }
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarSetor.tpl");
        return $tela;
    }

    public function postCadastraSetor($id, $post, $file) {
        parent::setClassModel(new SetorDAO());
        parent::getClassModel()->cadastrar(parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml"), $post, $file);
        $msg = "Setor cadastrado com sucesso!";
        self::AbreSetor($msg);
    }

    private function getSetor() {
        $setores = self::getObjSetor()->getAllSetor();
        self::getObjSmarty()->assign("TITULO", "Listagem de Setor");
        //self::debuga($setores);
        self::getObjSmarty()->assign("SETORES", $setores);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaSetor.tpl");
        return $tela;
    }

    private function getContatos() {
        self::getObjSmarty()->assign("TITULO", "Contatos");

        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagCrmContatos.tpl");
        return $tela;
    }

    private function getObjChamados() {
        if ($this->objChamados == null) {
            $this->objChamados = new ChamadosDAO();
        }
        return $this->objChamados;
    }

    private function getObjSetor() {
        if ($this->objSetor == null) {
            $this->objSetor = new SetorDAO();
        }
        return $this->objSetor;
    }

    public function getObjJson() {
        if ($this->objJason == null)
            $this->objJason = new Json();
        return $this->objJason;
    }

    private function getIdUsrSessao() {
        return self::getObjUsrSessao()->getIdUsuario();
    }

}

?>