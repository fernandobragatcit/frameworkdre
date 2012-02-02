<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."DireitosAdmin.class.php");

class CrudDireitos extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formDireitos.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridDireitos.xml");
		self::setClassModel(new DireitosAdmin());
		self::setStringClass("".__CLASS__."");
		switch($get['a']){
			case "formCad":
				self::formCadastro();
				break;
			case "cadastra":
				self::postCadastro($post,$file);
				break;
			case "formAlt":
				self::formAltera($get['id']);
				break;
			case "altera":
				self::postAltera($get['id'],$post,$file);
				break;
			case "deleta":
				self::deleta($get['id']);
				break;
			case "lista":
			default:
				self::listDados($post, $get['p']);
				break;
		}
	}
}
?>