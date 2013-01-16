<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO . "SubDireitosUsuarioDAO.class.php");
require_once(CLASSES_DAO . "UsuariosFgvDAO.class.php");
require_once(FWK_CONTROL . "ControlConfiguracoes.class.php");

/**
 * Classe CRUD de cadastro de direitos para um usuário específico.
 *
 * @author Andre Coura
 * @since 1.0 - 08/11/2009
 */
class CrudSubDireitosUsuario extends AbsCruds {

    public function executa($get, $post, $file) {
        // self::debuga($get);
        self::setXmlForm(FWK_XML_CRUD . "formSubDireitosUsuario.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridSubDireitosUsuario.xml");
        self::setClassModel(new SubDireitosUsuarioDAO());
        self::setStringClass("" . __CLASS__ . "");
        //self::debuga($get,$post);
        switch ($get['a']) {
            case "formPerms":
                self::listaPermissoes($get, $post);
                break;
            case "formAddPermissoes":
                if ($get["msg"])
                    parent::getObjSmarty()->assign("MSG", $get["msg"]);
                self::formAddPermissoes($get, $post);
                break;
            case "salvarPermissoes":
                self::salvarPermissoes($get, $post);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    private function listaPermissoes($get = null, $post = null) {
        //self::debuga($get);
        self::setXmlGrid(FWK_XML_CRUD . "gridSubPermissoes.xml");
        $intDocsGrupo = self::getDocsGrupo();
        if ($intDocsGrupo > 0) {
            self::getObjGrid()->setVariavelWhere2($intDocsGrupo);
        }
        $urlVoltar = FormataLink::getUrlAtualDoNavegador();
        self::getObjGrid()->setUtf8Decode(true);
        self::getObjGrid()->setXmlGrid(self::getXmlGrid());
        self::getObjGrid()->setArrPost($post);
        self::getObjGrid()->setArrGet($get);
        self::getObjGrid()->setIdReferencia($get["id"]);
        //seta um array com atributos para as funcoes de busca especificada nas tags infos do grid xml
        self::getObjGrid()->setArrAttrInfosGrid(array($get["id"]));
        self::getObjGrid()->setXmlParam("&idUser=" . $get["id"]);
        self::getObjGrid()->showGrid();
    }

    private function formAddPermissoes($get = null, $post = null) {
        $direito = self::getObjDireito()->getNomeDireito($get["id"]);
        $nomeUser = self::getObjUsuario()->getNomeUsuarioById($get["idUser"]);
        $arrIdPermissoes = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjDireito()->getPermissoes($get["id"], $get["idUser"]));
        //self::debuga($arrIdPermissoes);
        foreach ($arrIdPermissoes as $valor) {
            if ((int)$valor == 1) {
                self::getObjSmarty()->assign("CHCAD", true);
            } else if ((int)$valor == 2) {
                self::getObjSmarty()->assign("CHALT", true);
            } else if ((int)$valor == 3) {
                self::getObjSmarty()->assign("CHEXC", true);
            }
        }
        self::getObjSmarty()->assign("TITULO_MARCADOR", "ETAPA 3 de 3");
        self::getObjSmarty()->assign("MARCADOR_GRID",self::getObjSmarty()->fetch(FWK_HTML_GRID . "marcadorGrid.tpl"));
        $salvar = "?c=" . self::getObjCrypt()->cryptData("CrudSubDireitosUsuario&a=salvarPermissoes&idDir=" . $get["id"] . "&idUser=" . $get["idUser"]);
        $voltar = "?c=" . self::getObjCrypt()->cryptData("CrudSubDireitosUsuario&a=formPerms&id=".$get["idUser"]);
        self::getObjSmarty()->assign("NOME_USUARIO", $nomeUser);
        self::getObjSmarty()->assign("DIREITO", $direito);
        self::getObjSmarty()->assign("FORM", "Permissões Por Direito De Usuário");
        self::getObjSmarty()->assign("SALVAR", $salvar);
        self::getObjSmarty()->assign("VOLTAR", $voltar);
        self::getObjHttp()->escreEm("CORPO", FWK_HTML_VIEWS . "formAcoesPorDireitoDeUsuarios.tpl");
    }

    private function salvarPermissoes($get = null, $post = null) {
        $subPermissoes = array_values($post);
        self::getObjDireito()->limpaTabelaPermissoesUsuarios($get["idDir"], $get["idUser"]);
        foreach ($subPermissoes as $valor) {
            self::getObjDireito()->inserirPermissoesUsuarios($get["idDir"], $get["idUser"], $valor);
        }
        self::vaiPara(self::getStringClass() . "&a=formAddPermissoes&id=" . $get["idDir"] . "&idUser=" . $get["idUser"] . "&msg=Permissões alteradas com sucesso!");
    }

    private function getObjUsuario() {
        if ($this->objUsuario == null) {
            $this->objUsuario = new UsuariosFgvDAO();
        }
        return $this->objUsuario;
    }

    private function getObjDireito() {
        if ($this->objDireito == null) {
            $this->objDireito = new SubDireitosUsuarioDAO();
        }
        return $this->objDireito;
    }

    public function getCtrlConfiguracoes() {
        if ($this->objCtrlConfiguracoes == null)
            $this->objCtrlConfiguracoes = new ControlConfiguracoes();
        return $this->objCtrlConfiguracoes;
    }

}

?>