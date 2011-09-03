<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."DocumentoUsuario.class.php");

class CrudDocumentoUsuario extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formDocumentoUsuario.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridDocumentoUsuario.xml");
		self::setClassModel(new DocumentoUsuario());
		self::setStringClass("".__CLASS__."");
		switch($get['a']){
			case "formAlt":
				self::formAltera($get['id']);
				break;
			case "altera":
				self::postAltera($get['id'],$post,$file);
				break;
			case "lista":
			default:
				self::listDados($get['p']);
				break;
		}
	}
}
?>