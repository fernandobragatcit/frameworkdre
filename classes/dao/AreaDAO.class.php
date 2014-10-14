<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class AreaDAO extends AbsModelDao {

    public $_table = "fwk_area";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_area";

    /**
     * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
     *
     * @author André Coura
     * @since 1.0 - 12/04/2011
     */
    public function cadastrar($xml, $post, $file) {
        $nome_pasta_copia = "area";
        $origem = PASTA_FWK . "files/" . $nome_pasta_copia . "/";
        $destino = SERVIDOR_FISICO . PASTA_CONTEUDO . "/";
        $pasta = $post["area"] = FormataString::formataNomePastaArea($post["area"]);
        FormataString::salvaAlteraPastaArea($pasta, $destino, $origem, $nome_pasta_copia, null);
        try {
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

    public function getIdArea() {
        return $this->id_area;
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author André Coura
     * @since 1.0 - 12/04/2011
     */
    public function alterar($id, $xml, $post, $file) {
        $nome_pasta_copia = "area";
        $origem = PASTA_FWK . "files/" . $nome_pasta_copia . "/";
        $destino = SERVIDOR_FISICO . PASTA_CONTEUDO . "/";
        $pasta = $post["area"] = FormataString::formataNomePastaArea($post["area"]);
        $pastaAtual = self::getPastaAtualArea($id);
        FormataString::salvaAlteraPastaArea($pasta, $destino, $origem, $nome_pasta_copia, $pastaAtual);
        try {
            $this->id_area = $id;
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

    public function getPastaAtualArea($id) {
        $strQuery = "SELECT 
    					area
					FROM 
						fwk_area
					WHERE 
						id_area = '" . $id . "'";
        return end(ControlDB::getRow($strQuery, 0));
    }

}

?>