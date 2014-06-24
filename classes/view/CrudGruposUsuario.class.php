<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "GruposUsuario.class.php");
require_once(FWK_DAO . "UsuariosDAO.class.php");
require_once(FWK_DAO . "LogDAO.class.php");

class CrudGruposUsuario extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formGruposUsuario.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridGruposUsuario.xml");
        self::setClassModel(new GruposUsuario());
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
                self::postAlteraGrupos($get['id'], $post, $file);
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

    protected function postAlteraGrupos($id, $post, $file) {
        try {
            $direitosAnteriores = self::getObjUsuario()->getGruposUsuarioById($id);
            $dadosUser = Utf8Parsers::arrayUtf8Encode(self::getObjUsuario()->getDadosUsuariosById($id));
            self::getClassModel()->alterar($id, self::getXmlForm(), $post, $file);
            $direitosAtualizados = self::getObjUsuario()->getGruposUsuarioById($id);
            self::logGruposPorUsuario(LOG_ALTERACAO_GRUPO_USER, $id, $direitosAnteriores, $direitosAtualizados, $dadosUser);
            self::vaiPara(self::getStringClass() . "&msg=Item alterado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        }
    }

    public function logGruposPorUsuario($descricao, $id, $dadosAnteriores, $dadosNovos, $dadosUser) {
        $arrTextoLog = FormataString::raw_json_encode(array('dados_anteriores' => $dadosAnteriores, 'dados_novos' => $dadosNovos));
        $nome = $dadosUser["nome_usuario"];
        $email = $dadosUser["email_usuario"];
        //SALVAR LOG
        $valores = FormataPost::montaArrayLogDireitosUser($descricao, self::getObjUsrSessao()->getIdUsuario(), self::getObjUsrSessao()->getNomeUsuario(), self::getObjUsrSessao()->getEmailUser(), $id, utf8_decode($nome), $email, $arrTextoLog);
        self::getObjLog()->registraLog("fwk_log_grupo_usuario", $valores);
    }

    protected function formAltera($id) {
        //self::debuga(self::getObjCrypt()->decryptData("Zm9ybXVsYXJpb3MmZj1DcnVkVXN1YXJpb3NHcnVwbyY="));
        $nome = self::getObjUsuario()->getNomeUsuarioById($id);
        self::getObjSmarty()->assign("NOME", utf8_encode($nome));
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

    private function getObjUsuario() {
        if ($this->objUsuario == null)
            $this->objUsuario = new UsuariosDAO();
        return $this->objUsuario;
    }

    private function getObjLog() {
        if ($this->getObjLog == null) {
            $this->getObjLog = new LogDAO();
        }
        return $this->getObjLog;
    }

}

?>