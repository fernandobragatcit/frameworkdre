<?php

require_once (FWK_EXCEPTION . "XMLException.class.php");
require_once (FWK_EXCEPTION . "ElementsException.class.php");
require_once (FWK_CONTROL . "ControlSmarty.class.php");
require_once (FWK_CONTROL . "ControlXML.class.php");
require_once (FWK_MODEL . "Form.class.php");
require_once (FWK_COMP . "FactoryCompHtml.class.php");
require_once(FWK_CONTROL . "ControlJS.class.php");
require_once(FWK_CONTROL . "ControlSessao.class.php");

class ControlForm {

    private $objSmarty;
    private $objXML;
    private $objForm;
    private $id_tela;
    private $strJsVal = "";
    private $objFactoryComps;
    private $idReferencia;
    private $idReferencia2;

    public function __construct($strXML = "") {
        if ($strXML != "")
            self::setObjXml($strXML);
    }

    public function setIdReferencia($intIdRef) {
        $this->idReferencia = $intIdRef;
    }

    public function getIdReferencia() {
        return $this->idReferencia;
    }

    public function setIdReferencia2($intIdRef) {
        $this->idReferencia2 = $intIdRef;
    }

    public function getIdReferencia2() {
        return $this->idReferencia2;
    }

    public function setObjXml($strXML) {
        try {
            $objCtrlXML = new ControlXML($strXML);
            $this->objXML = $objCtrlXML->getXML();
        } catch (XMLException $e) {
            die($e->__toString());
        }
    }

    private function getObjXml() {
        if ($this->objXML == null)
            throw new XMLException("Não foi passado para o Formulário o XML para a criação do mesmo");
        return $this->objXML;
    }

    public function getObjForm() {
        if ($this->objForm == null)
            $this->objForm = new Form(self::getObjXml(), self::getObjSmarty());
        return $this->objForm;
    }

    protected function getObjSmarty() {
        if ($this->objSmarty == null)
            $this->objSmarty = ControlSmarty::getSmarty();
        return $this->objSmarty;
    }

    public function setActionForm($strAction) {
        self::getObjForm()->setActionForm($strAction);
    }

    public function setTplsFile($strFile) {
        self::getObjForm()->setCaminhoTpls($strFile);
    }

    public function setTplsForm($strTplForm) {
        self::getObjForm()->setTplForm($strTplForm);
    }

    public function registraForm() {
        self::regConteudoForm();
        self::getObjForm()->getEstruturaForm();
    }

    public function setTipoForm($strTipoForm) {
        self::getObjForm()->setTipoForm($strTipoForm);
    }

    public function setEstruturaForm($strTpl) {
        self::getObjForm()->setTplEstruturaForm($strTpl);
    }

    public function registraFormValues($arrDados, $utf8 = false, $setEstrutura = true) {
        self::regFormValues($arrDados, $utf8);
        if ($setEstrutura)
            self::getObjForm()->getEstruturaForm();
    }

    public function registraSemEstrutura($arrDados) {
        self::regFormValues($arrDados);
    }

    public function setId($id) {
        $this->id_tela = $id;
    }

    public function getIdTela() {
        return $this->id_tela;
    }

    public function setErroCampoForm($strCampo, $strErro) {
        $this->strJsVal.="setFieldErroCampo('" . $strCampo . "');\n";
        self::getObjSmarty()->assign($strCampo . "_error", $strErro);
    }

    public function getFactoryCompHtml() {
        if ($this->objFactoryComps == null)
            $this->objFactoryComps = new FactoryCompHtml();
        return $this->objFactoryComps;
    }

    public function setNomeForm($strNomeForm) {
        self::getObjForm()->setNomeForm($strNomeForm);
    }

