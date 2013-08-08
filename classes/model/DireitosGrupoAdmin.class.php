<?php

require_once(FWK_MODEL . "AbsModelCruds.class.php");
require_once(FWK_DAO . "GrupoUsuarioDAO.class.php");

/**
 * Classe de entidade e relacionamentos de direitos para grupos
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 07/11/2009
 */
class DireitosGrupoAdmin extends AbsModelCruds {

    /**
     * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
     */
    public $_table = "fwk_direitos_grupo";

    /**
     * Chave primária para utilização em funções genéricas
     */
    public $_id = "id_grupo";

    /**
     * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
     */
    public function cadastrar($xml, $post, $file) {
        
    }

    private function salvaDireitosGrupo($arrValores, $idGrupo) {
        if (!empty($arrValores)) {
            $arrDireitos = self::pegaDireitosPai($arrValores);
            $objBanco = ControlDb::getBanco();
            foreach ($arrDireitos as $valor) {
                $strQuery = "INSERT INTO " . $this->_table . "
							(id_grupo, id_direitos)
						VALUES
							('" . $idGrupo . "','" . $valor . "')";
                if (!$objBanco->Execute($strQuery))
                    throw new CrudException("Erro ao cadastrar os Direitos para o Grupo!");
            }
        }
    }

    /**
     * Método utilizado para pegar os direitos pai respectivo ao direito filho selecionado.
     *
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 25/01/2013
     */
    private function pegaDireitosPai($arrValores) {
        //pego os direitos pai do direito selecionado.
        $arrIdsItensMenu = self::getObjGrupoUsuarioDAO()->getTodosIdsItemMenu();
        for ($i = 0; $i < count($arrValores); $i++) {
            $idMenu = self::getObjGrupoUsuarioDAO()->getIdMenu($arrValores[$i]);
            if (empty($idMenu)) {
                $idItemMenu = self::getObjGrupoUsuarioDAO()->getIdItemMenu($arrValores[$i]);
                if (empty($idItemMenu)) {
                    $arrDireitoPai[$i] = $arrValores[$i];
                } else if (in_array($idItemMenu, $arrIdsItensMenu)) {
                    $arrDireitoPai[$i] = self::montarDireitos($idItemMenu);
                } else {
                    $arrDireitoPai[$i] = $arrValores[$i];
                }
            }
        }
        if (!empty($arrDireitoPai)) {
            $arrDireitoPai = array_values($arrDireitoPai);
            for ($y = 0; $y < count($arrDireitoPai); $y++) {
                if (is_array($arrDireitoPai[$y])) {
                    foreach ($arrDireitoPai[$y] as $vl) {
                        $arrDados[] = $vl;
                    }
                } else {
                    $arrDados[] = $arrDireitoPai[$y];
                }
            }
            $arrDireitoPai = $arrDados;
        }
        $result = array_unique($arrDireitoPai);
        return $result;
    }

    /**
     * monta os direitos pai em um array.
     * @return array de direitos pai
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 25/01/2013
     */
    private function montarDireitos($item) {
        $idItemMenuPai = $item;
        while (empty($direitoMenuPai)) {
            $idMenuPai = self::getObjGrupoUsuarioDAO()->getIdMenuPaiByIdItemMenuPai($idItemMenuPai);
            if (empty($idMenuPai)) {
                $idItemMenuPaiAtual = $idItemMenuPai;
                $idItemMenuPai = self::getObjGrupoUsuarioDAO()->getIdItemMenuPaiByIdItemMenuPai($idItemMenuPai);
                $direitoItemMenuPai[] = self::getObjGrupoUsuarioDAO()->getDireitoItemMenuPai($idItemMenuPaiAtual);
                if ($idItemMenuPaiAtual == $idItemMenuPai) {
                    break;
                }
            } else {
                $direitoMenuPai = self::getObjGrupoUsuarioDAO()->getDireitoMenuPai($idMenuPai);
                $direitoFilho = self::getObjGrupoUsuarioDAO()->getDireitoItemMenuPai($idItemMenuPai);
            }
        }
        if (!empty($direitoMenuPai) && !empty($direitoMenuPai)) {
            $direitoItemMenuPai[] = $direitoMenuPai;
            $direitoItemMenuPai[] = $direitoFilho;
        }
        return $direitoItemMenuPai;
    }

    private function limpaDiretosPorGrupo($id) {
        ControlDb::delRowTable(array("table" => $this->_table,
            "campo" => $this->_id,
            "valor" => $id));
    }

    /**
     * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
     *
     * @author André Coura
     * @since 1.0 - 05/09/2008
     */
    public function alterar($id, $xml, $post, $file) {
        try {
            self::limpaDiretosPorGrupo($id);
            self::salvaDireitosGrupo(self::verificaCampos($post, "direitosGrupo"), $id);
        } catch (CrudException $e) {
            throw new CrudException($e->getMensagem());
        }
    }

    public function setIdDireito($id_direitos) {
        $this->id_direitos = $id_direitos;
    }

    public function getIdDireito() {
        return $this->id_direitos;
    }

    public function setIdGrupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    public function getIdGrupo() {
        return $this->id_grupo;
    }

    protected function getObjGrupoUsuarioDAO() {
        if ($this->objItem == null)
            $this->objItem = new GrupoUsuarioDAO();
        return $this->objItem;
    }

}

?>