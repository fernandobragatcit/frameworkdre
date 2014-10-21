<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO . "RedeSocialDAO.class.php");
require_once(FWK_DAO . "FotosDAO.class.php");

class CrudRedeSocial extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formRedeSocial.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridRedeSocial.xml");
        self::setClassModel(new RedeSocialDAO());
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "formCad":
            case "formAlt":
                self::formAltera($get['id']);
                break;
            case "cadastra":
            case "altera":
                self::postAlteraRedeSocial($get['id'], $post, $file);
                break;
            case "status" :
                self::alteraStatus($get["id"]);
                break;
            case "deleta":
                self::deletaRedeSocial($get['id']);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    private function postAlteraRedeSocial($id, $post, $file) {
        try {
            $arrDados = self::getClassModel()->buscaCampos($id, 0);
            $post = FormataPost::mergeArrayPost($arrDados, $post);

            if ($post["nome_arquivo_null"]) {
                self::getObjFoto()->deletaFoto($arrDados["id_foto"]);
                self::anulaCampo($id, "id_foto");
                $arrDados["id_foto"] = null;
                $post["id_foto"] = null;
                $file["nome_arquivo"]["name"] = null;
            }
            if ($arrDados["id_foto"] != null) {
                if ($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null) {
                    self::getObjFoto()->alterar($arrDados["id_foto"], parent::getXmlForm(), $post, $file);
                    $post["id_foto"] = self::getObjFoto()->getIdFoto();
                } else {
                    $post["id_foto"] = $arrDados["id_foto"];
                }
            } else {
                if ($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null) {
                    self::getObjFoto()->cadastrar(parent::getXmlForm(), $post, $file);
                    $post["id_foto"] = self::getObjFoto()->getIdFoto();
                } else {
                    $post["id_foto"] = null;
                }
            }
            if ($id) {
                self::getClassModel()->alterar($id, self::getXmlForm(), $post, $file);
            } else {
                self::getClassModel()->cadastrar(self::getXmlForm(), $post, $file);
            }
            self::vaiPara(self::getStringClass() . "&msg=Ítem alterado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        }
    }

    protected function deletaRedeSocial($id) {
        try {
            $arrDados = self::getClassModel()->buscaCampos($id, 0);
            self::getObjFoto()->deletaFoto($arrDados["id_foto"]);
            self::getClassModel()->deletar($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=Ã�tem deletado com sucesso!");
    }

    private function getObjFoto() {
        if ($this->obFoto == null) {
            $this->obFoto = new FotosDAO();
        }
        return $this->obFoto;
    }

}

?>