<?php

require_once(FWK_MODEL . "AbsModelCruds.class.php");
require_once(FWK_MODEL . "DireitosAdmin.class.php");
require_once(FWK_MODEL . "DireitosGrupoAdmin.class.php");
require_once(FWK_MODEL . "DireitosUsuarioAdmin.class.php");
require_once(FWK_DAO . "ItemMenuDAO.class.php");

/**
 * Classe modelo para manipulação dos Menus do Admin
 */
class MenuAdmin extends AbsModelCruds {

    /**
     * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
     */
    public $_table = "fwk_menu";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_menu";

    /**
     * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
     */
    public function cadastrar($xml, $post, $file) {
        try {
            self::validaForm($xml, $post);
            self::salvaPostAutoUtf8($post);
            $this->tipo_menu = "c";
            self::salvar();

            //Salva-se a funcionalidade de menu automaticamente em direitos.
            self::getObjDireitosAdmin()->setIdMenu($this->id_menu);
            self::getObjDireitosAdmin()->setNomeDireito($post["ordem_menu"] . " " . $post["nome_menu"]);
            self::getObjDireitosAdmin()->setIdPortal($post["id_portal"]);
            self::getObjDireitosAdmin()->salvar();

            //atribui a funcionalidade criada pelo usuário ao grupo dele ou apenas ao usuário
            //de acordo com o que foi escolhido no formulário
            if ($post["funcionalidade"] != "") {
                if ($post["funcionalidade"] == DIREITOS_GRUPO) {
                    $arrGrupos = self::getUsuarioSessao()->getGrupoUsuario();
                    if (is_array($arrGrupos) && count($arrGrupos) > 0) {
                        //caso não tenha o admin nos grupos para uma funcionalidade seta-o
                        if (!in_array(1, $arrGrupos))
                            $arrGrupos[] = 1;
                        ControlDb::getBanco()->BeginTrans();
                        foreach ($arrGrupos as $grupo) {
                            ControlDb::getBanco()->Execute("insert into fwk_direitos_grupo (id_direitos,id_grupo)
										values ('" . self::getObjDireitosAdmin()->getIdDireitosAdmin() . "', '" . $grupo . "')");
                        }
                        ControlDb::getBanco()->CommitTrans();
                    }
                } else if ($post["funcionalidade"] == DIREITOS_USUARIO) {
                    self::getObjDireitosUsuarioAdmin()->setIdDireito(self::getObjDireitosAdmin()->getIdDireitosAdmin());
                    self::getObjDireitosUsuarioAdmin()->setIdUsuario(self::getUsuarioSessao()->getIdUsuario());
                    self::getObjDireitosUsuarioAdmin()->salvar();
                }
            }
        } catch (CrudException $e) {
            throw new CrudException($e->getMensagem());
        }
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author André Coura
     * @since 1.0 - 05/09/2008
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            $this->id_menu = $id;
            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            self::replace();

            $idDireito = self::getObjItemMenuDAO()->getIdDireitoByIdMenu($this->id_menu);
            self::getObjItemMenuDAO()->alterarNomeDireito(trim($post["ordem_menu"] . " " . utf8_decode($post["nome_menu"])), $idDireito);
        } catch (CrudException $e) {
            throw new CrudException($e->getMensagem());
        }
    }

    protected function getObjDireitosAdmin() {
        if ($this->objDireitosAdmin == null)
            $this->objDireitosAdmin = new DireitosAdmin();
        return $this->objDireitosAdmin;
    }

    protected function getObjDireitosGrupoAdmin() {
        if ($this->objDireitosGrupoAdmin == null)
            $this->objDireitosGrupoAdmin = new DireitosGrupoAdmin();
        return $this->objDireitosGrupoAdmin;
    }

    protected function getObjDireitosUsuarioAdmin() {
        if ($this->objDireitosUsuarioAdmin == null)
            $this->objDireitosUsuarioAdmin = new DireitosUsuarioAdmin();
        return $this->objDireitosUsuarioAdmin;
    }
    protected function getObjItemMenuDAO() {
        if ($this->objItem == null)
            $this->objItem = new ItemMenuDAO();
        return $this->objItem;
    }

    public function deletarMenu($id) {
        $arrDados = array("table" => "fwk_direitos",
            "campo" => "id_menu",
            "valor" => $id);
        $arrCampos = ControlDb::selectRowTable($arrDados, 0);

//		print("<pre>");
//		print_r($arrCampos);
//		die();

        ControlDb::getBanco()->StartTrans();
        $trans1 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos_grupo WHERE id_direitos = " . $arrCampos["id_direitos"]);
        $trans2 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos_usuario WHERE id_direitos = " . $arrCampos["id_direitos"]);
        $trans3 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos WHERE id_menu = " . $id);
        $trans4 = ControlDb::getBanco()->Execute("DELETE FROM fwk_menu WHERE id_menu = " . $id);

        if (!$trans1 || !$trans2 || !$trans3 || !$trans4) {
            ControlDb::getBanco()->RollbackTrans();
            //die("Impossível deletar o dado da tabela ".ControlDb::getBanco()->Error());
            die(ADODB_Pear_Error());
            throw new CrudException("Impossível deletar o dado da tabela " . $this->_table);
        }
        ControlDb::getBanco()->CommitTrans();
    }

}

?>