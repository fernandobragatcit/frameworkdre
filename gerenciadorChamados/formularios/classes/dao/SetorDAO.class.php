<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "DocumentoDAO.class.php");

class SetorDAO extends AbsModelDao {

    public $_table = "fwk_chamados_setor";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_setor";

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function cadastrar($xml, $post, $file) {
        try {
            //self::debuga( $post);
            self::validaForm($xml, $post);
            self::salvaPostAutoUtf8($post);
            self::salvar();

            if (self::ErrorMsg()) {
                print("<pre>");
                print_r($post);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function getIdSetor() {
        return $this->id_setor;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            $this->id_setor = $id;
            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            self::replace();
            if (self::ErrorMsg()) {
                print("<pre>");
                print_r($post);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }
    
     public function getAllSetor() {
        $strQuery = "SELECT *
                     FROM fwk_chamados_setor";
        return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery, 0));
    }
    

}

?>
