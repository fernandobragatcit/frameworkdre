<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class SubDireitosUsuarioDAO extends AbsModelDao {

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

    public function inserirPermissoesUsuarios($idDir, $idUser, $idPerm) {
        $objBanco = ControlDb::getBanco();
        $sql = "INSERT INTO fwk_sub_direitos_usuarios (id_direito,id_usuario,id_sub_direito) VALUES('" . $idDir . "','" . $idUser . "','" . $idPerm . "')";
        $objBanco->Execute($sql);
    }

    public function limpaTabelaPermissoesUsuarios($idDir, $idUser) {
        $objBanco = ControlDb::getBanco();
        $sql = "DELETE FROM fwk_sub_direitos_usuarios WHERE id_direito=" . $idDir . " AND id_usuario=" . $idUser . "";
        $objBanco->Execute($sql);
    }

    public function getPermissoes($idDir, $idUser) {
        $strQuery = "SELECT id_sub_direito 
					FROM fwk_sub_direitos_usuarios  
					WHERE id_direito=" . $idDir . " AND id_usuario=" . $idUser . "";
        return ControlDb::getCol($strQuery);
    }

    public function getUsuariosColaboradores() {
        $strQuery = "SELECT us.id_usuario FROM fwk_usuario us
            INNER JOIN fgv_colaborador col
            ON col.id_usuario=us.id_usuario order by us.id_usuario";
        return ControlDb::getCol($strQuery);
    }

    public function getDireitosFormUsuarioByUsuario($idUser) {
        $strQuery = "(SELECT
            dir.id_direitos FROM fwk_direitos_grupo dg
            INNER JOIN fwk_direitos dir ON dir.id_direitos=dg.id_direitos
            INNER JOIN fwk_item_menu it ON it.id_item_menu=dir.id_item_menu
            WHERE it.formulario=1 AND dg.id_grupo in
            (SELECT id_grupo FROM fwk_grupo_usuario where id_usuario=".$idUser." order by dir.id_direitos))
            
            UNION DISTINCT 
            
            (SELECT
            dir.id_direitos FROM fwk_direitos_usuario ds
            INNER JOIN fwk_direitos dir ON dir.id_direitos=ds.id_direitos
            INNER JOIN fwk_item_menu it ON it.id_item_menu=dir.id_item_menu
            WHERE it.formulario=1 AND id_usuario=".$idUser." order by dir.id_direito)
";
        return ControlDb::getCol($strQuery);
    }

}

?>