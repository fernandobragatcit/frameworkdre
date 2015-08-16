<?php

require_once(FWK_MODEL . "AbsModelCruds.class.php");
require_once(FWK_MODEL . "DireitosAdmin.class.php");
require_once(FWK_MODEL . "DireitosGrupoAdmin.class.php");
require_once(FWK_MODEL . "DireitosUsuarioAdmin.class.php");
require_once(FWK_DAO . "ItemMenuDAO.class.php");

/**
 * Classe Modelo para criação dos ítens de menu ou submenus
 *
 * @author Andre
 * @since 1.0 - 05/09/2009
 */
class ItemMenuAdmin extends AbsModelCruds {

    /**
     * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
     */
    public $_table = "fwk_item_menu";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_item_menu";
    private $objDireitosAdmin;
    private $objDireitosGrupoAdmin;
    private $objDireitosUsuarioAdmin;

    /**
     * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
     */
    public function cadastrar($xml, $post, $file) {
        //self::debuga($post);
        try {
            self::validaForm($xml, $post);
            self::salvaPostAutoUtf8($post);
            $this->id_menu_pai = $post["id_menu_pai"] == "" ? null : $post["id_menu_pai"];
            $this->id_item_menu_pai = $post["id_item_menu_pai"] == "" ? null : $post["id_item_menu_pai"];
            //$this->tipo_item_menu = "c";
            self::salvar();

            //#####################################################################################################
            //pego as ordens para montar a numeração do nome do direito
            if ($this->id_menu_pai) {
                $ordemMenuPai = self::getObjItemMenuDAO()->getOrdemMenuPai($this->id_menu_pai);
            } else {
                $idItemMenuPai = $this->id_item_menu_pai;
                while (empty($ordemMenuPai)) {
                    $idMenuPai = self::getObjItemMenuDAO()->getIdMenuPaiByIdItemMenuPai($idItemMenuPai);
                    if (empty($idMenuPai)) {
                        $idItemMenuPaiAtual = $idItemMenuPai;
                        $idItemMenuPai = self::getObjItemMenuDAO()->getIdItemMenuPaiByIdItemMenuPai($idItemMenuPai);
                        $ordemItemMenuPai[] = self::getObjItemMenuDAO()->getOrdemItemMenuPai($idItemMenuPaiAtual);
                    } else {
                        $ordemMenuPai = self::getObjItemMenuDAO()->getOrdemMenuPai($idMenuPai);
                        $ordemFilho = self::getObjItemMenuDAO()->getOrdemItemMenuPai($idItemMenuPai);
                    }
                }
            }
            if ($ordemFilho) {
                $ordemMenuPai.="." . $ordemFilho;
            }
            if ($ordemItemMenuPai) {
                if (count($ordemItemMenuPai) > 1) {
                    $ordemItemMenuPai = krsort($ordemItemMenuPai);
                }
                foreach ($ordemItemMenuPai as $valor) {
                    $ordemMenuPai.="." . $valor;
                }
            }
            $numeracaoNome = $ordemMenuPai . "." . $post["ordem_item_menu"];
            //self::debuga($numeracaoNome,$post);
            //#####################################################################################################
            //
            //
            //$idItemMenu = self::getIdItemMenu($post["nome_item_menu"]);
            //Salva-se a funcionalidade de menu automaticamente em direitos.
            self::getObjDireitosAdmin()->setIdItemMenu($this->id_item_menu);
            self::getObjDireitosAdmin()->setNomeDireito($numeracaoNome . " " . utf8_decode($post["nome_item_menu"]));
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
                        ControlDb::getBanco()->StartTrans();
                        foreach ($arrGrupos as $grupo) {
                            if (!ControlDb::getBanco()->Execute("INSERT INTO fwk_direitos_grupo (id_direitos,id_grupo)
										VALUES ('" . self::getObjDireitosAdmin()->getIdDireitosAdmin() . "', '" . $grupo . "')")) {
                                ControlDb::getBanco()->FailTrans();
                                throw new CrudException("Impossivel cadastrar o direito para o ítem ao grupo " . $this->_table);
                            }
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
     * Método para buscar o ultimo id do item menu gravado com o nome postado
     * tentativa de integridade do método.
     *
     * @author Andre Coura
     * @since 1.0 - 07/11/2009
     * @param String $strNomeItemMenu: nome do item de menu
     */
    private function getIdItemMenu($strNomeItemMenu) {
        $strQuery = "SELECT MAX(id_item_menu) FROM " . $this->_table . "
						WHERE  LOWER(nome_item_menu) = '" . strtolower(utf8_decode($strNomeItemMenu)) . "'";
        $arrDados = ControlDb::getBanco()->GetRow($strQuery);

        return $arrDados[0];
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author André Coura
     * @since 1.0 - 05/09/2008
     */
    public function alterar($id, $xml, $post, $file) {
        //self::debuga($post);
        try {
            $this->id_item_menu = $id;
            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            $this->id_menu_pai = $post["id_menu_pai"] == "" ? null : $post["id_menu_pai"];
            $this->id_item_menu_pai = $post["id_item_menu_pai"] == "" ? null : $post["id_item_menu_pai"];
            self::replace();
            //#####################################################################################################
            //pego as ordens para montar a numeração do nome do direito
            if ($this->id_menu_pai) {
                $ordemMenuPai = self::getObjItemMenuDAO()->getOrdemMenuPai($this->id_menu_pai);
                $caiu = "if";
            } else {
                $caiu = "else";
                $idItemMenuPai = $this->id_item_menu_pai;
                while (empty($ordemMenuPai)) {
                    $idMenuPai = self::getObjItemMenuDAO()->getIdMenuPaiByIdItemMenuPai($idItemMenuPai);
                    if (empty($idMenuPai)) {
                        $idItemMenuPaiAtual = $idItemMenuPai;
                        $idItemMenuPai = self::getObjItemMenuDAO()->getIdItemMenuPaiByIdItemMenuPai($idItemMenuPai);
                        $ordemItemMenuPai[] = self::getObjItemMenuDAO()->getOrdemItemMenuPai($idItemMenuPaiAtual);
                        if ($idItemMenuPaiAtual == $idItemMenuPai) {
                            $ordemMenuPai = 0;
                            break;
                        }
                    } else {
                        $ordemMenuPai = self::getObjItemMenuDAO()->getOrdemMenuPai($idMenuPai);
                        $ordemFilho = self::getObjItemMenuDAO()->getOrdemItemMenuPai($idItemMenuPai);
                    }
                }
            }
            if ($ordemMenuPai == 0) {
                $numeracaoNome = "";
            } else {
                if ($ordemFilho) {
                    $ordemMenuPai.="." . $ordemFilho;
                }
                if ($ordemItemMenuPai) {
                    if (count($ordemItemMenuPai) > 1) {
                        $ordemItemMenuPai = krsort($ordemItemMenuPai);
                    }
                    foreach ($ordemItemMenuPai as $valor) {
                        $ordemMenuPai.="." . $valor;
                    }
                }
                $numeracaoNome = $ordemMenuPai . "." . $post["ordem_item_menu"];
            }
            //self::debuga($numeracaoNome,$post);
            //#####################################################################################################
            //
            //
            $idDireito = self::getObjItemMenuDAO()->getIdDireitoByIdItemMenu($this->id_item_menu);
            self::getObjItemMenuDAO()->alterarNomeDireito(trim($numeracaoNome . " " . utf8_decode($post["nome_item_menu"])), $idDireito);
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

    public function deletaItemMenu($id) {
        $arrDados = array("table" => "fwk_direitos",
            "campo" => "id_item_menu",
            "valor" => $id);
        $arrCampos = ControlDb::selectRowTable($arrDados, 0);

//		print("<pre>");
//		print_r($arrCampos);
//		die();

        ControlDb::getBanco()->StartTrans();
        $trans1 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos_grupo WHERE id_direitos = " . $arrCampos["id_direitos"]);
        $trans2 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos_usuario WHERE id_direitos = " . $arrCampos["id_direitos"]);
        $trans3 = ControlDb::getBanco()->Execute("DELETE FROM fwk_direitos WHERE id_item_menu = " . $id);
        $trans4 = ControlDb::getBanco()->Execute("DELETE FROM fwk_item_menu WHERE id_item_menu = " . $id);

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