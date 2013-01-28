<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_MODEL . "ItemMenuAdmin.class.php");

class CrudItemMenu extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formItemMenu.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridItemMenu.xml");
        self::setClassModel(new ItemMenuAdmin());
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "formCad":
                self::formCadastro();
                break;
            case "cadastra":
                if (empty($post["formulario"])) {
                    $post["formulario"] = 0;
                }
                if (empty($post["id_menu_pai"]) && empty($post["id_item_menu_pai"])) {
                    $post["id_menu_pai"] = 1;
                }
                self::postCadastro($post, $file);
                break;
            case "formAlt":
                self::formAltera($get['id']);
                break;
            case "altera":
                if (empty($post["formulario"])) {
                    $post["formulario"] = 0;
                }
                if (empty($post["id_menu_pai"]) && empty($post["id_item_menu_pai"])) {
                    $post["id_menu_pai"] = 1;
                }
                self::postAltera($get['id'], $post, $file);
                break;
            case "deleta":
                self::deletaMenu($get['id']);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    private function deletaMenu($id) {
        try {
            self::getClassModel()->deletaItemMenu($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=Ã?tem deletado com sucesso!");
    }

}

?>