<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class EstadosDAO extends AbsModelDao{

	public $_table = "fwk_estados";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_estado";


	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 12/04/2011
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
					
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function getIdEstado(){
    	return $this->id_estado;
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 12/04/2011
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_estado = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }
	public function getNomeEstadoById($id) {
       $strQuery = "
                       SELECT estado
                       FROM ".$this->_table." 
                       WHERE id_estado=".$id;
       
       $result = ControlDb::getRow($strQuery, 1);
       //self::debuga($strQuery);
       
       return $result[0];
   }
	
}
?>