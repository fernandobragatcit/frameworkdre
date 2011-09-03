<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoLatDireita extends AbsConteudosArea {

	 public function executa($get,$post,$file){
	 	parent::setNomeForm("Conteúdo Lateral Direita");
		parent::setXmlConteudo(XML_LAT_DIREITA);
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