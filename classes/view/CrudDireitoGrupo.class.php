<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."DireitosGrupoAdmin.class.php");

class CrudDireitoGrupo extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formDireitoGrupo.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridDireitoGrupo.xml");
		self::setClassModel(new DireitosGrupoAdmin());
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
				self::listDados($get, $post);
				break;
		}
	}
}
?>