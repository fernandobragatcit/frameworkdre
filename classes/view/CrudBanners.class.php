<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_DAO."BannersDAO.class.php");
require_once (FWK_DAO."FotosDAO.class.php");

class CrudBanners extends AbsCruds {
	
	private $obFoto;

	public function executa($get, $post, $file) {
		self::setXmlForm(FWK_XML_CRUD."formBanners.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridBanners.xml");
		self::setClassModel(new BannersDAO());
		self::setStringClass("".__CLASS__."");
		switch ($get["a"]) {
			case "formCad" :
			case "formAlt" :
				self::formAltera($get["id"]);
				break;
			case "status" :
				self::alteraStatusBanner($get["id"]);
				break;
			case "cadastra" :
			case "altera" :
				self::postAlteraBanner($get["id"], $post, $file);
				break;
			case "deleta" :
				self::deletaBanner($get["id"]);
				break;
			case "lista" :
			default :
				self::listDados($get, $post);
				break;
		}
	}

	protected function postAlteraBanner($id, $post, $file){
		try{
			$arrDados = self::getClassModel()->buscaCampos($id,0);
			if($arrDados["id_foto"] != null){
				if($file["nome_arquivo"]["name"] != "" || $file["nome_arquivo"]["name"] != null){
					self::getObjFoto()->alterar($arrDados["id_foto"], parent::getXmlForm(),$post,$file);
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
				
			//salva no banco o nome da categoria como parametro para curso
			self::getClassModel()->alterar($id,self::getXmlForm(),$post,$file);
			self::vaiPara(self::getStringClass()."&msg=Item alterado com sucesso!");
				
		}catch(CrudException $e){
			die($e->getMensagem());
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}

	}

	private function alteraStatusBanner($id){
		try{
			self::getClassModel()->alterarStatusBanner($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");

	}

	protected function deletaBanner($id){
		try{
			self::getClassModel()->deletarBanner($id);
			self::vaiPara(self::getStringClass()."&msg=Item deletado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}

	}

	private function getObjFoto(){
		if($this->obFoto == null){
			$this->obFoto = new FotosDAO();
		}
		return $this->obFoto;
	}

}
?>