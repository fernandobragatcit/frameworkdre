<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_DAO."ConfiguracoesGeraisDAO.class.php");

class CrudConfiguracoesGerais extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formConfiguracoesGerais.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridConfiguracoesGerais.xml");
		self::setClassModel(new ConfiguracoesGeraisDAO());
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
				self::deletaConfiguracoesGerais($get['id']);
				break;
			case "lista":
			default:
				self::listDados($get, $post);
				break;
		}
	}

	private function deletaConfiguracoesGerais($id){
		try{
			self::getClassModel()->deletarConfiguracoesGerais($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ã?tem deletado com sucesso!");
	}
	
}
?>