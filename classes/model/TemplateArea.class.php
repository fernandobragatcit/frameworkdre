<?php
require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_EXCEPTION."XMLException.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

class TemplateArea {

    private $objCtrlXml;

    public function __construct() {

    }

    private function getObjXml(){
		if($this->objCtrlXml == null)
			$this->objCtrlXml = new ControlXML();
		return $this->objCtrlXml;
    }

    public function getTemplateArea($strXmlArea=null){
		try{
			if($strXmlArea == null)
				$strXmlArea = CONFIG_FILE;
			if($strXmlArea != null){
				if(is_file($strXmlArea))
					$dadosPortal = self::getDadosXmlPasta($strXmlArea);
			}else{
				return;
			}
			//configura título página
			if(isset($dadosPortal->conteudo) && $dadosPortal->conteudo !=""){
				if(!isset($dadosPortal->conteudo->wireframe) && $dadosPortal->conteudo->wireframe == ""){
					return self::getTemplateArea("../".$strXmlArea);
				}else{
					$strWire = (string)$dadosPortal->conteudo->wireframe;
					$arrWire = explode(".",$strWire);
					return $arrWire[0];
				}
			}
			return self::getTemplateArea("../".$strXmlArea);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

    public function setTemplateArea($strXmlArea=null,$wireframe){
		try{
			if($strXmlArea == null)
				$strXmlArea = CONFIG_FILE;
			if($strXmlArea != null){
				if(is_file($strXmlArea))
					$dadosPortal = self::getDadosXmlPasta($strXmlArea);
			}else{
				return;
			}
			//configura título página
			if(!isset($dadosPortal->conteudo)){
				$dadosPortal->addChild("conteudo");
			}
			if(!isset($dadosPortal->conteudo->wireframe) && $dadosPortal->conteudo->wireframe == ""){
				//cria a estrutura onde fica salvo o wireframe
				//cria os nós e a estrutura para o título
				$novoTitulo = $dadosPortal->conteudo->addChild("wireframe",$wireframe.".tpl");

			}else{
				(string)$dadosPortal->conteudo->wireframe = $wireframe.".tpl";

			}
			self::getObjXml()->salvaXml($strXmlArea,$dadosPortal->asXML());
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

    private function getDadosXmlPasta($strXml = null){
    	$camXml = ($strXml!=null)?$strXml:CONFIG_FILE;
		try{
			return self::getObjXml()->getXML($camXml);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

}
?>