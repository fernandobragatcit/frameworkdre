<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class GrupoUsuarioDAO extends AbsModelDao {

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

    //########################################################################
    public function getIdMenu($idDireito) {

        $strQuery = "SELECT id_menu FROM fwk_direitos WHERE id_direitos = '" . $idDireito . "'";
        //self::debuga($strQuery);
        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getIdItemMenu($idDireito) {

        $strQuery = "SELECT id_item_menu FROM fwk_direitos WHERE id_direitos = '" . $idDireito . "'";
        //self::debuga($strQuery);
        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getIdDireitoByIdMenu($idMenu) {

        $strQuery = "SELECT id_direitos FROM fwk_direitos WHERE id_menu = '" . $idMenu . "'";
        //self::debuga($strQuery);
        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getIdDireitoByIdItemMenu($idItemMenu) {

        $strQuery = "SELECT id_direitos FROM fwk_direitos WHERE id_item_menu = '" . $idItemMenu . "'";
        //self::debuga($strQuery);
        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getDireitoItemMenuPai($idItemMenu) {

        $strQuery = "SELECT id_direitos 
					FROM fwk_direitos WHERE id_item_menu = '" . $idItemMenu . "'";

        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getDireitoMenuPai($idMenu) {

        $strQuery = "SELECT id_direitos 
					FROM fwk_direitos WHERE id_menu = '" . $idMenu . "'";

        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getIdMenuPaiByIdItemMenuPai($idItemMenu) {

        $strQuery = "SELECT id_menu_pai	FROM fwk_item_menu WHERE id_item_menu = '" . $idItemMenu . "'";

        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getIdItemMenuPaiByIdItemMenuPai($idItemMenu) {

        $strQuery = "SELECT id_item_menu_pai 
					FROM fwk_item_menu WHERE id_item_menu = '" . $idItemMenu . "'";

        return end(ControlDb::getRow($strQuery, 0));
    }
    public function getTodosIdsItemMenu() {

        $strQuery = "SELECT id_item_menu FROM fwk_item_menu";

        return ControlDb::getAll($strQuery, 1);
    }

}

?>