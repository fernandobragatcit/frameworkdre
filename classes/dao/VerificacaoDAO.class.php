<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class VerificacaoDAO extends AbsModelDao{

	public $_table = "fwk_verificacao";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "tabela_verificacao";

	public function checkHoje($tabela){
		$strQuery = "SELECT 
						if(data_verificacao >= CURDATE(), true, false)
					FROM 
						".$this->_table."
					WHERE 
						tabela_verificacao = '".$tabela."'";
		return end(ControlDb::getRow($strQuery));
	}
	
	public function setVerificacao($tabela){
		$objBanco = ControlDb::getBanco();
		$sql = "UPDATE ".$this->_table." SET data_verificacao = CURDATE() WHERE tabela_verificacao = '".$tabela."'";
		$objBanco->Execute($sql);
	}


}
?>