<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_DAO."CategoriaBannersDAO.class.php");

class CrudCategoriaBanners extends AbsCruds {


	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formCategoriaBanners.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridCategoriaBanners.xml");
		self::setClassModel(new CategoriaBannersDAO());
		self::setStringClass("".__CLASS__."");
		switch($get['a']){
			case "formCad":
			case "formAlt":
				self::formAltera($get['id']);
				break;
			case "cadastra":
			case "altera":
				self::postAltera($get['id'],$post,$file);
				break;
			case "deleta":
				self::deleta($get['id']);
				break;
			case "lista":
			default:
				self::listDados($get, $post);
				break;
		}
	}

}
?>