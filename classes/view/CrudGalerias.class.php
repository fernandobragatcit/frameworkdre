<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_DAO . "GaleriasDAO.class.php");
require_once(FWK_DAO . "FotosDAO.class.php");
require_once(FWK_DAO . "FotosGaleriaDAO.class.php");

class CrudGalerias extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlForm(FWK_XML_CRUD . "formGalerias.xml");
        self::setXmlGrid(FWK_XML_CRUD . "gridGalerias.xml");
        self::setClassModel(new GaleriasDAO());
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "formCad":
            case "formAlt":
                self::formAlteraGaleria($get['id']);
                break;
            case "cadastra":
            case "altera":
                self::postAlteraGaleria($get['id'], $post, $file);
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

    public function formAlteraGaleria($id) {
        $arrDados = self::getClassModel()->buscaCampos($id, 0);
        self::getClassModel()->setTipoForm(self::getTipoForm());
        self::getClassModel()->getObjCtrlForm(parent::getXmlForm())->setIdReferencia($id);
        self::getClassModel()->preencheFormComDados(self::getXmlForm(), $id, self::getStringClass(), $arrDados);
    }

    public function postAlteraGaleria($id, $post, $file) {
        //self::debuga($post);
        $fotos = $post["fotos_galeria"];
//        $fotos = array_values($post["fotos_galeria"]);
//        $quant = count($fotos) - 2;
        unset($post["fotos_galeria"]);
        self::getClassModel()->alterar($id, self::getXmlForm(), $post, null);
        $idGaleria = self::getClassModel()->getIdGaleria();
        $arrFotos = self::getFotosGaleriaDAO()->buscaAllCampos($idGaleria, 0);
        self::getFotosGaleriaDAO()->limpaGaleria($idGaleria);
        $ate=$post["fotos_galeriaCount"];
        for ($i = 0; $i < $ate; $i++) {
            $postAux = $fotos[$i];
            $postAux["multiplos"] = true;
            $postAux["indice"] = $i;
            $postAux["nome_campo"] = "fotos_galeria";
            $fileAux["nome_arquivo"]["name"] = $file["fotos_galeria"]["name"][$i];
            $fileAux["nome_arquivo"]["type"] = $file["fotos_galeria"]["type"][$i];
            $fileAux["nome_arquivo"]["tmp_name"] = $file["fotos_galeria"]["tmp_name"][$i];
            $fileAux["nome_arquivo"]["error"] = $file["fotos_galeria"]["error"][$i];
            $fileAux["nome_arquivo"]["size"] = $file["fotos_galeria"]["size"][$i];
            $objFoto = new FotosDAO();

            //self::debuga($postAux, $fileAux);
//            if ($fileAux["nome_arquivo"]["error"] == 0) {
            if (!empty($postAux["titulo_foto"])) {
               //self::debuga("caiu aki primeiro if",$postAux, $fileAux);
                if($postAux["id_nome_arquivo"]){
                    $objFoto->alterar($postAux["id_nome_arquivo"],self::getXmlForm(), $postAux, $fileAux);
                }else{
                $objFoto->cadastrar(self::getXmlForm(), $postAux, $fileAux);
                $idFoto = $objFoto->getIdFoto();
                self::getFotosGaleriaDAO()->cadastrar(null, array("id_galeria" => $idGaleria, "id_foto" => $idFoto), null);
                }
            }else{
                 //self::debuga("caiu aki else");
                $ate++;
            }
            if ($arrFotos) {
                //self::debuga("caiu aki Segundo if");
                foreach ($arrFotos as $foto) {
                    if ($foto["id_foto"] == $postAux["id_nome_arquivo"]) {
                        $objFoto->alterar($foto["id_foto"], self::getXmlForm(), $postAux, $fileAux);
                        self::getFotosGaleriaDAO()->alterar($foto["id_galeria"], null, $foto, null);
                    }
                }
            }
            //self::debuga("Parou",$postAux, $fileAux);
            //self::debuga("IF", $fileAux, $postAux, $post,$file);
//            } else {
//                //self::debuga("ELSE", $fileAux, $arrFotos, $post, $postAux,$file);
//                foreach ($arrFotos as $foto) {
//                    if ($foto["id_foto"] == $postAux["id_nome_arquivo"]) {
//                        $objFoto->alterar($foto["id_foto"], self::getXmlForm(), $postAux, $fileAux);
//                        self::getFotosGaleriaDAO()->alterar($foto["id_galeria"], null, $foto, null);
//                    }
//                }
//            }
         $objFoto = null;
        }
        self::vaiPara(self::getStringClass() . "&msg=Item alterado com sucesso!");
    }

    private function getFotosGaleriaDAO() {
        if ($this->objFotosGaleria == null) {
            $this->objFotosGaleria = new FotosGaleriaDAO();
        }
        return $this->objFotosGaleria;
    }

}

?>