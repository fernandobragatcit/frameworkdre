<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "UsuariosGrupo.class.php");
require_once(FWK_DAO . "GrupoUsuarioDAO.class.php");
require_once(FWK_DAO . "LogDAO.class.php");

class CrudUsuariosGrupo extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formUsuariosGrupo.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridUsuariosGrupo.xml");
        self::setClassModel(new UsuariosGrupo());
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "formCad":
                self::formCadastro();
                break;
            case "cadastra":
                self::postCadastro($post, $file);
                break;
            case "formAlt":
                self::formAltera($get['id']);
                break;
            case "altera":
                //self::debuga("opss");
                self::postAlteraUsuariosGrupo($get['id'], $post, $file);
                break;
            case "deleta":
                self::deleta($get['id']);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    protected function postAlteraUsuariosGrupo($id, $post, $file) {
        try {
            $direitosAnteriores = self::getObjGrupos()->getUsuariosPorGrupoById($id);
            $nomeGrupo = self::getObjGrupos()->getNomeGrupoById($id);
            self::getClassModel()->alterar($id, self::getXmlForm(), $post, $file);
            $direitosAtualizados = self::getObjGrupos()->getUsuariosPorGrupoById($id);
            self::logUsuariosPorGrupo(LOG_ALTERACAO_USUARIOS_GRUPO, $id, $direitosAnteriores, $direitosAtualizados, $nomeGrupo);
            self::vaiPara(self::getStringClass() . "&msg=Item alterado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        }
    }

    public function logUsuariosPorGrupo($descricao, $id, $dadosAnteriores, $dadosNovos, $nomeGrupo) {
        $arrTextoLog = FormataString::raw_json_encode(array('dados_anteriores' => $dadosAnteriores, 'dados_novos' => $dadosNovos));
        //SALVAR LOG
        $valores = FormataPost::montaArrayLogDireitosGrupo($descricao, self::getObjUsrSessao()->getIdUsuario(), self::getObjUsrSessao()->getNomeUsuario(), self::getObjUsrSessao()->getEmailUser(), $id, $nomeGrupo, $arrTextoLog);
        self::getObjLog()->registraLog("fwk_log_usuario_grupo", $valores);
    }

    protected function formAltera($id) {
        $grupo = self::getObjGrupos()->getNomeGrupoById($id);
        self::getObjSmarty()->assign("GRUPO", $grupo);
        $arrDados = self::getClassModel()->buscaCampos($id);
        if (isset($arrDados["id_foto"]) || $arrDados["id_foto"] != "") {
            self::getObjSmarty()->assign("ID_FOTO", $arrDados["id_foto"]);
        }
        if (!isset($arrDados["status"])) {
            $arrDados["status"] = "S";
        }
        self::getClassModel()->setTipoForm(self::getTipoForm());
        self::getClassModel()->preencheForm(self::getXmlForm(), $id, self::getStringClass());
    }

    private function getObjGrupos() {
        if ($this->objGrp == null) {
            $this->objGrp = new GrupoUsuarioDAO();
        }
        return $this->objGrp;
    }

    private function getObjLog() {
        if ($this->getObjLog == null) {
            $this->getObjLog = new LogDAO();
        }
        return $this->getObjLog;
    }

}

?>