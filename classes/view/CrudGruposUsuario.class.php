<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "GruposUsuario.class.php");
require_once(FWK_DAO . "UsuariosDAO.class.php");

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
                self::postAltera($get['id'], $post, $file);
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
    

}

?>