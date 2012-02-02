<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_MODEL."CssPasta.class.php");
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class CrudCssPasta extends AbsCruds {

	public function executa($get, $post, $file) {
		self::setXmlForm(FWK_XML_CRUD."formCssPasta.xml");
		self::setXmlGrid(FWK_XML_CRUD."gridCssPasta.xml");
		self::setClassModel(new CssPasta());
		self::setStringClass("".__CLASS__."");
		switch ($get["a"]) {
			case "formCad" :
				self::formCadastro($post, $file);
				break;
			case "cadastra" :
				self::postCadastro($post, $file);
				break;
			case "formAlt" :
				self::formAlteraCss($get["id"]);
				break;
			case "status" :
				self::alteraStatusCss($get["id"]);
				break;
			case "altera" :
				self::postAltera($get["id"], $post, $file);
				break;
			case "deleta" :
				self::deletaCss($get["id"]);
				break;
			case "lista" :
			default :
				self::listDados($post, $get["p"]);
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


	protected function formAlteraCss($id){
		$arrDados = self::getClassModel()->buscaCampos($id);

		self::getObjSmarty()->assign("NOME_CSS",$arrDados["nome_estilo_css"]);

		self::getClassModel()->setTipoForm(self::getTipoForm());
		self::getClassModel()->preencheForm(self::getXmlForm(),$id, self::getStringClass());
	}

	private function alteraStatusCss($id){
		try{
			self::getClassModel()->alterarStatusCss($id);
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}
		self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");

	}

	protected function deletaCss($id){
		try{
			self::getClassModel()->deletarCss($id);
			self::vaiPara(self::getStringClass()."&msg=Ítem deletado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
			return;
		}

	}

/*
 * public function listDados2() {
		chdir(DEPOSITO_CSS);
		$diretorio = getcwd();
		$ponteiro = opendir($diretorio);
		$arrnomes = array();
		while ($nome_itens = readdir($ponteiro)) {
			$arrnomes[] = $nome_itens;
			if($nome_itens!="." && $nome_itens!=".."){
				$tipoArquivo = explode(".",$nome_itens);
				if(count($tipoArquivo) == 2){
					if($tipoArquivo[1]=="css"){
						$strQuery = "INSERT INTO fwk_estilo_css (nome_estilo_css, status, data_cadastro, id_usuario_cad)
										VALUES ('".$nome_itens."', 'S', '2010-08-04', '1')";
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