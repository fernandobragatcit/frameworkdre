<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."GruposAdmin.class.php");

class CrudGrupos extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formGrupos.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridGrupos.xml");
		self::setClassModel(new GruposAdmin());
		self::setStringClass("".__CLASS__."");
		switch($get['a']){
			case "formCad":
				self::formCadastro();
				break;
			case "cadastra":
				self::postCadastro($post,$file);
				self::vaiPara(self::getStringClass());
				break;
			case "formAlt":
				self::formAltera($get['id']);
				break;
			case "altera":
				self::postAltera($get['id'],$post,$file);
				self::vaiPara(self::getStringClass());
				break;
			case "deleta":
				self::deleta($get['id']);
				break;
			case "lista":
			default:
				self::listDados($get['p']);
				break;
		}
	}
	
}
?>