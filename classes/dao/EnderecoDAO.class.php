<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class EnderecoDAO extends AbsModelDao{

	public $_table = "fwk_endereco";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_endereco";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 21/04/2011
	 */
	public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
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
			$this->id_endereco = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($this);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdEndereco(){
		return $this->id_endereco;
	}



}
?>