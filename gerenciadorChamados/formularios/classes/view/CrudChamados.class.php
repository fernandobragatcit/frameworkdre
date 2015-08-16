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
            case "deletaPrioridade":
                self::deletaPrioridade($get);
                break;
            case "deletaStatus":
                self::deletaStatus($get);
                break;
            case "editaSetor":
                self::formAlteraSetor($get["id"]);
                break;
            case "editaPrioridade":
                self::formAlteraPrioridade($get["id"]);
                break;
            case "editaStatus":
                self::formAlteraStatus($get["id"]);
                break;
            default:
                self::exibeTelaChamados();
                break;
        }
    }

    //TELA PRINCIPAL
    public function exibeTelaChamados($get = null, $msg = null, $info = null, $alerta = null) {
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
            } else if ($_GET["aba"] == "prioridade") {
                $strTela = self::getPrioridade($msg, $info, $alerta);
            } else if ($_GET["aba"] == "status") {
                $strTela = self::getStatus($msg, $info, $alerta);
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

    public function getViewChamado($id = null) {
        $dadosChamados = self::getObjChamados()->getChamadoById($id);
        self::getObjSmarty()->assign("CHAMADOS", $dadosChamados);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagViewChamado.tpl");
        return $tela;
    }

    public function AbreSetor($msg = null, $info = null, $alerta = null) {
        if (self::getIdForm()) {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioSetor&id=" . self::getIdForm());
        } else {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioSetor");
        }
        self::getObjSmarty()->assign("TITULO", "Cadastrar Setor");
        self::getObjSmarty()->assign("ABA_SELECIONADA", "abaCadastrarSetor");
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarSetor.tpl");
        return $tela;
    }

    public function postCadastraSetor($id, $post, $file) {
        parent::setClassModel(new SetorDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml");
        if ($id) {
            parent::getClassModel()->alterar($id, parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Setor Alterado com Sucesso!"; //Título principal da mensagem 
        } else {
            parent::getClassModel()->cadastrar(parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Setor Cadastrado com Sucesso!"; //Título principal da mensagem 
        }
        $_GET["aba"] = "cadastrarsetor";
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function getSetor($msg = null, $info = null, $alerta = null) {
        $setores = self::getObjSetor()->getAllSetor();
        foreach ($setores as $i => $valor) {
            $setores[$i]["deletar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=deletaSetor&id=" . $valor["id_setor"]));
            $setores[$i]["editar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=editaSetor&id=" . $valor["id_setor"]));
        }
        self::getObjSmarty()->assign("TITULO", "Lista de Setores");
        self::getObjSmarty()->assign("SETORES", $setores);
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaSetor.tpl");
        return $tela;
    }

    public function AbrePrioridade($msg = null, $info = null, $alerta = null) {
        if (self::getIdForm()) {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioPrioridade&id=" . self::getIdForm());
        } else {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioPrioridade");
        }
        self::getObjSmarty()->assign("TITULO", "Cadastrar Prioridade");
        self::getObjSmarty()->assign("ABA_SELECIONADA", "abaCadastrarPrioridade");
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarPrioridade.tpl");
        return $tela;
    }

    public function postCadastraPrioridade($id, $post, $file) {
        parent::setClassModel(new PrioridadeDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarPrioridade.xml");
        if ($id) {
            parent::getClassModel()->alterar($id, parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Prioridade Alterada com Sucesso!"; //Título principal da mensagem 
        } else {
            parent::getClassModel()->cadastrar(parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Setor Cadastrado com Sucesso!"; //Título principal da mensagem 
        }
        $_GET["aba"] = "cadastrarprioridade";
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function getPrioridade($msg = null, $info = null, $alerta = null) {
        $prioridade = self::getObjPrioridade()->getAllPrioridade();
        foreach ($prioridade as $i => $valor) {
            $prioridade[$i]["deletar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=deletaPrioridade&id=" . $valor["id_prioridade"]));
            $prioridade[$i]["editar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=editaPrioridade&id=" . $valor["id_prioridade"]));
        }
        self::getObjSmarty()->assign("TITULO", "Lista de Prioridades");
        self::getObjSmarty()->assign("PRIORIDADES", $prioridade);
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaPrioridade.tpl");
        return $tela;
    }

    public function AbreStatus($msg = null, $info = null, $alerta = null) {
        if (self::getIdForm()) {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioStatus&id=" . self::getIdForm());
        } else {
            self::setLinkFormSalvar("CrudChamados&a=salvarFormularioStatus");
        }
        self::getObjSmarty()->assign("TITULO", "Cadastrar Status");
        self::getObjSmarty()->assign("ABA_SELECIONADA", "abaCadastrarStatus");
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "formCadastrarStatus.tpl");
        return $tela;
    }

    public function postCadastraStatus($id, $post, $file) {
        parent::setClassModel(new StatusDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarStatus.xml");
        if ($id) {
            parent::getClassModel()->alterar($id, parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Status Alterado com Sucesso!"; //Título principal da mensagem 
        } else {
            parent::getClassModel()->cadastrar(parent::getXmlForm(), $post, $file);
            //MENSAGENS DE RETORNO
            $alerta = "alertaSucesso"; //classe css
            $msg = "Status Cadastrado com Sucesso!"; //Título principal da mensagem 
        }
        $_GET["aba"] = "cadastrarstatus";
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function getStatus($msg = null, $info = null, $alerta = null) {
        $status = self::getObjStatus()->getAllStatus();
        foreach ($status as $i => $valor) {
            $status[$i]["deletar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=deletaStatus&id=" . $valor["id_status"]));
            $status[$i]["editar"] = ("?c=" . self::getObjCrypt()->cryptData("CrudChamados&a=editaStatus&id=" . $valor["id_status"]));
        }
        self::getObjSmarty()->assign("TITULO", "Lista de Status");
        self::getObjSmarty()->assign("STATUS", $status);
        self::setMsgTpl($msg, $info, $alerta);
        $tela = parent::getObjSmarty()->fetch(FWK_TPLS_CH . "tagListaStatus.tpl");
        return $tela;
    }

    public function deletaSetor($get) {
        parent::setClassModel(new SetorDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml");
        $result = self::deleta($get["id"]);
        if ($result) {
            self::getObjSmarty()->assign("ABA_SELECIONADA", "abasetor");
            $_GET["aba"] = "setor";
            $alerta = "alertaSucesso"; //classe css
            $msg = "O Setor foi deletado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
        }
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function deletaPrioridade($get) {
        parent::setClassModel(new PrioridadeDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarPrioridade.xml");
        $result = self::deleta($get["id"]);
        if ($result) {
            self::getObjSmarty()->assign("ABA_SELECIONADA", "abaPrioridade");
            $_GET["aba"] = "prioridade";
            $alerta = "alertaSucesso"; //classe css
            $msg = "A Prioridade foi deletada com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
        }
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function deletaStatus($get) {
        parent::setClassModel(new StatusDAO());
        parent::setXmlForm(CHA_XML . "formCadastrarStatus.xml");
        $result = self::deleta($get["id"]);
        if ($result) {
            self::getObjSmarty()->assign("ABA_SELECIONADA", "abaStatus");
            $_GET["aba"] = "status";
            $alerta = "alertaSucesso"; //classe css
            $msg = "Status foi deletado com sucesso!"; //Título principal da mensagem 
            //$info = "Informações a respeito serão encaminhadas ao seu e-mail cadastrado no portal"; //Qualquer Informação se for necessária.
        }
        self::exibeTelaChamados(null, $msg, $info, $alerta);
    }

    public function formAlteraSetor($id = null) {
        parent::setXmlForm(CHA_XML . "formCadastrarSetor.xml");
        parent::setClassModel(new SetorDAO());
        $arrDadosSetor = self::getClassModel()->buscaCampos($id);
        $arrDadosSetor = Utf8Parsers::arrayUtf8Encode($arrDadosSetor);
        self::getObjSmarty()->assign("setor", $arrDadosSetor["setor"]);
        self::getObjSmarty()->assign("email_setor", $arrDadosSetor["email_setor"]);
        $_GET["aba"] = "cadastrarsetor";
        self::setIdForm($id);
        self::exibeTelaChamados();
    }

    public function formAlteraPrioridade($id = null) {
        parent::setXmlForm(CHA_XML . "formCadastrarPrioridade.xml");
        parent::setClassModel(new PrioridadeDAO());
        $arrDadosPrioridade = self::getClassModel()->buscaCampos($id);
        $arrDadosPrioridade = Utf8Parsers::arrayUtf8Encode($arrDadosPrioridade);
        self::getObjSmarty()->assign("prioridade", $arrDadosPrioridade["prioridade"]);
        $_GET["aba"] = "cadastrarprioridade";
        self::setIdForm($id);
        self::exibeTelaChamados();
    }

    public function formAlteraStatus($id = null) {
        parent::setXmlForm(CHA_XML . "formCadastrarStatus.xml");
        parent::setClassModel(new StatusDao());
        $arrDadosStatus = self::getClassModel()->buscaCampos($id);
        $arrDadosStatus = Utf8Parsers::arrayUtf8Encode($arrDadosStatus);
        self::getObjSmarty()->assign("status", $arrDadosStatus["status"]);
        $_GET["aba"] = "cadastrarstatus";
        self::setIdForm($id);
        self::exibeTelaChamados();
    }

    //FUNÇÃO QUE DA UM SET NA MENSAGEM
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

    //FUNÇÃO QUE CRYPTOGRAFA LINK
    public function setLinkFormSalvar($params) {
        $salvar = "?c=" . self::getObjCrypt()->cryptData($params);
        self::getObjSmarty()->assign("SALVAR", $salvar);
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