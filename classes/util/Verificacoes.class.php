<?php
require_once(FWK_DAO."TipoBasicoDAO.class.php");
require_once(FWK_DAO."VerificacaoDAO.class.php");

class Verificacoes {

	public static function verificouTabela($tabela) {
		
		$objVer = new VerificacaoDAO();
		if(!$objVer->checkHoje($tabela)){
		}
		
		print"<pre>";
		print_r($arrTabelas);
		die();
		
		
	}

}
?>