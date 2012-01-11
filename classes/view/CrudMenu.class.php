<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."MenuAdmin.class.php");

class CrudMenu extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formMenu.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridMenu.xml");
		self::setClassModel(new MenuAdmin());
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
				self::deletaMenu($get['id']);
				break;
			case "lista":
			default:
				self::listDados($get['p']);
				break;
		}
	}

	private function deletaMenu($id){
		try{
			self::getClassModel()->deletarMenu($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");
	}
}
?>