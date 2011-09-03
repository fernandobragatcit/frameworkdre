<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."PastasAdmin.class.php");

class CrudPastas extends AbsCruds {

    public function executa($get,$post,$file){
		self::setXmlForm(FWK_XML_CRUD."formPastas.xml");
		self::setClassModel(new PastasAdmin());
		self::setStringClass("".__CLASS__."");
		switch($get["a"]){
			case "formCad":
				self::formCadastro();
				break;
			case "cadastra":
				self::postCadastro($post,$file);
				break;
			case "formAlt":
				self::formAlteraPasta($get["pasta"]);
				break;
			case "altera":
				self::postAlteraPasta($get["pasta"],$post,$file);
				break;
			case "deleta":
				self::deletaPasta($get["pasta"]);
				break;
			default:
				self::listaPastasPasta($get);
				break;
		}
	}

	//exibe a estrutura de diretorios da pasta atual
	private function listaPastasPasta($get){
		if(isset($get["msg"]) && $get["msg"] != ""){
			parent::getObjSmarty()->assign("MENS_GRID",$get["msg"]);

		}

		// pega o endereço do diretório
		$diretorio = getcwd();
		// abre o diretório
		$ponteiro  = opendir($diretorio);
		$arrPastas = array();
		$cont = 0;
		// monta os vetores com os itens encontrados na pasta
		while ($strElemPasta = readdir($ponteiro)) {
		    if(is_dir($strElemPasta)){
		    	if(!in_array($strElemPasta,self::getClassModel()->getArrConfigPastas())){
		    		$arrPastas[$cont][0] = $strElemPasta;
		    		$arrPastas[$cont][1] = "<a href=\"?c=".parent::getObjCrypt()->cryptData(
		    								parent::getStringClass()."&a=formAlt&pasta=".$strElemPasta).
											"\" title=\"Alterar Pasta\">alterar</a> &nbsp;";

		    		$arrPastas[$cont++][1].= "<a href=\"javascript:void(confirmIr('?c=".parent::getObjCrypt()->cryptData(
		    								parent::getStringClass()."&a=deleta&pasta=".$strElemPasta)."', " .
		    										"'Você realmente gostaria de excluir a pasta \'".$strElemPasta."\'?'));\" " .
		    												"title=\"Excluir Pasta\">deletar</a>";
		    	}
		    }
		}
		//verifica-se se existem pastas sem ser as do sistema disponíveis para serem listadas
		if(count($arrPastas)>0){
			//aviso ao grid que existem dados para ele
			parent::getObjSmarty()->assign("NUM_DADOS_INI",TRUE);
			//seto os títulos das colunas
			parent::getObjSmarty()->assign("ARR_TITULOS",array("Nome da Pasta","Ações"));
			//envio os valores para a pasta
			parent::getObjSmarty()->assign("ARR_DADOS",$arrPastas);
		}

		self::regJsGrid();
		parent::getObjSmarty()->assign("TITULO_GRID","Pastas");
		parent::getObjSmarty()->assign("VAI_PARA",parent::getObjCrypt()->cryptData(parent::getStringClass()."&a=formCad"));
		parent::getObjHttp()->escreEm("CORPO", FWK_HTML_GRID."gridSimples.tpl");
	}

	private function formAlteraPasta($strPasta){
		$objCtrlForm = new ControlForm(parent::getXmlForm());
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm("CrudPastas&a=altera&pasta=".$strPasta);
		$objCtrlForm->registraFormValues(array("nome_pasta"=>$strPasta));
	}

	protected function postAlteraPasta($strPasta,$post,$file){
		try{
			self::getClassModel()->alterarPasta($strPasta,parent::getXmlForm(),$post,$file);
			self::vaiPara(self::getStringClass()."&msg=Pasta alterada com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}
	}

	private function deletaPasta($nomePasta){
		try{
			self::getClassModel()->deletarPasta($nomePasta);
			self::vaiPara(self::getStringClass()."&msg=Pasta deletada com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}
	}

	private function regJsGrid(){
		$objCtrlCss = ControlJS::getJS();
		$objCtrlCss->addJs(FWK_JS."MochiKit/MochiKit.js");
		$objCtrlCss->addJs(FWK_JS."actionsGrid.js");
	}
}
?>