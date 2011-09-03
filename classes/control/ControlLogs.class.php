<?php
require_once (FWK_MODEL."Logs.class.php");
require_once (FWK_MODEL."GridErro.class.php");
require_once (FWK_CONTROL."ControlXML.class.php");
require_once(FWK_EXCEPTION."XMLException.class.php");

class ControlLogs {

    private $xmlLog;
    private $strCaminhoDir;
    private $strNomeXml;
    private $objLog;

    public function __construct() {
    }

    private function getObjLog(){
    	if($this->objLog == null)
    		$this->objLog = new Logs();
    	return $this->objLog;
    }

    private function verificaPastaLog(){
    	if (!file_exists($this->strCaminhoDir))
			self::criaPastaLog();
			return $this->strCaminhoDir;
    }

    public function setNomeXml($strNomeXml = null){
    	if($strNomeXml == null)
    		$this->strNomeXml = PRE_NOME_XML.date("m_Y").".xml";
    	else
    		$this->strNomeXml = $strNomeXml;
    }

    public function getNomeXml(){
		if($this->strNomeXml == null)
			self::setNomeXml();
		return $this->strNomeXml;
    }

    public function setPastaLogs($strDir){
    	$this->strCaminhoDir = $strDir;
    }

    public function getPastaLogs(){
    	return $this->strCaminhoDir;
    }

    private function verificaLog(){
    	if(!file_exists(self::verificaPastaLog().self::getNomeXml())){
			//cria o novo arquivo de log a partir do modelo do framework
    		self::registraLog(FWK_DEFAULT);
		}else{
			self::registraLog(self::verificaPastaLog().self::getNomeXml());
		}
    }

    private function registraLog($caminhoXml){
    	try{
	    	$objCtrolXml = new ControlXML($caminhoXml);
			self::getObjLog()->setObjXML($objCtrolXml->getXML());
			self::getObjLog()->registraLog();
			$objCtrolXml->salvaXml(self::verificaPastaLog().self::getNomeXml(), self::getObjLog()->getObjXML()->asXML());
		}catch(XMLException $e){
			$objErros = new GridErro($e);
			$objErros->setSubjectMail("Registro de Logs no sistema: ". $_SERVER["HTTP_HOST"]);
			$objErros->setFromMail($_SERVER["SERVER_NAME"]);
			$objErros->setFromName("Registro de Logs");
			$objErros->setMensErro("Registro de Logs no sistema: ".$e->__toString());
			$objErros->sendMail();
		}
    }

    private function criaPastaLog(){
    	if($this->strCaminhoDir!=null)
			mkdir($this->strCaminhoDir, 0755);
    }

    public function registraLogs(){
		self::verificaLog();
    }

    public function getXmlLog(){
		return $this->xmlLog;
    }

    public function setXmlLog($xmlLog){
		$this->xmlLog = $xmlLog;
    }
}
?>