    private function regFormValues($arrDados, $utf8 = false) {
        try {
            self::validaDados(self::getObjXml()->children()->campos);
        } catch (ElementsException $e) {
            die($e->__toString());
        }
        if ($utf8) {
            foreach ($arrDados as $nomeCampo => $fieldData) {
                $arrDados[$nomeCampo] = $fieldData;
            }
        }
        self::setJsForm();
        self::getFactoryCompHtml()->setClasseAtual(self::getObjXml()->attributes()->classe);
        self::getFactoryCompHtml()->setIdReferencia(self::getIdReferencia());
        self::getFactoryCompHtml()->setIdReferencia2(self::getIdReferencia2());
        foreach (self::getObjXml()->children()->campos as $data) {
            switch ((string) $data->attributes()->type) {
                case "listBox":
                    self::getFactoryCompHtml()->buildComp($data, self::getIdTela());
                    break;
                case "multFieldsWithSource":
                    self::getFactoryCompHtml()->buildComp($data, $arrDados);
                    break;
                case "checkbox":
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name] == "S" ? "true" : "false");
                    break;
                case "password_confirme":
                    self::setJsComparaCampos($data->attributes()->id, $data->attributes()->comparaCom, $data->attributes()->label, $data->attributes()->mensErro);
                    $data->attributes()->type = "password";
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name]);
                    break;
                case "email_confirme":
                    self::setJsComparaCampos($data->attributes()->id, $data->attributes()->comparaCom, $data->attributes()->label, $data->attributes()->mensErro);
                    $data->attributes()->type = "text";
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name]);
                    break;
                /* case "email":
                  self::setJsCampoEspecial($data->attributes()->id,$data->attributes()->label,$data->attributes()->type,$data->attributes()->mensErro);
                  $data->attributes()->type = "text";
                  self::getFactoryCompHtml()->buildComp($data,$arrDados[(string)$data->attributes()->name]);
                  break; */
                case "data":
                case "date":
                    self::setJsCampoEspecial($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mensErro);
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name]);
                    break;
                case "integer":
                case "inteiro":
                case "cpf":
                case "cnpj":
                case "longlat":
                case "email":
                case "telefone":
                case "cep":
                    self::setJsCampoEspecial($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mensErro);
                    if (isset($data->attributes()->mask) && $data->attributes()->mask != "")
                        self::setJsMascaraCampos($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mask, $data->attributes()->mensErro);
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name]);
                    break;
                default:
                    self::getFactoryCompHtml()->buildComp($data, $arrDados[(string) $data->attributes()->name]);
                    break;
            }
            if ($data->attributes()->type != "file" && $data->attributes()->type != "addMultCampo") {
                if (isset($data->attributes()->obrigatorio) && $data->attributes()->obrigatorio == "true")
                    self::setJsCampoObrigatorio($data->attributes()->id, $data->attributes()->label, $data->attributes()->mensErro);
                if (isset($data->attributes()->maxlength) && $data->attributes()->maxlength != "") {
                    $tamMax = (is_numeric($data->attributes()->maxlength)) ?
                            (int) $data->attributes()->maxlength : 0;
                    $tamMin = (is_numeric($data->attributes()->minlength)) ?
                            (int) $data->attributes()->minlength : 0;
                    self::setJsTamanhoCampos($data->attributes()->id, $data->attributes()->label, $tamMax, $tamMin);
                }
            }
        }
        self::getButtons("Alterar");
        self::validacaoForm();
    }

    private function regConteudoForm() {
        try {
            self::validaDados(self::getObjXml()->children()->campos);
        } catch (ElementsException $e) {
            die($e->__toString());
        }
        self::setJsForm();
        self::getFactoryCompHtml()->setClasseAtual(self::getObjXml()->attributes()->classe);
        self::getFactoryCompHtml()->setIdReferencia(self::getIdReferencia());
        self::getFactoryCompHtml()->setIdReferencia2(self::getIdReferencia2());
        foreach (self::getObjXml()->children()->campos as $data) {
            switch ((string) $data->attributes()->type) {
                case "password_confirme":
                    self::setJsComparaCampos($data->attributes()->id, $data->attributes()->comparaCom, $data->attributes()->label, $data->attributes()->mensErro);
                    $data->attributes()->type = "password";
                    self::getFactoryCompHtml()->buildComp($data);
                    break;
                case "email_confirme":
                    self::setJsComparaCampos($data->attributes()->id, $data->attributes()->comparaCom, $data->attributes()->label, $data->attributes()->mensErro);
                    $data->attributes()->type = "text";
                    self::getFactoryCompHtml()->buildComp($data);
                    break;
                case "email":
                    self::setJsCampoEspecial($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mensErro);
                    $data->attributes()->type = "text";
                    self::getFactoryCompHtml()->buildComp($data);
                    break;
                case "inteiro":
                case "integer":
                    self::setJsCampoEspecial($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mensErro);
                    self::setJsMascaraCampos($data->attributes()->id, $data->attributes()->label, $data->attributes()->type, $data->attributes()->mask, $data->attributes()->mensErro);
                    $data->attributes()->type = "text";
                    self::getFactoryCompHtml()->buildComp($data);
                    break;
                case "captcha":
                    self::getFactoryCompHtml()->buildComp($data);
                    if (!isset($data->attributes()->mensErro) || $data->attributes()->mensErro == "")
                        $data->attributes()->mensErro = "Texto digitado não corresponde à imagem";
                    self::setJsCampoObrigatorio($data->attributes()->id, $data->attributes()->label, $data->attributes()->mensErro);
                    break;
                default:
                    self::getFactoryCompHtml()->buildComp($data);
                    if (isset($data->attributes()->obrigatorio) && $data->attributes()->obrigatorio == "true")
                        self::setJsCampoObrigatorio($data->attributes()->id, $data->attributes()->label, $data->attributes()->mensErro);
                    break;
            }
            if ($data->attributes()->type != "addMultCampo" && isset($data->attributes()->maxlength) && $data->attributes()->maxlength != "") {
                $tamMax = (is_numeric((string) $data->attributes()->maxlength)) ?
                        (int) $data->attributes()->maxlength : 0;
                $tamMin = (is_numeric((string) $data->attributes()->minlength)) ?
                        (int) $data->attributes()->minlength : 0;
                self::setJsTamanhoCampos($data->attributes()->id, $data->attributes()->label, $tamMax, $tamMin);
            }
        }
        self::getButtons();
        self::validacaoForm();
    }

    private function getButtons($label = "") {
        self::getFactoryCompHtml()->setClasseAtual((string) self::getObjXml()->attributes()->classe);
        self::getFactoryCompHtml()->setTipo((string) self::getObjXml()->attributes()->tipo);
        self::getFactoryCompHtml()->setCategoria((string) self::getObjXml()->attributes()->categoria);
        if (self::getObjXml()->children()->buttons) {
            //verificar se botao salvar poderá aparecer no Form
            foreach (self::getObjXml()->children()->buttons->button as $btn) {
                if (empty($btn->attributes()->direito)) {
                    self::getFactoryCompHtml()->buildBtn($btn, $label);
                } else {
                    if (trim($_SESSION["ACAO_FORM"]) == "formAlt") {
                        if (self::verificaPermissoesAlterar(self::getIdUsrSessao(), $btn->attributes()->direito)) {
                            self::getFactoryCompHtml()->buildBtn($btn, $label);
                        }
                    } else {
                        self::getFactoryCompHtml()->buildBtn($btn, $label);
                    }
                }
            }
        }
    }

    private function validaDados($objDatas) {
        foreach ($objDatas as $simpleData) {
            if (!isset($simpleData->attributes()->type) || $simpleData->attributes()->type == "")
                throw new ElementsException(FORM_INPUT_TYPE_ERRO);
            if (!isset($simpleData->attributes()->name) || $simpleData->attributes()->name == "")
                throw new ElementsException(FORM_INPUT_NAME_ERRO);
            if (!isset($simpleData->attributes()->id) || $simpleData->attributes()->id == "")
                $simpleData->attributes()->id = $simpleData->attributes()->name;
        }
    }

    private function setJsForm() {
        $objCtrlJs = ControlJS::getJS();
        //$objCtrlJs->addJs(FWK_JS."mascaras/jquery.maskedinput-1.1.4.js");
        $objCtrlJs->addJs(FWK_JS . "mascaras/jquery.numeric.js");
        $objCtrlJs->addJs(FWK_JS . "mascaras/jquery.floatnumber.js");
        $objCtrlJs->addJs(FWK_JS . "mascaras/jquery.maskMoney.js");
        $objCtrlJs->addJs(FWK_JS . "mascaras/jquery.alphanumeric.js");


        $objCtrlJs->addJs(FWK_JS . "jquery-ui.min.js");
        $objCtrlJs->addJs(FWK_JS . "jquery.ui.datepicker-pt-BR.js");
        $objCtrlJs->addJs(FWK_JS . "jquery.maskedinput-1.2.2.min.js");

        $objCtrlJs->addJs(FWK_JS . "actionsForm.js");
    }

    private function setJsCampoObrigatorio($idCampo, $labelCampo, $strTxtErro = "") {
        if ($strTxtErro == null || $strTxtErro == "")
            $strTxtErro = "Campo Obrigatorio";
        $this->strJsVal.= "setCampoObrigatorio('" . $idCampo . "','" . $labelCampo . "','" . $strTxtErro . "'); \n";
    }

    private function setJsCampoEspecial($idCampo, $labelCampo, $tipoCampo, $strTxtErro) {
        if ($strTxtErro == null || $strTxtErro == "")
            $strTxtErro = "Campo Invalido";
        $this->strJsVal.= "setCampoEspecial('" . $idCampo . "','" . $labelCampo . "','" . $tipoCampo . "','" . $strTxtErro . "'); \n";
    }

    private function setJsComparaCampos($idCampo, $idCampoComp, $labelCampo, $strTxtErro) {
        if ($strTxtErro == null || $strTxtErro == "")
            $strTxtErro = "Campo Invalido";
        $this->strJsVal.= "setCampoComparavel('" . $idCampo . "','" . $idCampoComp . "','" . $labelCampo . "','" . $strTxtErro . "'); \n";
    }

    private function setJsTamanhoCampos($idCampo, $labelCampo, $tamMáximo, $tamMinimo) {
        $this->strJsVal.= "setTamanhoCampos('" . $idCampo . "','" . $labelCampo . "','" . $tamMáximo . "','" . $tamMinimo . "'); \n";
    }

    private function setJsMascaraCampos($idCampo, $labelCampo, $tipoCampo, $strMask, $strTxtErro) {
        $this->strJsVal.= "setMascaraCampo('" . $idCampo . "','" . $labelCampo . "','" . $tipoCampo . "','" . $strMask . "','" . $strTxtErro . "'); \n";
    }

    private function validacaoForm() {
        self::getObjSmarty()->assign("VALIDACAO_FORM_JS", $this->strJsVal);
    }

    protected function getObjUsrSessao() {
        if ($this->objUserSess == null) {
            $objCtrlSess = new ControlSessao();
            $this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
        }
        return $this->objUserSess;
    }

    private function getIdUsrSessao() {
        return self::getObjUsrSessao()->getIdUsuario();
    }

    /*     * * Método "verificaPermissoesAlterar", para verificar se usuário tem permissão para alterar o formulário setado.
     * @author Fernando Braga
     * @since 3.0 - 02/01/2013
     */

    private function verificaPermissoesAlterar($idUser = null, $direitoBtn = null) {
        $status = false;
        $subDireitos = self::getPermissoesUsuario($idUser, $direitoBtn);
        if (!empty($subDireitos)) {
            if (in_array(ALTERAR, $subDireitos)) {
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

    protected function getObjGrid() {
        if ($this->objGrid == null) {
            $this->objGrid = ControlGrid::getGrid();
        }
        return $this->objGrid;
    }

}

?>