<?php
require_once (FWK_MODEL."AbsModelDao.class.php");

class ZipDAO extends AbsModelDao {

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "nome_imagem_zip";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 06/08/2010
	 */
	public function cadastrarZip($xml, $post, $file) {
		try {
			self::validaForm($xml, $post);
			self::salvaPostAutoUtf8($post);
			//Tratamento especifico para o zip
			$this->nome_imagem_zip = $file[$this->strNomeCampo]["name"];
			self::uploadFile("", $this->strNomeCampo,DEPOSITO_IMGS."zip/");
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}


	/**
	 * Esta função retorna um array com os nomes dos arquivos que estão dentro do zip.
	 *
	 * @param String $strCaminho: 		Local do arquivo .zip
	 * @param String $strNomeArquivo: 	Nome do arquivo .zip
	 * @return array ou false no caso de erro
	 */
	function montaArrayZip($strCaminho, $strNomeArquivo){
	    // Instancia a classe
	    $zip = new ZipArchive();
	    // Tenta abrir o zip
	    if($zip->open($strCaminho.$strNomeArquivo)){
	        // Recupera o numero de arquivos do zip
	        $num_files = $zip->numFiles;
	        // percorre os arquivos pegando os nomes e colocando em um array
	        for($i=0; $i<=($num_files)-1;$i++){
	            $saida[] = $zip->getNameIndex($i);
	        }
	        // fecha a conexão
	        $zip->close();
	        // Retorna o array para ser manipulado
	        return $saida;
	    }
//	    return false;
	}

	/**
	 * Esta função descompacta arquivos de um zip
	 *
	 * @param String $strCaminho: 		Local do arquivo .zip
	 * @param String $strNomeArquivo: 	Nome do arquivo .zip
	 * @param String $strLocal: 		Pasta onde os arquivos devem ser descompactados
	 */
	public function descompactaZip($strCaminho, $strNomeArquivo, $strLocal){
		if (!extension_loaded("zip")) {
 			   print( "Nao esta habilitado php_zip.dll, edite seu php.ini" );
		 	   //no php.ini descomente essa linha, se nao existir basta cria-la: extension=php_zip.dll
 			   exit;
		}
	    // Instancia a classe do Zip
	  	$zip = new ZipArchive();
	    // Tenta abrir o zip
	    if($zip->open($strCaminho.$strNomeArquivo)){
	        $return = $zip->extractTo($strLocal); // executa o unzip
	        $zip->close(); // fecha a coneção com o .zip
	        unlink($strCaminho.$strNomeArquivo); //exclui o arquivo .zip depois de estarem descompactados
	    }else{
	        echo "O arquivo não pode ser aberto.";
	    }
	}

}
?>