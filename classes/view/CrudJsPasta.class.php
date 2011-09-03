<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_MODEL."JsPasta.class.php");

class CrudJsPasta extends AbsCruds {

	public function executa($get, $post, $file) {
		self::setXmlForm(FWK_XML_CRUD."formJsPasta.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridJsPasta.xml");
		self::setClassModel(new JsPasta());
		self::setStringClass("".__CLASS__."");
		switch ($get["a"]) {
			case "formCad" :
				self::formCadastro($post, $file);
				break;
			case "cadastra" :
				self::postCadastro($post, $file);
				break;
			case "formAlt" :
				self::formAlteraJs($get["id"]);
				break;
			case "status" :
				self::alteraStatusJs($get["id"]);
				break;
			case "altera" :
				self::postAltera($get["id"], $post, $file);
				break;
			case "deleta" :
				self::deletaJs($get["id"]);
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
	protected function formAlteraJs($id){
		$arrDados = self::getClassModel()->buscaCampos($id);

		self::getObjSmarty()->assign("NOME_JS",$arrDados["nome_javascript"]);
		//self::getObjSmarty()->assign("NOME_IMAGENS",$arrDados["nome_imagem"]);

		self::getClassModel()->setTipoForm(self::getTipoForm());
		self::getClassModel()->preencheForm(self::getXmlForm(),$id, self::getStringClass());
	}

	private function alteraStatusJs($id){
		try{
			self::getClassModel()->alterarStatusJs($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");

	}

	protected function deletaJs($id){
		try{
			self::getClassModel()->deletarJs($id);
			self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}

	}

/*	public function listDados2() {
		print("<pre>");
		chdir(DEPOSITO_JS);
		$diretorio = getcwd();
		$ponteiro = opendir($diretorio);
		$arrnomes = array();
		while ($nome_itens = readdir($ponteiro)) {
			$arrnomes[] = $nome_itens;
			if($nome_itens!="." && $nome_itens!=".."){
				$tipoArquivo = explode(".",$nome_itens);
				$ultimaPosicao = $tipoArquivo[count($tipoArquivo)-1];
					if($ultimaPosicao=="js"){
						$strQuery = "INSERT INTO fwk_javascript (nome_javascript, status, data_cadastro, id_usuario_cad)
										VALUES ('".$nome_itens."', 'S', '2010-10-25', '1')";
						if(parent::getObjDB()->Execute($strQuery))
							echo "Inserido -> ".$strQuery."<br /><br />";
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