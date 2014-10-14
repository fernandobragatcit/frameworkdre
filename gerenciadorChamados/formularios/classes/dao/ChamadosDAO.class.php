<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "DocumentoDAO.class.php");

class ChamadosDAO extends AbsModelDao {

    public $_table = "fwk_chamados";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_chamado";

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

    public function getIdChamado() {
        return $this->id_chamado;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author Fernando Braga
     * @since 1.0 - 22/03/2013
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            $this->id_chamado = $id;
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

    public function getAllChamado() {
        $strQuery = "SELECT f.id_chamado, f.descricao_chamado, u.nome_usuario, 
                     DATE_FORMAT(f.data_entrada, '%d/%m/%Y') as data_entrada, p.prioridade,
                     s.status, g.setor, f.resumo_chamado FROM fwk_chamados f
                     INNER JOIN fwk_chamados_prioridade p ON p.id_prioridade = f.id_prioridade
                     LEFT JOIN fwk_chamados_status s ON s.id_status = f.id_status
                     LEFT JOIN fwk_usuario u ON u.id_usuario = f.id_usuario_solicitante
                     LEFT JOIN fwk_chamados_setor g ON g.id_setor = f.id_setor ORDER BY id_chamado DESC";
        return ControlDb::getAll($strQuery, 0);
    }

    public function getChamadoById($id = null) {
        $strQuery = "SELECT f.id_chamado, f.descricao_chamado, u.nome_usuario as usuario_solicitante,
                     DATE_FORMAT(f.data_entrada, '%d/%m/%Y') as data_entrada, p.prioridade,
                     s.status, g.setor, f.resumo_chamado FROM fwk_chamados f
                     INNER JOIN fwk_chamados_prioridade p ON p.id_prioridade = f.id_prioridade
                     LEFT JOIN fwk_chamados_status s ON s.id_status = f.id_status
                     LEFT JOIN fwk_usuario u ON u.id_usuario = f.id_usuario_solicitante
                     LEFT JOIN fwk_chamados_setor g ON g.id_setor = f.id_setor
                     WHERE id_chamado = " . $id;
        return ControlDB::getRow($strQuery, 0);
        //return ControlDb::getAll($strQuery, 0);
    }

    public function getAllSetorChamados() {
        $strQuery = "SELECT *
                     FROM fwk_chamados_setor";
        return ControlDb::getAll($strQuery, 0);
    }

    public function getAllPrioridadeChamados() {
        $strQuery = "SELECT *
                     FROM fwk_chamados_prioridade";
        return ControlDb::getAll($strQuery, 0);
    }

}

?>
