<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoCentralInf extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Central Inferior");
		parent::setXmlConteudo(XML_CENTRO_INF);
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