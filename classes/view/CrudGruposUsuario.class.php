<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."GruposUsuario.class.php");

class CrudGruposUsuario extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formGruposUsuario.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridGruposUsuario.xml");
		self::setClassModel(new GruposUsuario());
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