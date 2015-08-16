<?php
require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_EXCEPTION."XMLException.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

class ConteudoAreas {

	private $objCtrlXml;

    public function __construct() {

    }

    private function getObjXml(){
		if($this->objCtrlXml == null)
			$this->objCtrlXml = new ControlXML();
		return $this->objCtrlXml;
    }

    public function getConteudoArea($strXmlArea, $strIdioma){
		try{
			if(!file_exists($strXmlArea)){
				return;
			}
			$objXml = self::getObjXml()->getXML($strXmlArea);
			if(count($objXml->conteudo) == 0)
				return null;
			foreach ($objXml->conteudo as $conteudo) {
				if((string)$conteudo->attributes()->ativo == "S"){
					if((string)$conteudo->attributes()->idioma == $strIdioma)
						return utf8_decode($conteudo);
				}
			}
			return null;
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

    public function setConteudoArea($post,$strXmlArea){
		try{
			//busco o objeto xml
			if(!file_exists($strXmlArea)){
				$objXml = self::getObjXml()->getXML(FWK_CONTEUDOS);
			}else{
				$objXml = self::getObjXml()->getXML($strXmlArea);
			}
			if(!$objXml)
				throw new XMLException("O XML não esta de acordo para a edição de conteúdos.");
			$versao = 0;
			
			//defino que os conteudos anteriores ativos não mais serão ativos.
			foreach ($objXml->conteudo as $conteudo) {
				if((string)$conteudo->attributes()->idioma == $post["idioma"]){
					if((string)$conteudo->attributes()->ativo == "S")
							$conteudo->attributes()->ativo = "N";
					$versao = (int)$conteudo->attributes()->versao;
				}
			}
			$strNovoConteudo = str_replace('&#13;', "", $post["conteudo_area"]);
			
			foreach ($objXml->conteudo as $conteudo) {
				if((string)$conteudo->attributes()->idioma == $post["idioma"]){
					//&#13;
					if($conteudo == $strNovoConteudo){
						$conteudo->attributes()->ativo == "S";
						return;
					}
				}
			}
		
			
			$strNovoConteudo = htmlspecialchars($strNovoConteudo);
			$strNovoConteudo = str_replace('&#13;', "", $strNovoConteudo);
			
			$novoConteudo = $objXml->addChild("conteudo","<![CDATA[".$strNovoConteudo."]]>");
			$novoConteudo->addAttribute("timestamp",time());
			$novoConteudo->addAttribute("autor",self::getUsuarioSessao()->getIdUsuario());
			$novoConteudo->addAttribute("versao",++$versao);
			$novoConteudo->addAttribute("ativo","S");
			$novoConteudo->addAttribute("idioma",$post["idioma"]);
			//salvo as alterações feitas no xml
			
			//print("<pre>");
			
			
			//print_r($objXml);
			

			self::getObjXml()->salvaXml($strXmlArea, $objXml->asXML());
			
			
			
			//die("chegueiaqui");
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }


    private function getUsuarioSessao(){
    	$this->objCtrlSessao = new ControlSessao();
		return $this->objCtrlSessao->getObjSessao(SESSAO_FWK_DRE);
    }
}
?>