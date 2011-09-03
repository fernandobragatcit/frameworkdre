<?php
require_once (FWK_MODEL."AbsConteudosArea.class.php");

class ConteudoLatEsquerda extends AbsConteudosArea {

	public function executa($get,$post,$file){
		parent::setNomeForm("Conteúdo Lateral Esquerda");
		parent::setXmlConteudo(XML_LAT_ESQUERDA);
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