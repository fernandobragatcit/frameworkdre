<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

class GruposAdmin  extends AbsModelCruds{

    /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_grupo";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_grupo";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 05/09/2008
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_grupo = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

}
?>