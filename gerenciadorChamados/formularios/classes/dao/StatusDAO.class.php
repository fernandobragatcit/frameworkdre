<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "DocumentoDAO.class.php");

class StatusDao extends AbsModelDao {

    public $_table = "fwk_chamados_status";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_status";

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

    public function getIdStatus() {
        return $this->id_status;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            $this->id_status = $id;
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

    public function getAllStatus() {
        $strQuery = "SELECT s.id_status, s.status, DATE_FORMAT(s.data_cadastro, '%d/%m/%Y') as data_cadastro,
                     u.nome_usuario as usu_cadastro FROM fwk_chamados_status s
                     INNER JOIN fwk_usuario u ON u.id_usuario = s.id_usu_cad";
        return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery, 0));
    }

}

?>