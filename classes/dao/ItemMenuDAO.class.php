<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class ItemMenuDAO extends AbsModelDao{

	public $_table = "fwk_item_menu";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_item_menu";

    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 26/07/2010
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_item_menu = $id;
			$arrCampos = self::buscaCampos($id);
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function getIdItemMenu(){
    	return $this->id_item_menu;
    }

    public function getItensInventario($idUsr){
//		$strQuery = "SELECT id_item_menu, id_menu_pai, id_item_menu_pai, tipo_item_menu, nome_item_menu, link_item_menu, ordem_item_menu
//					 FROM fwk_item_menu
//					 WHERE id_menu_pai = '10'";

	    $strQuery = "SELECT DISTINCT
							im.nome_item_menu, im.link_item_menu, im.id_item_menu, im.id_menu_pai, tipo_item_menu
						FROM
							fwk_item_menu im LEFT JOIN fwk_direitos di
							ON di.id_item_menu = im.id_item_menu
							LEFT JOIN fwk_direitos_usuario du
							ON di.id_direitos = du.id_direitos
							LEFT JOIN fwk_direitos_grupo dg
							ON di.id_direitos = dg.id_direitos
							INNER JOIN fwk_grupo gr
							ON dg.id_grupo = gr.id_grupo
						WHERE
							im.id_menu_pai = '10' AND
							(gr.id_grupo in(select id_grupo from fwk_grupo_usuario g1
											where g1.id_usuario = '".$idUsr."')
							OR du.id_usuario = '".$idUsr."')
						ORDER BY
							im.ordem_item_menu";
//			die($strQuery);
		return ControlDB::getAll($strQuery);
    }


}
?>