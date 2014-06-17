<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "DireitosUsuarioAdmin.class.php");
require_once(FWK_DAO . "UsuariosDAO.class.php");
require_once(FWK_DAO . "LogDAO.class.php");

/**
 * Classe CRUD de cadastro de direitos para um usuário específico.
 *
 * @author Andre Coura
 * @since 1.0 - 08/11/2009
 */
class CrudDireitosUsuario extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formDireitosUsuario.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridDireitosUsuario.xml");
        self::setClassModel(new DireitosUsuarioAdmin());
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "formAlt":
                self::formAltera($get['id']);
                break;
            case "altera":
                self::postAlteraDireito($get['id'], $post, $file);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    private function postAlteraDireito($id, $post, $file) {
        try {
            $direitosAnteriores = self::getObjUsuario()->getDireitosUsuarioById($id);
            $dadosUser = Utf8Parsers::arrayUtf8Encode(self::getObjUsuario()->getDadosUsuariosById($id));
            self::getClassModel()->alterar($id, self::getXmlForm(), $post, $file);
            $direitosAtualizados = self::getObjUsuario()->getDireitosUsuarioById($id);
            self::logDireitos(LOG_ALTERACAO_DIREITO_USER, $id, $direitosAnteriores, $direitosAtualizados, $dadosUser);
            self::vaiPara(self::getStringClass() . "&msg=Item alterado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        }
    }

    protected function formAltera($id) {
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

    public function logDireitos($descricao, $id, $dadosAnteriores, $dadosNovos, $dadosUser) {
        $arrTextoLog = FormataString::raw_json_encode(array('dados_anteriores' => $dadosAnteriores, 'dados_novos' => $dadosNovos));
        $nome = $dadosUser["nome_usuario"];
        $email = $dadosUser["email_usuario"];
        //SALVAR LOG
        $valores = FormataPost::montaArrayLogDireitosUser($descricao, self::getObjUsrSessao()->getIdUsuario(), self::getObjUsrSessao()->getNomeUsuario(), self::getObjUsrSessao()->getEmailUser(), $id, $nome, $email, $arrTextoLog);
        self::getObjLog()->registraLog("fwk_log_direitos", $valores);
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