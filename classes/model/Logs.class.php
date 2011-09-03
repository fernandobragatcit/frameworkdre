<?php
require_once(FWK_EXCEPTION."XMLException.class.php");

class Logs {

	private $xmlAno;
	private $xmlMes;
	private $xmlDia;
	private $objXml;

    public function __construct() {

    }

    public function getObjXML(){
    	return $this->objXml;
    }

    public function setObjXML($objXml){
    	$this->objXml = $objXml;
    }

    public function registraLog(){
		if($this->objXml == null)
			throw new XMLException(ERRO_LEITURA_XML);
		self::setNumAccessXml();
		self::setMesXml();
		self::setDiaXml();
		self::setUsuarioXml();
    }

    private function setNumAccessXml(){
    	if($this->objXml[0]->attributes()->numTotalAcessos == "")
    		$this->objXml[0]->attributes()->numTotalAcessos = 1;
    	else{
    		if(self::verificaUsuario())
    			$this->objXml[0]->attributes()->numTotalAcessos = (int)$this->objXml[0]->attributes()->numTotalAcessos+1;
    	}
    }

    private function verificaUsuario(){
		if(!self::getUsuarioObjXml())
			return true;
		return self::atualizaTimeStampUser();
    }

    private function atualizaTimeStampUser(){
    	$retorno = false;
    	if((time() - (double)self::getUsuarioObjXml()->attributes()->timestamp) > 5400)
    		$retorno = true;
		self::getUsuarioObjXml()->attributes()->timestamp = time();
		return $retorno;
    }

    private function setMesXml(){
		if(self::getMesObjXml()){
			self::getMesObjXml()->attributes()->valor = self::getMes();
		}else{
			$novoMes = $this->objXml[0]->addChild("mes");
			$novoMes->addAttribute("valor",self::getMes());
		}
    }

    private function getMesObjXml(){
    	if(!isset($this->objXml[0]->mes))
    		return null;
    	foreach ($this->objXml[0]->mes as $mesXml)
			if((string)$mesXml->attributes()->valor == (string)self::getMes())
				return $mesXml;
			else if(isset($mesXml->attributes()->valor) && (string)$mesXml->attributes()->valor == "")
				return $mesXml;
    }

    private function setDiaXml(){
		if(self::getDiaObjXml()){
			self::getDiaObjXml()->attributes()->valor = self::getDia();
		}else{
			$novoDia = self::getMesObjXml()->addChild("dia");
			$novoDia->addAttribute("valor",self::getDia());
		}
    }

    private function getDiaObjXml(){
    	if(!self::getMesObjXml())
    		return null;
    	foreach (self::getMesObjXml()->dia as $diaXml)
    		if((string)$diaXml->attributes()->valor == (string)self::getDia())
				return $diaXml;
			else if(isset($diaXml->attributes()->valor) && (string)$diaXml->attributes()->valor == "")
				return $diaXml;
    }

    private function setUsuarioXml(){
		if(self::getUsuarioObjXml()){
			if((string)self::getUsuarioObjXml()->attributes()->ip == (string)self::getIpUsuario()){
				self::setBrowserUser(self::getUsuarioObjXml()->browsers);
				self::setPageViewsUserInXml(self::getUsuarioObjXml()->pageViews);
			}else{
				self::getUsuarioObjXml()->attributes()->ip = self::getIpUsuario();
				self::getUsuarioObjXml()->attributes()->timestamp = time();
				self::setBrowserUser(self::getUsuarioObjXml()->browsers);
				self::setPageViewsUserInXml(self::getUsuarioObjXml()->pageViews);
			}
		}else{
			$novoUsuario = self::getDiaObjXml()->addChild("usuario");
			$novoUsuario->addAttribute("ip",self::getIpUsuario());
			$novoUsuario->addAttribute("timestamp",time());
			$novoUsuario->addChild("browsers");
			self::setBrowserUser($novoUsuario->browsers);
			$novoUsuario->addChild("pageViews");
			self::setPageViewsUserInXml($novoUsuario->pageViews);
		}
    }

    private function setBrowserUser($objBrowserUser){
    	foreach ($objBrowserUser->browser as $browser)
			if((string)$browser == (string)self::getBrowserUsuario())
				return;
		if(count($objBrowserUser) > 0)
			if($objBrowserUser->browser == ""){
				$objBrowserUser->browser[0] = self::setCDATA(self::getBrowserUsuario());
				return;
			}
		$objBrowserUser->addChild("browser",self::setCDATA(self::getBrowserUsuario()));
    }

    private function getUsuarioObjXml(){
		if(!self::getDiaObjXml())
			return null;
		foreach (self::getDiaObjXml()->usuario as $usuarioXml)
    		if((string)$usuarioXml->attributes()->ip == (string)self::getIpUsuario()){
				return $usuarioXml;
    		}else if(isset($usuarioXml->attributes()->ip) && (string)$usuarioXml->attributes()->ip == ""){
				return $usuarioXml;
    		}
    	return null;
    }

    private function setPageViewsUserInXml($objPageViewXml){
		foreach ($objPageViewXml->pag as $pagView){
			if((string)$pagView == (string)self::getPagAtual()){
				return;
			}
		}
		if(!isset($objPageViewXml->pag)){
			$newPag = $objPageViewXml->addChild("pag",self::setCDATA(self::getPagAtual()));
			$newPag->addAttribute("timestamp",time());
		}else if(isset($objPageViewXml->pag->attributes()->timestamp) && $objPageViewXml->pag->attributes()->timestamp == ""){
			$objPageViewXml->pag->attributes()->timestamp = time();
			$objPageViewXml->pag =self::setCDATA(self::getPagAtual());
		}else{
			$newPag = $objPageViewXml->addChild("pag",self::setCDATA(self::getPagAtual()));
			$newPag->addAttribute("timestamp",time());
		}
    }

    public function setCDATA($strTexto){
    	return "<![CDATA[".htmlspecialchars($strTexto)."]]>";
    }

    public function getPagAtual(){
    	return $_SERVER["REQUEST_URI"];
    }

    public function getIpUsuario(){
		return $_SERVER["REMOTE_ADDR"];
    }
    public function getBrowserUsuario(){
		return $_SERVER["HTTP_USER_AGENT"];
    }

    public function getAno(){
    	if($this->xmlAno != null)
    		return $this->xmlAno;
    	return date("Y");
    }

    public function setAno($strAno){
    	$this->xmlAno = $strAno;
    }

    public function getMes(){
    	if($this->xmlMes != null)
    		return $this->xmlMes;
    	return date("m");
    }

    public function setMes($strMes){
    	$this->xmlMes = $strMes;
    }

    public function getDia(){
    	if($this->xmlDia != null)
    		return $this->xmlDia;
    	return date("d");
    }

    public function setDia($strDia){
    	$this->xmlDia = $strDia;
    }
}
?>