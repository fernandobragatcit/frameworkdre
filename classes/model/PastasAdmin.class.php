<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");
require_once(FWK_UTIL."FormataString.class.php");

class PastasAdmin extends AbsModelCruds{

    public function cadastrar($xml,$post,$file) {
		self::validaForm($xml,$post);
		$strPasta = FormataString::renomeiaPasta($post["nome_pasta"]);

		if (isset($strPasta) && $strPasta != "") {
		    if(is_dir($strPasta."/"))
		    	throw new CrudException("Ja existe uma pasta com o nome sugerido.");
		    if(!mkdir($strPasta))
		    	throw new CrudException("Não foi possível criar a pasta.");
			//Criada a pasta, gera-se a estrutura interna dela.
			mkdir($strPasta."/config/");
			copy(FWK_DEFAULT."index.php", $strPasta."/index.php");
			copy(FWK_DEFAULT."config.xml", $strPasta."/config/config.xml");
		}
    }


    public function alterarPasta($strPasta,$xml,$post,$file) {
    	$post["nome_pasta"] = FormataString::renomeiaPasta($post["nome_pasta"]);
		self::validaForm($xml,$post);

		if (isset($strPasta) && $strPasta != "") {
		    if(!is_dir($strPasta."/"))
		    	throw new CrudException("A pasta referida não existe.");
			if(!rename($strPasta, trim($post["nome_pasta"])))
				throw new CrudException("A pasta não pode ser alterada.");
		}
    }

	/**
	 * As pastas a serem ignoradas
	 */
    public function getArrConfigPastas(){
    	$arrFilesSistem = array(".",
								"..",
								".svn",
								"classes",
								"config",
								"framework",
								"html",
								"modulos",
								"uploads",
								".settings",
								"arquivos");

    	return $arrFilesSistem;
    }


    public function deletarPasta($strPasta) {
		if (isset($strPasta) && $strPasta != "") {
		    if(!is_dir($strPasta."/"))
		    	throw new CrudException("A pasta referida não existe.");

		//verifica-se se existem pastas filhas, caso tenha não será permitido deletar
		if (is_dir($strPasta)) {
		    if ($dh = opendir($strPasta)) {
		        while (($file = readdir($dh)) !== false) {
		            if (!in_array($file,self::getArrConfigPastas()) && $file != "index.php"){
		            	throw new CrudException("A pasta referida não pode ser deletada por possuir pastas internas.");
		            }
		        }
		        closedir($dh);
		    }
		}


		if ($dh = opendir($strPasta."/config/")) {
	        while (($file = readdir($dh)) !== false) {
	            if (!is_dir($strPasta."/config/".$file))
	            	if(!unlink($strPasta."/config/".$file))
						throw new CrudException("A pasta não pode ser alterada.");
	        }
	        closedir($dh);
	    }
	    if(!rmdir($strPasta."/config/"))
				throw new CrudException("A pasta \"".$strPasta."/config/\" não pode ser alterada.");
	    if(!unlink($strPasta."/index.php"))
			throw new CrudException("O arquivo \"".$strPasta."/index.php\" não pode ser alterada.");
		chmod($strPasta."/", 755);
		if(!rmdir($strPasta))
			throw new CrudException("A pasta \"".$strPasta."\" não pode ser deletada.");
		}

    }



}
?>