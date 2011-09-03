<?php
include_once(SERV_APLICACAO_CLASSES_MODEL."class.Upload.php");
/**
 * Classe para manipulação dos arquivos de upload
 *
 * @author André Coura
 * @since 02/05/2007
 */
class ControlUpload extends Upload{

	private $fileConfig;
	private $strPastaDestino;
	/**
	 * Cosntrutor da classe controlUpload
	 *
	 * @author André Coura
	 * @since 02/04/2007
	 * @param String $arquivo_configuracao arquivo XML contendo a configuração para o upload
	 * @return Exception erro de exceção por não ter encontrado o arquivo
	 */
	public function ControlUpload($arquivo_configuracao){
 		try
 		{
 			if(!file_exists($arquivo_configuracao))
		 		throw new Exception("Não foi possivel encontrar o arquivo de configuração");
			$obXML = simplexml_load_file($arquivo_configuracao);
			$this->fileConfig = $obXML->upload_files;
			$this->setPastaDestino((string)$this->fileConfig->pasta_destino."/");
		}
		catch (Exception $e)
		{
		  return $e->getMessage();
		}
	}
	/**
	 * Busca as extensões permitidas de arquivo para upload
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return Array $arrExtensoes arr contendo todas as extensoes permitidas setadas no XML
	 */
	public function getExtensoes(){
		$arrExtensoes = array();
		for($i=0;$i<count($this->fileConfig->ext_arquivos->extensao);$i++){
			array_push($arrExtensoes,(string)$this->fileConfig->ext_arquivos->extensao[$i]);
		}
		return $arrExtensoes;
	}
	/**
	 * Busca as extensões inválidas de arquivo para upload
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return Array $arrExtensoes arr contendo todas as extensoes inválidas setadas no XML
	 */
	public function getExtensoesInvalidas(){
		$arrExtensoes = array();
		for($i=0;$i<count($this->fileConfig->ext_arquivos_invalidos->extensao);$i++){
			array_push($arrExtensoes,(string)$this->fileConfig->ext_arquivos_invalidos->extensao[$i]);
		}
		return $arrExtensoes;
	}
	/**
	 * Busca do tamanho máximo do arquivo para upload
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return String o tamanho máximo setado no arquivo XMLL
	 */
	public function getTamArquivo(){
		return (string)$this->fileConfig->tamanho_arquivo_maximo;
	}
	/**
	 * Busca da pasta de destino do upload
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return String pasta de destino
	 */
	public function getPastaDestino(){
		return $this->strPastaDestino;
	}
	/**
	 * Seta a pasta de destino do upload
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return String pasta de destino
	 */
	public function setPastaDestino($strPastaDestino){
		$this->strPastaDestino = $strPastaDestino;
	}
	/**
	 * Recebe as configurações e envia o arquivo apra a pasta de destino
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @return Boolean se bem ou não sucedido
	 */
	public function uploadArquivo($input,$nomeImg=null,$nomeOriginal=false){
		$this->setExtArquivo($this->getExtensoes());//extensao de aqruivos suportados
		$this->setArquivosInvalidos($this->getExtensoesInvalidas());
		$this->setMaxFileSize($this->getTamArquivo());
		if(!$nomeOriginal && $nomeImg==null){
			($nomeImg!=null)?$nome=date("dmY")."_".$nomeImg:$nome=date("dmY_his");
		}elseif(!$nomeOriginal && $nomeImg!=null){
			$nome=$nomeImg;
		}else{
			$arrNome=explode('.',$_FILES[$input]['name']);
			$nome=$arrNome[0];
		}
		return $this->uploadFile($input, $this->getPastaDestino(),$nome);
	}
	public function getErrosUp(){
		return $this->getErros();
	}
	/**
	 * Método para tratamento da imagem enviada e redimensionamento da mesma
	 *
	 * @author André Coura
	 * @since 25/07/2007
	 */
	public function geraImgXY($strImgOriginal,$strNewImg,$strTipoImg,$intTamX=null,$intTamY=null){
		list($largOriginal, $altOriginal) = getimagesize($strImgOriginal);
		$intNewTamX = $largOriginal;$intNewTamY=$altOriginal;
		if($intTamX)
			$porcentagemX = ((int)$intTamX*100)/$largOriginal;
		if($intTamY)
		 $porcentagemY = ($intTamY*100)/$altOriginal;

		if($intTamX){
			$intNewTamX = (int)$intTamX;
		}elseif(!$intTamX && $intTamY!=null){
			$intNewTamX = ((int)$largOriginal*$porcentagemY)/100;
		}else{
			$intNewTamX =$largOriginal;
		}

		if($intTamY){
			$intNewTamY = (int)$intTamY;
		}elseif(!$intTamY && $intTamX!=null){
			$intNewTamY = $altOriginal*$porcentagemX/100;
		}else{
			$intNewTamY = $altOriginal;
		}

		$image_p = imagecreatetruecolor($intNewTamX, $intNewTamY);

		switch($strTipoImg){
			case 'image/jpeg':
				$image = imagecreatefromjpeg($strImgOriginal);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $intNewTamX, $intNewTamY, $largOriginal, $altOriginal);
				imagejpeg($image_p, $strNewImg, 100);
			break;
			case 'image/png':
				$image = imagecreatefrompng($strImgOriginal);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $intNewTamX, $intNewTamY, $largOriginal, $altOriginal);
				imagepng($image_p, $strNewImg, 100);
			break;
			case 'image/gif':
				$image = imagecreatefromgif($strImgOriginal);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $intNewTamX, $intNewTamY, $largOriginal, $altOriginal);
				imagegif($image_p, $strNewImg, 100);
			break;
		}

	}

}
?>