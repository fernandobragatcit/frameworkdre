<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_DAO."FicheiroDAO.class.php");
require_once(FWK_DAO."FotosDAO.class.php");

class CrudFicheiros extends AbsCruds {

	public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formFicheiros.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridFicheiros.xml");
		self::setClassModel(new FicheiroDAO());
		self::setStringClass("".__CLASS__."");
		switch($get['a']){
			case "formCad":
			case "formAlt":
				self::formAltera($get['id']);
				break;
			case "cadastra":
			case "altera":
				self::postAlteraFicheiro($get['id'],$post,$file);
				break;
			case "deleta":
				self::deletaFicheiro($get['id']);
				break;
			case "lista":
			default:
				self::listDados($get['p']);
				break;
		}
	}


	private function postAlteraFicheiro($id,$post,$file){
		try{
			$arrDados = self::getClassModel()->buscaCampos($id,0);
			$post = FormataPost::mergeArrayPost($arrDados,$post);
			
			if($post["nome_arquivo_null"]){
				self::anulaCampo($id, "id_foto");
				$arrDados["id_foto"] = null;
				$post["id_foto"] = null;
				$file["nome_arquivo"]["name"] = null;
			}
			if($arrDados["id_foto"] != null){
				if($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null){
					self::getObjFoto()->alterar($arrDados["id_foto"],parent::getXmlForm(),$post,$file);
					$post["id_foto"] = self::getObjFoto()->getIdFoto();
				}else{
					$post["id_foto"] = $arrDados["id_foto"];
				}
			}else{
				if($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null){
					self::getObjFoto()->cadastrar(parent::getXmlForm(),$post,$file);
					$post["id_foto"] = self::getObjFoto()->getIdFoto();
				}else{
					$post["id_foto"] = null;
				}
			}
			self::getClassModel()->alterar($id,self::getXmlForm(),$post,$file);
			self::vaiPara(self::getStringClass()."&msg=Ã�tem alterado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}
	}


	protected function deletaFicheiro($id){
		try{
			$arrDados = self::getClassModel()->buscaCampos($id,0);
			self::getObjFoto()->deletaFoto($arrDados["id_foto"]);
			self::getClassModel()->deletar($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ã�tem deletado com sucesso!");
	}


	private function getObjFoto(){
		if($this->obFoto == null){
			$this->obFoto = new FotosDAO();
		}
		return $this->obFoto;
	}


}
?>