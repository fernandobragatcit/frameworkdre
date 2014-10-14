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

    public function getIdSetor() {
        return $this->id_setor;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function alterar($id = null, $xml = null, $post = null, $file = null) {
        try {
            $this->id_setor = $id;
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

    public function getAllSetor() {
        $strQuery = "SELECT s.id_setor, s.setor, s.email_setor,
                     DATE_FORMAT(s.data_cadastro, '%d/%m/%Y') as data_cadastro,
                     u.nome_usuario as usu_cadastro FROM fwk_chamados_setor s
                     INNER JOIN fwk_usuario u ON u.id_usuario = s.id_usuario_cad";
        return ControlDb::getAll($strQuery, 0);
    }

    public function getSetorById($id) {
        $strQuery = "SELECT s.id_setor, s.setor, s.email_setor,
                     DATE_FORMAT(s.data_cadastro, '%d/%m/%Y') as data_cadastro,
                     u.nome_usuario as usu_cadastro FROM fwk_chamados_setor s
                     INNER JOIN fwk_usuario u ON u.id_usuario = s.id_usuario_cad 
                     WHERE id_setor = " . $id;
        return ControlDb::getAll($strQuery, 0);
    }

}

?>
