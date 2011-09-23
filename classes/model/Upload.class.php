<?php
require_once(FWK_UTIL."FormataString.class.php");
require_once(FWK_EXCEPTION."UploadException.class.php");

/**
 * Classe de manipulação de arquivos para upload
 *
 * @author André Coura
 * @since 10/08/2008
 */
class Upload{

	private $arquivoDestino = ""; //caminho do arquivo de destino
	private $tmpFile; //nome temporário
	private $nomeArquivo; //nome do arquivo
	private $nomeArquivoOriginal; //nome original do arquivo
	private $caminhoCompleto; //caminho completo do arquivo
	private $tiposArquivos = array(); //array com o tipo de arquivos suportados
	private $arquivosInvalidos = array(); //tipos banidos do upload
	private $extensao; //extensao do arquivo
	private $err = ""; //erros
	private $maxFileSize = ""; //tamanho máximo do arquivo a ser passado
	private $fileSize; //tamnaho do arquivo
	private $overwrite = "S"; //sobre-escreve arquivo existente
	private $parsername = false; //executar parser no nome
	private $limitTime = 90000; //tempo de expiração do upload

	/**
	 * Construtor
	 */
	public function __construct(){

	}
	/**
	 * Define os tipos de arquivos suportados pelo upload
	 */
	public function setExtArquivo($arrExt){
		$this->tiposArquivos = $arrExt;
	}
	/**
	 * Retorna uma sting com todos os arquivos suportado seprados por ','
	 */
	public function getExtArquivo(){
		return implode(", ", $this->tiposArquivos);
	}

	/**
	 * Definição de arquivos inválidos
	 */
	public function setArquivosInvalidos($arrExt){
		$this->arquivosInvalidos = $arrExt;
	}
	/**
	 * Retorna uma string com todos os arquivos inválidos separados por ','
	 */
	public function getArquivosInvalidos(){
		return implode(", ", $this->arquivosInvalidos);
	}


	/**
	 * Definição do tamanho máximo do arquivo
	 */
	public function setMaxFileSize($maxsize){
		$this->maxFileSize = $maxsize;
	}
	/**
	 * Definição se grava ou não por cima de um arquivo ja existente
	 */
	public function setOverwrite($ow){
		$this->overwrite = $ow;
	}
	/**
	 * busca a extensao do arquivo atual
	 */
	public function getExtensao(){
		return $this->extensao;
	}

	public function getNomeArquivo(){
		return $this->nomeArquivo;
	}
	/**
	 * Nome do arquivo original
	 */
	public function getNomeArquivoOriginal(){
		return $this->nomeArquivoOriginal;
	}
	/**
	 * caminho completo do arquivo e o próprio arquivo
	 */
	public function getCaminhoCompleto(){
		return $this->caminhoCompleto;
	}
	/**
	 * tamanho do arquivo
	 */
	public function getFileSize(){
		return $this->fileSize;
	}
	/**
	 * Relatório de erros
	 */
	public function getErros(){
		return $this->err;
	}

	public function setErros($erro){
		$this->err=$erro;
	}
	public function setParserName($boolean){
		$this->parsername = $boolean;
	}

	public function getParserName(){
		return $this->parsername;
	}

