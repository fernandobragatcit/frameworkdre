<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class SubDireitosGrupoDAO extends AbsModelDao {

    public $_table = "fwk_grupo_usuario";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_grupo_usuario";

    /**
     * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
     *
     * @author André Coura
     * @since 1.0 - 21/04/2011
     */
    public function cadastrar($xml, $post, $file) {
        try {
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
            $this->id_grupo_usuario = $id;
            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            self::replace();
            if (self::ErrorMsg()) {
                print("<h1>" . __CLASS__ . "</h1>");
                print("<pre>");
                print_r($this);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function getGruposUsuario($idUsuario) {

        $strQuery = "SELECT id_grupo
					FROM fwk_grupo_usuario fgu
					WHERE id_usuario = '" . $idUsuario . "'";

        return ControlDb::getAll($strQuery, 0);
    }

    public function getNomeDireito($id) {
        $strQuery = "SELECT nome_direito 
					FROM fwk_direitos 
					WHERE id_direitos = '" . $id . "'";

        return end(Utf8Parsers::arrayUtf8Encode(ControlDb::getRow($strQuery, 0)));
    }

    public function getNomeGrupoById($id) {
        $strQuery = "SELECT nome_grupo 
					FROM fwk_grupo 
					WHERE id_grupo = '" . $id . "'";

        return end(Utf8Parsers::arrayUtf8Encode(ControlDb::getRow($strQuery, 0)));
    }

    public function inserirPermissoesGrupo($idDir, $idGrupo, $idPerm) {
        $objBanco = ControlDb::getBanco();
        $sql = "INSERT INTO fwk_sub_direitos_grupo (id_direito,id_grupo,id_sub_direito) VALUES('" . $idDir . "','" . $idGrupo . "','" . $idPerm . "')";
        $objBanco->Execute($sql);
    }

    public function limpaTabelaPermissoesGrupo($idDir, $idGrupo) {
        $objBanco = ControlDb::getBanco();
        $sql = "DELETE FROM fwk_sub_direitos_grupo WHERE id_direito=" . $idDir . " AND id_grupo=" . $idGrupo . "";
        $objBanco->Execute($sql);
    }

    public function getPermissoes($idDir, $idGrupo) {
        $strQuery = "SELECT id_sub_direito 
					FROM fwk_sub_direitos_grupo  
					WHERE id_direito=" . $idDir . " AND id_grupo=" . $idGrupo . "";
        return ControlDb::getAll($strQuery, 1);
    }

    public function getTodosGrupos() {
        $strQuery = "SELECT id_grupo 
					FROM fwk_grupo";
        return ControlDb::getAll($strQuery, 1);
    }

    public function getDireitosByGrupo($gp) {
        $strQuery = "SELECT
           dg.id_direitos
           FROM
            fwk_direitos_grupo dg
            INNER JOIN fwk_direitos dir ON dir.id_direitos=dg.id_direitos
            INNER JOIN fwk_item_menu it ON it.id_item_menu=dir.id_item_menu
           WHERE it.formulario=1 AND id_grupo=" . $gp;
        return ControlDb::getAll($strQuery, 1);
    }

}

?>