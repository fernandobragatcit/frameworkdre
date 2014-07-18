<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class ConfiguracoesGeraisDAO extends AbsModelDao {

    public $_table = "fwk_config_sistema";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_config";

    public function cadastrar($xml, $post, $file) {
        try {
            $objDoc = new DocumentoDAO();
            $objDoc->setTipoDocumento(TIPODOC_CONFIG_GERAL);
            $objDoc->cadastrar($xml, $post, $file);
            $this->id_config = $objDoc->getIdDocumento();

            self::validaForm($xml, $post);
            self::salvaPostAutoUtf8($post);
            self::salvar();
            if (self::ErrorMsg()) {
                print("<h1>" . __CLASS__ . "</h1>");
                print("<pre>");
                print_r($post);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author André Coura
     * @since 1.0 - 21/04/2011
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            $objDoc = new DocumentoDAO();
            $objDoc->setTipoDocumento(TIPODOC_CONFIG_GERAL);
            $objDoc->alterar(((!isset($id) || $id == "") ? null : $id), $xml, $post, $file);
            $this->id_config = $objDoc->getIdDocumento();

            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            self::replace();
            if (self::ErrorMsg()) {
                print("<h1>" . __CLASS__ . "</h1>");
                print("<pre>");
                print_r($post);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function getIdConfiguracaoGeral() {
        return $this->id_config;
    }



}

?>