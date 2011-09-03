<?php
require_once(FWK_EXCEPTION."XMLException.class.php");

class ControlXML{

	private $strXml;
	private $objXml;

	public  function __construct($strXML=null){
		$this->strXml = $strXML;
	}

	public function getStrXml(){
		return $this->strXml;
	}

	public function setStrXml($strXml){
		$this->strXml = $strXml;
	}

	public function getXML($strXmlCust = null){
		$strXml = (isset($strXmlCust))?$strXmlCust:self::getStrXml();
		if (!file_exists($strXml))
    		throw new XMLException(ERRO_LEITURA_XML.$strXml);
		 $objXml = @simplexml_load_file($strXml);
		 if($objXml)
		 	return $objXml;
		 throw new XMLException(ERRO_LEITURA_XML.$strXml);
	}

	/**
	 * Método para salvar o conteudo modificado do xml
	 *
	 * @author André Coura
	 * @since 10/08/08
	 */
	public function salvaXml($caminho_xml, $xml_final) {
		umask(0000);
		//Escreve o XML no diretorio especificado
		$xml_escrito = fopen($caminho_xml, "w");
		if (!fwrite($xml_escrito, str_replace('&#13;', "", htmlspecialchars_decode($xml_final))))
			return false;
		fclose($xml_escrito);
		return true;
	}

	public function getDomXml(){
		if (!file_exists($this->strXml))
    		throw new XMLException(ERRO_LEITURA_XML);
		$objDom = new DOMDocument();
		$objDom->load($this->strXml);
		return $objDom;
	}
}
?>