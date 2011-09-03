<?php
include_once (BIB_FPDF);
require_once (FWK_CONTROL."ControlXML.class.php");
require_once (FWK_UTIL."Cryptografia.class.php");

require_once (FWK_EXCEPTION."ReportException.class.php");

class ControlReports extends FPDF{

	private $objCtrlXml;
	private $objCtrlFpdf;
	private $objCript;
	private $strXml;
	private $idDocumento;

	public function debug($array){
		print("<pre>");
		print_r($array);
		die("</pre>");
	}
	
	public function getStrXml(){
		
		if($this->strXml == null || $this->strXml =="")
			throw new ReportException("Não foi passado o XML para a criação do Report.");
		if(!file_exists($this->strXml))
			throw new ReportException("O Arquivo XML: ".$this->strXml." não existe ou possui outro nome.");
		return $this->strXml;
	}
	
	public function setStrXml($xml){
		$xml = self::getObjCript()->decryptData($xml);
		if($xml == null || $xml =="")
			throw new ReportException("Não foi passado o XML para a criação do Report.");
		if(!file_exists($xml))
			throw new ReportException("O Arquivo XML: ".$xml." não existe ou possui outro nome.");
		$this->strXml = $xml;
	}
	
	public function getIdDocumento(){
		return $this->idDocumento; 
	}
	
	public function setIdDocumento($intId){
		$this->idDocumento = $intId;
	}

	public function getObjCtrlXml($strXml){
		if($strXml == null || $strXml == "")
			throw new ReportException("Não foi passado o XML para a criação do Report.");
		if($this->objCtrlXml == null){
			$this->objCtrlXml = ControlXML($strXml);
		}
		return $this->objCtrlXml;
	}
	
	public function getObjCript(){
		if($this->objCript == null)
			$this->objCript = new Cryptografia();
		return $this->objCript;
	}

	function Header(){
		
		self::AddImagem(DEPOSITO_IMGS."logo_relatorios.png",5,5,20);
		//Arial bold 15
		self::addFont("Arial","B",15);
		//Move to the right
		$this->Cell(80);
		//Title
		self::addCelula(1,10,"Title",1,0,"C");
		//Line break
		$this->Ln(20);
	}
	
	public function addFont($strNome,$strTipo="", $dblTamanho=""){
		$this->SetFont($strNome,$strTipo, $dblTamanho);
	}
	
	public function addImagem($strImg, $left, $top, $tamanho){
		$this->Image($strImg, $left, $top, $tamanho);
	}
	
	public function addCelula($largura, $top="",$strTxt="", $a="", $b="", $c=""){
		$this->Cell($largura, $top,$strTxt, $a, $b, $c);
	}

	function Footer(){
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont("Arial","I",8);
		//Page number
		$this->Cell(0,10,"Page ".$this->PageNo()."/{nb}",0,0,"C");
	}


	public function geraPdf(){
		$this->AliasNbPages();
		$this->AddPage();
		$this->SetFont("Times","",12);
		for($i=1;$i<=40;$i++)
			$this->Cell(0,10,"Número da página: ".$i,0,1);
		$this->Output();
	}





}
?>