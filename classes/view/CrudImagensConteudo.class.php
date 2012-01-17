<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_MODEL."ImagensConteudo.class.php");
require_once (FWK_DAO."ZipDAO.class.php");

class CrudImagensConteudo extends AbsCruds {

	private $objZip;
	private $objImagem;
	private $objRar;

	public function executa($get, $post, $file) {
		self::setXmlForm(FWK_XML_CRUD."formImagensConteudo.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridImagensConteudo.xml");
		self::setClassModel(new ImagensConteudo());
		self::setStringClass("".__CLASS__."");
		switch ($get["a"]) {
			case "formCad" :
				self::formCadastro($post, $file);
				break;
			case "cadastra" :
				self::postCadastroImagem($post, $file);
				break;
			case "formAlt" :
				self::formAlteraImagem($get["id"]);
				break;
			case "altera" :
				self::postAltera($get["id"], $post, $file);
				break;
			case "deleta" :
				self::deletaImagem($get["id"]);
				break;
			case "lista" :
			default :
				self::listDados($get["p"]);
				break;
		}



//		if(isset($get["msg"]) && trim($get["msg"]) != ""){
//
//			print_r($get);
//			print("oi".$get["msg"]);
//			die($get["msg"]);
//
//		}
	}

	private function postCadastroImagem($post,$file){
		try{
			$this->tipo_imagem = self::getObjImagem()->getExtFile($file["nome_imagem"]["type"]);
			$this->strNomeCampo = "nome_imagem";

			//se o campo de arquivo unico estiver vazio ele pega o do arquivo zip.
			if($this->tipo_imagem == "" || $this->tipo_imagem == null){
				$this->tipo_imagem = self::getObjImagem()->getExtFile($file["nome_imagem_zip"]["type"]);
				$this->strNomeCampo = "nome_imagem_zip";
			}
				//se vier um arquivo zip
			if($this->strNomeCampo == "nome_imagem_zip" && $this->tipo_imagem == ".zip"){
				self::getObjZip()->cadastrarZip(parent::getXmlForm(),$post,$file);
				$zip = self::getObjZip()->montaArrayZip(DEPOSITO_IMGS."zip/", $file["nome_imagem_zip"]["name"]);
				self::getObjZip()->descompactaZip(DEPOSITO_IMGS."zip/",$file["nome_imagem_zip"]["name"],DEPOSITO_IMGS."zip/");
				for($g=0; $g<count($zip); $g++){
					$nome = $zip[$g];
					self::getObjImagem()->cadastrarImagemZip(parent::getXmlForm(),$post,$file,$nome,$this->strNomeCampo);
					$this->objImagem = null;
				}
			}else{
				self::getObjImagem()->cadastrarImagem(parent::getXmlForm(),$post,$file,$this->strNomeCampo);
				self::vaiPara(self::getStringClass()."&msg=Ítem cadastrado com sucesso!");
			}

		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}
	}

	protected function formAlteraImagem($id){
		$arrDados = self::getClassModel()->buscaCampos($id);

		self::getObjSmarty()->assign("NOME_IMAGENS",$arrDados["nome_imagem"]);

		self::getClassModel()->setTipoForm(self::getTipoForm());
		self::getClassModel()->preencheForm(self::getXmlForm(),$id, self::getStringClass());
	}

	protected function deletaImagem($id){
		try{
			self::getClassModel()->deletarImagem($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");
	}

	private function getObjZip(){
		if($this->objZip == null)
			$this->objZip = new ZipDAO();
		return $this->objZip;
	}

	private function getObjImagem(){
		if($this->objImagem == null)
			$this->objImagem = new ImagensConteudo();
		return $this->objImagem;
	}

	
 /* public function listDados2() {
		chdir(DEPOSITO_IMGS);
		$diretorio = getcwd();
		$ponteiro = opendir($diretorio);
		$arrnomes = array();
		while ($nome_itens = readdir($ponteiro)) {
			$arrnomes[] = $nome_itens;
			if($nome_itens!="." && $nome_itens!=".."){
				$tipoArquivo = explode(".",$nome_itens);
				if(count($tipoArquivo) == 2){
					if(in_array($tipoArquivo[1],array("jpg","png","gif"))){
						$strQuery = "INSERT INTO fwk_imagens (nome_imagem, tipo_imagem, data_usuario_cad, id_usuario_cad)
										VALUES ('".$nome_itens."','".$tipoArquivo[1]."', '2012-01-16', '5')";
						if(parent::getObjDB()->Execute($strQuery))
							echo "Inserido -> ".$strQuery."<br /><br />";
					}
				}
			}
		}
		print("<br>");
		print("<br>");
		print("<br>");
		print("-----------------------------");
		print("<pre>");
		print_r($arrnomes);
		die();
	}*/

}
?>