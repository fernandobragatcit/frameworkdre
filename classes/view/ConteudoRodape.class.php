<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoRodape extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Rodapé");
		parent::setXmlConteudo(XML_RODAPE);
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