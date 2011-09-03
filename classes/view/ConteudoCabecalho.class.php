<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoCabecalho extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Cabeçalho");
		parent::setXmlConteudo(XML_CABECALHO);
		parent::setStrClass(__CLASS__);
		switch($get["a"]){
			case "altera":
				self::salvaConteudo($post);
				break;
			default:
				self::getFormConteudo($get);
				break;
		}
	 }
}
?>