<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."UsuarioAdmin.class.php");

class CrudUsuario extends AbsCruds {

	public function executa($get,$post,$file){
		self::registraGrupoUsuario();
		self::setXmlForm(FWK_XML_CRUD."formUsuario.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridUsuario.xml");
		self::setClassModel(new UsuarioAdmin());
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
				self::deletaUsuario($get['id']);
				break;
			case "lista":
			default:
				self::listDados($post, $get['p']);
				break;
		}
	}

	private function registraGrupoUsuario(){
		foreach (self::getObjSessaoAdmin()->getGrupoUsuario() as $grupo) {
			if((int)$grupo == 1){
				self::getObjSmarty()->assign("GRUPO_USUARIO",true);
				break;
			}
		}
	}

	private function deletaUsuario($id){
		try{
			self::getClassModel()->deletarUsuario($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ã?tem deletado com sucesso!");
	}
	
}
?>