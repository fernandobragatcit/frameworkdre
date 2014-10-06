<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO . "AreaDAO.class.php");

class CrudArea extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formArea.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridArea.xml");
        self::setClassModel(new AreaDAO());
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
                self::deletaArea($get['id'], self::getClassModel()->getPastaAtualArea($get['id']));

                break;
            case "status" :
                self::alteraStatus($get["id"]);
                break;
            case "lista":
            default:
                //FormataString::debuga("chegou",$get,$post);
                self::listDados($get, $post);
                break;
        }
    }

    private function deletaArea($id, $pasta) {
        try {
            self::deleta($id);
            $destino = SERVIDOR_FISICO . PASTA_CONTEUDO . "/";
            FormataString::deletaPastaeSubpasta($pasta, $destino);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=Ítem deletado com sucesso!");
    }

}

?>