	/**
	 * Método de execução do upload
	 */
	public function uploadFile($input, $pastaDestino, $novoNomeArquivo=""){
		set_time_limit($this->limitTime);//tempo transação
		$this->tmpFile = $_FILES[$input]['tmp_name'];
		$this->nomeArquivoOriginal = $_FILES[$input]['name'];
		$this->extensao = ".".strtolower(end(explode('.',$this->nomeArquivoOriginal)));

		if(count($this->tiposArquivos) > 0){
			if(!in_array($this->extensao, $this->tiposArquivos)){
				$this->setErros(" Extensão do arquivo inválida!");
				throw new UploadException(" Extensão do arquivo inválida!");
			}
		}
		if(count($this->arquivosInvalidos) > 0){
			if(in_array($this->extensao,$this->arquivosInvalidos) ){
				$this->setErros("Arquivo com extensão restrita!");
				throw new UploadException("Arquivo com extensão restrita!");
			}
		}
		if($this->parsername){
			$objFormatStr = new FormataString();
			$novoNomeArquivo = $objFormatStr->subsChars($novoNomeArquivo);
		}
		if($novoNomeArquivo != ''){
			$this->nomeArquivo = $novoNomeArquivo . $this->extensao;
			$this->caminhoCompleto = $pastaDestino . $novoNomeArquivo . $this->extensao;
		}else{
			$novoNomeArquivo = $this->nomeArquivoOriginal;
			$this->caminhoCompleto = $pastaDestino . $this->nomeArquivoOriginal;
		}
		if(file_exists($this->caminhoCompleto) && $this->overwrite == 'N'){
			$this->setErros("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
			throw new UploadException("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
		}
		$srcfile = $this->arquivoDestino . $pastaDestino . $novoNomeArquivo;
		if($this->err == ""){
    		if (is_uploaded_file($this->tmpFile)){
				if (!copy($this->tmpFile,$srcfile)){
					$this->setErros(" Erro no upload do arquivo!");
				}else{
					$this->fileSize = fileSize($srcfile);
					if($this->maxFileSize != ''){
						if($this->fileSize > $this->maxFileSize){
							$this->setErros("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
							unlink($srcfile);
							throw new UploadException("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
						}
					}
				}
    		}
		}
		unlink($this->tmpFile);
		return true;
	}

	/**
	 * Método de execução do upload multiplo
	 */
	public function uploadMultiploFile($input, $pastaDestino, $novoNomeArquivo="", $indice){
		set_time_limit($this->limitTime);//tempo transação
		$this->tmpFile = $_FILES[$input]['tmp_name'][$indice];
		$this->nomeArquivoOriginal = $_FILES[$input]['name'][$indice];
		$this->extensao = strtolower(strstr($this->nomeArquivoOriginal, '.'));

		if(count($this->tiposArquivos) > 0){
			if(!in_array($this->extensao, $this->tiposArquivos)){
				$this->setErros(" Extensão do arquivo inválida!");
				throw new UploadException(" Extensão do arquivo inválida!");
			}
		}
		if(count($this->arquivosInvalidos) > 0){
			if(in_array($this->extensao,$this->arquivosInvalidos) ){
				$this->setErros("Arquivo com extensão restrita!");
				throw new UploadException("Arquivo com extensão restrita!");
			}
		}
		if($this->parsername){
			$objFormatStr = new FormataString();
			$novoNomeArquivo = $objFormatStr->subsChars($novoNomeArquivo);
		}
		if($novoNomeArquivo != ''){
			$this->nomeArquivo = $novoNomeArquivo . $this->extensao;
			$this->caminhoCompleto = $pastaDestino . $novoNomeArquivo . $this->extensao;
		}else{
			$novoNomeArquivo = $this->nomeArquivoOriginal;
			$this->caminhoCompleto = $pastaDestino . $this->nomeArquivoOriginal;
		}
		if(file_exists($this->caminhoCompleto) && $this->overwrite == 'N'){
			$this->setErros("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
			throw new UploadException("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
		}
		$srcfile = $this->arquivoDestino . $pastaDestino . $novoNomeArquivo;
		if($this->err == ""){
    		if (is_uploaded_file($this->tmpFile)){
				if (!copy($this->tmpFile,$srcfile)){
					$this->setErros(" Erro no upload do arquivo!");
				}else{
					$this->fileSize = fileSize($srcfile);
					if($this->maxFileSize != ''){
						if($this->fileSize > $this->maxFileSize){
							$this->setErros("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
							unlink($srcfile);
							throw new UploadException("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
						}
					}
				}
    		}
		}
		unlink($this->tmpFile);
		return true;
	}

	/*
	 * Método de execução da copia dos arquivos do zip
	 */
	public function copyFile($pastaOrigem, $pastaDestino, $nomeArquivo){
		set_time_limit($this->limitTime);//tempo transação
		$this->tmpFile = $pastaOrigem.$nomeArquivo;
		$this->nomeArquivoOriginal = $nomeArquivo;
		$this->extensao = end(explode(".",$this->nomeArquivoOriginal));
		if(count($this->tiposArquivos) > 0){

			if(!in_array($this->extensao, $this->tiposArquivos)){
				$this->setErros(" Extensão do arquivo inválida!");
				throw new UploadException(" Extensão do arquivo inválida!");
			}
		}
		if(count($this->arquivosInvalidos) > 0){
			if(in_array($this->extensao,$this->arquivosInvalidos) ){
				$this->setErros("Arquivo com extensão restrita!");
				throw new UploadException("Arquivo com extensão restrita!");
			}
		}
		if($this->parsername){
			$objFormatStr = new FormataString();
			$novoNomeArquivo = $objFormatStr->subsChars($novoNomeArquivo);
		}
		if($novoNomeArquivo != ''){
			$this->nomeArquivo = $novoNomeArquivo . $this->extensao;
			$this->caminhoCompleto = $pastaDestino . $novoNomeArquivo . $this->extensao;
		}else{
			$novoNomeArquivo = $this->nomeArquivoOriginal;
			$this->caminhoCompleto = $pastaDestino . $this->nomeArquivoOriginal;
		}
		if(file_exists($pastaDestino.$nomeArquivo) && $this->overwrite == 'N'){
			$this->setErros("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
			throw new UploadException("O arquivo ja existe na pasta e não pôde ser sobre-escrito!");
		}
		$srcfile = $pastaDestino . $novoNomeArquivo;
		if($this->err == ""){
				if(!copy($this->tmpFile,$srcfile)){
					$this->setErros(" Erro no upload do arquivo!");
				}else{
					$this->fileSize = fileSize($srcfile);
					if($this->maxFileSize != ''){
						if($this->fileSize > $this->maxFileSize){
							$this->setErros("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
							unlink($srcfile);
							throw new UploadException("Tamnho do arquivo inválido: ".$this->fileSize."bytes(Tamanho máximo de: " . $this->maxFileSize . " bytes)");
						}
					}
				}
		}

		unlink($this->tmpFile);
		return true;
	}

}
?>