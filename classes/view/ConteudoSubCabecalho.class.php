<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoSubCabecalho extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Sub-Cabeçalho");
		parent::setXmlConteudo(XML_SUB_CABECALHO);
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