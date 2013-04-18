<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "DocumentoDAO.class.php");

class PrioridadeDAO extends AbsModelDao {

    public $_table = "fwk_chamados_prioridade";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_prioridade";

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function cadastrar($xml, $post, $file) {
        try {
            self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
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

    public function getIdPrioridade() {
        return $this->id_prioridade;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function alterar($id = null, $xml = null, $post = null, $file = null) {
        try {
            $this->id_prioridade = $id;
            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            
            $arrCampos = self::buscaCampos($id);
            self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
            self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
            
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

    public function getAllPrioridade() {
        $strQuery = "SELECT p.id_prioridade, p.prioridade, DATE_FORMAT(p.data_cadastro, '%d/%m/%Y') as data_cadastro,
                     u.nome_usuario as usu_cadastro FROM fwk_chamados_prioridade p
                     INNER JOIN fwk_usuario u ON u.id_usuario = p.id_usuario_cad";
        return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery, 0));
    }

}

?>
