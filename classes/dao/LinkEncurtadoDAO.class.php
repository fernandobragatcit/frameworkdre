<?php
require_once(FWK_CONTROL."ControlDB.class.php");

class LinkEncurtadoDAO {

	public $_table = "fwk_link_encurtado";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_link";

	public function cadastrar($urlLong){
		try{
			$objBanco = ControlDb::getBanco();
			$strQuery = "INSERT INTO ".$this->_table."
							(url_completa)
						VALUES
							('".$urlLong."')";
			if(!$objBanco->Execute($strQuery)){
				if(self::ErrorMsg()){
					print($strQuery."<br />");
					print("<pre>");
					print_r($post);
					print("<br/><br /><h1>".self::ErrorMsg()."</h1></pre>");
				}
				throw new CrudException("Erro ao salvar ShortUrl!");
			}else{
				return self::getIdByUrl($urlLong);
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getUrlById($id){
		$strQuery = "SELECT
						url_completa
					FROM 
						".$this->_table."
					WHERE 
						".$this->_id." = '".$id."'";
		return end(ControlDB::getRow($strQuery,3));
	}

	public function getIdByUrl($urlLong){
		$strQuery = "SELECT
						id_link
					FROM 
						".$this->_table."
					WHERE 
						url_completa = '".$urlLong."'";
		return end(ControlDB::getRow($strQuery,3));
	}
	
}
?>