<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."DireitosUsuarioAdmin.class.php");

/**
 * Classe CRUD de cadastro de direitos para um usuário específico.
 *
 * @author Andre Coura
 * @since 1.0 - 08/11/2009
 */
class CrudDireitosUsuario extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formDireitosUsuario.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridDireitosUsuario.xml");
		self::setClassModel(new DireitosUsuarioAdmin());
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
				self::listDados($post, $get['p']);
				break;
		}
	}

}
?>