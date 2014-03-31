<?php

require_once (FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "DocumentoDAO.class.php");

class FotosCropDAO extends AbsModelDao {

    public $_table = "fwk_fotos_crop";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_crop";

    /**
     * Nome do input:file para ser tratado por ser um vetor a parte
     */
    private $strNomeCampo = "nome_arquivo";

    /**
     * 
     * @author Fernando Braga
     * @since 3.0 - 27/03/2014
     */
    public function cadastrarCrop($xml, $post, $file) {
        //self::debuga($post);
        $quant = $post["ordem_recorte"];
        for ($i = 1; $i <= $quant + 1; $i++) {
            if (!empty($post["id_foto"]) && !empty($post["tag_recorte" . $i])) {
                $strIdX = "cropX" . $i;
                $strIdY = "cropY" . $i;
                $strIdTag = "tag_recorte" . $i;
                $post["cropX"] = $post[$strIdX];
                $post["cropY"] = $post[$strIdY];
                $post["tag_recorte"] = $post[$strIdTag];
                $dados_crop = self::buscaDadosCropByTagEfoto($post["tag_recorte"], $post['id_foto']);
                if ($dados_crop["id_crop"] <= 0) {
                    $strQuery = "INSERT INTO " . $this->_table . " (id_foto, cropX, cropY, tag_recorte)VALUES('" . $post['id_foto'] . "','" . $post["cropX"] . "','" . $post["cropY"] . "','" . $post["tag_recorte"] . "')";
                } else {
                    $strQuery = "UPDATE " . $this->_table . " SET id_foto='" . $post['id_foto'] . "', cropX='" . $post["cropX"] . "', cropY='" . $post["cropY"] . "', tag_recorte='" . $post["tag_recorte"] . "' WHERE id_crop='" . $dados_crop["id_crop"] . "'";
                }
                ControlDb::getBanco()->Execute($strQuery);
            }
        }
    }

    public function buscaDadosCropByTagEfoto($tag, $id_foto) {
        try {
            $strQuery = "SELECT * FROM fwk_fotos_crop WHERE tag_recorte='" . $tag . "' AND id_foto=" . $id_foto;
            return ControlDB::getRow($strQuery, 0);
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function buscaNomeImagem($idImg) {
        try {
            $strQuery = "SELECT nome_arquivo FROM fwk_fotos WHERE id_foto='" . $idImg . "'";
            return end(ControlDB::getRow($strQuery, 0));
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function getIdFoto() {
        return $this->id_crop;
    }

    public function setNomeCampo($nomeCampo) {
        $this->strNomeCampo = $nomeCampo;
    }

    public function deletaFoto($id) {
        try {
            self::deletar($id);
        } catch (CrudException $e) {
            throw new CrudException($e->getMensagem());
        }
    }

}

?>