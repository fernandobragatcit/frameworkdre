<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "UsuariosGrupo.class.php");
require_once(FWK_DAO . "GrupoUsuarioDAO.class.php");

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
        $grupo = self::getObjGrupos()->getNomeGrupoById($id);
        self::getObjSmarty()->assign("GRUPO", utf8_encode($grupo));
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

}

?>