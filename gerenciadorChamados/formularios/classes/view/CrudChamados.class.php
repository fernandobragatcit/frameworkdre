<?php

require(FWK_FORM_CH . "configTagsChamado.php");
require_once(CHA_MODEL . "AbsCrudsCha.class.php");
require_once(CHA_MODEL . "AbsModelFormsCha.class.php");

//CLASSES DE BANCO DE DADOS RELATIVO AO CHAMADO
require_once(FWK_DAO_CH . "ChamadosDAO.class.php");
require_once(FWK_DAO_CH . "PrioridadeDAO.class.php");
require_once(FWK_DAO_CH . "SetorDAO.class.php");
require_once(FWK_DAO_CH . "StatusDAO.class.php");

class CrudChamados extends AbsCrudsCha {

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
            case "salvarFormularioPrioridade":
                self::postCadastraPrioridade($get["id"], $post, $file);
                break;
            case "salvarFormularioStatus":
                self::postCadastraStatus($get["id"], $post, $file);
                break;
            case "deletaSetor":
                self::deletaSetor($get);
                break;
            default:
                self::exibeTelaChamados();
                break;
        }
    }

    public function exibeTelaChamados($get = null, $msg = null, $info = null, $alerta = null) {
        //self::debuga($get["id"]);
        $idChamado = $get["id"];
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
                $strTela = self::AbreChamados();
            } else if ($_GET["aba"] == "viewChamados") {
                $strTela = self::getViewChamado($idChamado);
            } else if ($_GET["aba"] == "cadastrarsetor") {
                $strTela = self::AbreSetor();
            } else if ($_GET["aba"] == "setor") {
                $strTela = self::getSetor();
            } else if ($_GET["aba"] == "prioridade") {
                $strTela = self::getPrioridade();
            } else if ($_GET["aba"] == "cadastrarprioridade") {
                $strTela = self::AbrePrioridade();
            } else if ($_GET["aba"] == "status") {
                $strTela = self::getStatus();
            } else if ($_GET["aba"] == "cadastrarstatus") {
                $strTela = self::AbreStatus();
            } else {
                $strTela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagChamados.tpl");
            }
            print ($_GET["jsoncallback"] . "(" . self::getObjJson()->encode(array("resultado" => true, "retorno" => $strTela)) . ")");
        } else {
            if ($_GET["aba"] == "abrechamados") {
                $strTela = self::AbreChamados($msg, $info, $alerta);
            } else if ($_GET["aba"] == "cadastrarsetor") {
                $strTela = self::AbreSetor($msg, $info, $alerta);
            } else if ($_GET["aba"] == "cadastrarprioridade") {
                $strTela = self::AbrePrioridade($msg, $info, $alerta);
            } else if ($_GET["aba"] == "cadastrarstatus") {
                $strTela = self::AbreStatus($msg, $info, $alerta);
            } else if ($_GET["aba"] == "setor") {
                $strTela = self::getSetor($msg, $info, $alerta);
            } else {
                self::getObjSmarty()->assign("DEFAULT", true);
                $strTela = self::getChamados();
            }
        }
        if ($objHttp) {
            $objHttp->escreEm("CORPO", FWK_TPLS_CH . "tagChamados.tpl");
        } else {
            self::getObjSmarty()->assign("CONTEUDO", $strTela);
            self::getObjHttp()->escreEm("CORPO", FWK_TPLS_CH . "tagChamados.tpl");
        }
    }

    public function formAlteraChamado($id = null) {
        parent::setXmlForm(CHA_XML . "formCadastrarChamado.xml");
        $arrDadosChamados = self::getClassModel()->buscaCampos($id);
        self::getClassModel()->setTipoForm(self::getTipoForm());
        self::getClassModel()->preencheFormComDados(parent::getXmlForm(), $id, self::getStringClass(), $arrDadosChamados);
    }

    public function getChamados() {
        $paramsViewChamado = FWK_VIEW_CH;
        $paramsViewChamado .= "&classe=CrudChamados";
        $paramsViewChamado .= "&metodo=exibeTelaChamados";
        $dadosChamados = self::getObjChamados()->getAllChamado();
        foreach ($dadosChamados as $i => $valor) {
            $dadosChamados[$i]["link"] = self::getObjCrypt()->cryptData($paramsViewChamado . "&id=" . $valor["id_chamado"]);
        }
        //self::debuga($dadosChamados);
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

    private function AbreChamados($msg = null, $info = null, $alerta = null) {
        //self::debuga($msg);
        $setor = self::getObjChamados()->getAllSetorChamados();
        $prioridade = self::getObjChamados()->getAllPrioridadeChamados();
        self::getObjSmarty()->assign("ARR_SETOR", $setor);
        self::getObjSmarty()->assign("ARR_PRIORIDADE", $prioridade);
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioChamado");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Abrir Chamado");
        self::setMsgTpl($msg, $info, $alerta);

        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarChamado.tpl");
        return $tela;
    }

    public function postCadastraChamado($id, $post, $file) {
        if ($id) {
            //ainda nao sabemos o que vai aqui.            
        } else {
            $idUsuario = self::getIdUsrSessao();
            $post["id_usuario_solicitante"] = $idUsuario;
            $post["id_status"] = STATUS_DEFAULT;
            parent::getClassModel()->cadastrar(self::getXmlForm(), $post, $file);

            //################### preparando Mensagem para retornar a tela ########################
            $alerta = "alertaSucesso"; //classe css
            $msg = "Seu chamado foi aberto!"; //Título principal da mensagem 
            $info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
            //#####################################################################################
        }
        $_GET["aba"] = "abrechamados";
        //$msg=informa a msg,
        //$info=informação se for necessário
        //$alerta=classe de alerta que será usada, basta escolher no css, exibirá diferentes cores;
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function AbreSetor($msg = null, $info = null, $alerta = null) {
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioSetor");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Cadastrar Setor");
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarSetor.tpl");
        return $tela;
    }

    public function postCadastraSetor($id, $post, $file) {
        if ($id) {
            //ainda nao sabemos o que vai aqui.            
        } else {
            $idUsuario = self::getIdUsrSessao();
            $post["id_usu_cad"] = $idUsuario;
            parent::setClassModel(new SetorDAO());
            parent::getClassModel()->cadastrar(parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml"), $post, $file);

            //################### preparando Mensagem para retornar a tela ########################
            $alerta = "alertaSucesso"; //classe css
            $msg = "Setor cadastrado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
            //#####################################################################################
        }
        $_GET["aba"] = "cadastrarsetor";
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function getSetor($msg = null, $info = null, $alerta = null) {
        $setores = self::getObjSetor()->getAllSetor();
        foreach ($setores as $i => $valor) {
            $setores[$i]["link"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=deletaSetor&id=" . $valor["id_setor"]));
        }
        self::getObjSmarty()->assign("TITULO", "Lista de Setores");
        self::getObjSmarty()->assign("SETORES", $setores);
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaSetor.tpl");
        return $tela;
    }

    public function getViewSetor($id = null) {
        $dadosSetor = self::getObjSetor()->getSetorById($id);
        //self::debuga($dadosSetor);
        self::getObjSmarty()->assign("DADOS_SETOR", $dadosSetor);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagViewSetor.tpl");
        return $tela;
    }

    public function getViewChamado($id = null) {
        $dadosChamados = self::getObjChamados()->getChamadoById($id);
        self::getObjSmarty()->assign("CHAMADOS", $dadosChamados);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagViewChamado.tpl");
        return $tela;
    }

    public function getPrioridade() {
        $prioridade = self::getObjPrioridade()->getAllPrioridade();
        //self::debuga($prioridade);
        self::getObjSmarty()->assign("TITULO", "Lista de Prioridades");
        self::getObjSmarty()->assign("PRIORIDADES", $prioridade);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaPrioridade.tpl");
        return $tela;
    }

    public function AbrePrioridade($msg = null, $info = null, $alerta = null) {
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioPrioridade");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Cadastrar Prioridade");
        self::setMsgTpl($msg, $info, $alerta);
        //self::debuga("novo debuga\n",$msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarPrioridade.tpl");
        return $tela;
    }

    public function postCadastraPrioridade($id, $post, $file) {
        if ($id) {
            //ainda nao sabemos o que vai aqui.            
        } else {
            $idUsuario = self::getIdUsrSessao();
            $post["id_usu_cad"] = $idUsuario;
            parent::setClassModel(new PrioridadeDAO());
            parent::getClassModel()->cadastrar(parent::setXmlForm(CHA_XML . "formCadastrarPrioridade.xml"), $post, $file);

            //################### preparando Mensagem para retornar a tela ########################
            $alerta = "alertaSucesso"; //classe css
            $msg = "Prioridade cadastrado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
            //#####################################################################################
        }
        $_GET["aba"] = "cadastrarprioridade";
        //$msg=informa a msg,
        //$info=informação se for necessário
        //$alerta=classe de alerta que será usada, basta escolher no css, exibirá diferentes cores;
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function getStatus() {
        $status = self::getObjStatus()->getAllStatus();
        self::getObjSmarty()->assign("TITULO", "Lista de Prioridades");
        self::getObjSmarty()->assign("STATUS", $status);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaStatus.tpl");
        return $tela;
    }

    public function AbreStatus($msg = null, $info = null, $alerta = null) {
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=salvarFormularioStatus");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("TITULO", "Cadastrar Status");
        self::setMsgTpl($msg, $info, $alerta);
        //self::debuga("novo debuga\n",$msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarStatus.tpl");
        return $tela;
    }

    public function postCadastraStatus($id, $post, $file) {
        if ($id) {
            //ainda nao sabemos o que vai aqui.            
        } else {
            $idUsuario = self::getIdUsrSessao();
            $post["id_usu_cad"] = $idUsuario;
            parent::setClassModel(new StatusDAO());
            parent::getClassModel()->cadastrar(parent::setXmlForm(CHA_XML . "formCadastrarStatus.xml"), $post, $file);

            //################### preparando Mensagem para retornar a tela ########################
            $alerta = "alertaSucesso"; //classe css
            $msg = "Status cadastrado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
            //#####################################################################################
        }
        $_GET["aba"] = "cadastrarstatus";
        //$msg=informa a msg,
        //$info=informação se for necessário
        //$alerta=classe de alerta que será usada, basta escolher no css, exibirá diferentes cores;
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function deletaSetor($get) {
        parent::setClassModel(new SetorDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml");
        $result = self::deleta($get["id"]);
        if ($result) {
            $_GET["aba"] = "setor";
            $alerta = "alertaSucesso"; //classe css
            $msg = "O Setor foi deletado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
        }
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function setMsgTpl($msg, $info, $alerta) {
        if (!is_array($msg) && !empty($msg)) {
            self::getObjSmarty()->assign("MSG_CH", $msg);
        }
        if (!is_array($info) && !empty($info)) {
            self::getObjSmarty()->assign("INFO_CH", $info);
        }
        if (!is_array($alerta) && !empty($alerta)) {
            self::getObjSmarty()->assign("CLASS_ALERTA", $alerta);
        }
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

    private function getObjPrioridade() {
        if ($this->objPrioridade == null) {
            $this->objPrioridade = new PrioridadeDAO();
        }
        return $this->objPrioridade;
    }

    private function getObjStatus() {
        if ($this->objStatus == null) {
            $this->objStatus = new StatusDAO();
        }
        return $this->objStatus;
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