<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoCentral extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Central");
		parent::setXmlConteudo(XML_CONT_CENTRO);
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