<?php

require_once (FWK_MODEL . "AbsCruds.class.php");
require_once(BIB_JSON);

abstract class AbsModelFormsCha extends AbsCruds {

    public function __construct() {
        parent::setTipoForm("formularios");
        self::setJsFormularios();
    }

    protected function setMenuEsquerdo($strTplMenu) {
        $strMenuEsq = parent::getObjSmarty()->fetch($strTplMenu);
        parent::getObjSmarty()->assign("INV_MENU_EQUERDO", $strMenuEsq);
    }

    protected function setStepsFormulario($strTplMenu) {
        $strMenuEsq = parent::getObjSmarty()->fetch($strTplMenu);
        parent::getObjSmarty()->assign("INV_STEPS", $strMenuEsq);
    }

    protected function setSubTituloForm($strSubTitulo) {
        parent::getObjSmarty()->assign("SUB_TITULO_FORM", $strSubTitulo);
    }

    protected function setStatusStep($strStep) {
        parent::getObjSmarty()->assign($strStep, "selectedStatus");
    }

    protected function setJsFormularios() {
        ControlJS::getJS()->addJs(FORMS_JS . "moduloFormAcoes.js");
        ControlJS::getJS()->addJs(self::getCtrlConfiguracoes()->getUrlSite() . "arquivos/js/tinymce/tiny_mce.js");
    }

    public function getObjJson() {
        if ($this->objJason == null)
            $this->objJason = new Json();
        return $this->objJason;
    }

    protected function setKeyCaixaBaixa($arrDados) {
        $arrNovoDados = array();
        if (count($arrDados) > 0) {
            foreach ($arrDados as $key => $dados) {
                $arrNovoDados[strtolower($key)] = $dados;
            }
        }
        return $arrNovoDados;
    }

    protected function geraPdf($strTela, $nomeArquivo) {
        require_once(BIBLIOTECAS_DRE . "MPDF53/mpdf.php");
        $mpdf = new mPDF('', '', 11, 'Arial', 0, 0, 5, 0, 0, 0, 'L');
        $mpdf->WriteHTML($strTela);
//		$mpdf->Output();
        $mpdf->Output($nomeArquivo, 'D');
        exit();
    }

    protected function trataTelefonesNaoObrigatorio($campo) {
        if ($campo == "(__) ____-____") {
            $campo = "";
        }
        return $campo;
    }

    protected function parseHoraMinuto($hora) {
        return substr($hora, 0, 5);
    }

}

?>