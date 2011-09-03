<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class CategoriaBannersDAO extends AbsModelDao{

	public $_table = "fwk_tipo_basico";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_tipo_basico";

	public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->id_tipo_documento = TIPODOC_BANNERS;
			self::salvar();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 21/04/2011
	 */
	public function alterar($id,$xml,$post,$file){
		try{
			$this->id_tipo_basico = $id==""?null:$id;
			$arrCampos = self::buscaCampos($id);
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			$this->id_tipo_documento = TIPODOC_BANNERS;
			self::replace();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdCategoriaBanner(){
		return $this->id_tipo_basico;
	}

}
?>