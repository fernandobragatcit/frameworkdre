<?php
require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_EXCEPTION."XMLException.class.php");
require_once(FWK_EXCEPTION."ConfigException.class.php");
require_once (FWK_CONTROL."ControlCSS.class.php");
require_once (FWK_CONTROL."ControlJS.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

class ControlConfiguracoes {

	private $objCtrlXml;
	private $strCaminhoXml;
	private $objXML;
	private $objSmarty;
	private $objCtrlJs;
	private $objCtrlCss;
	private $objCtrlSessao;
	private $strAssignMenu;
	private $arrCtrlCss = array();
	private $arrCtrlJs = array();

	public function __construct() {
    }

    protected function getUsuarioSessao(){

		return self::getObjSessao()->getObjSessao(SESSAO_FWK_DRE);
    }

    protected function getObjSessao(){
    	if($this->objCtrlSessao == null)
    		$this->objCtrlSessao = new ControlSessao();
    	return $this->objCtrlSessao;
    }



	public function getObjCtrlCss() {
		if ($this->objCtrlCss == null)
			$this->objCtrlCss = ControlCSS::getCSS();
		return $this->objCtrlCss;
	}

	public function getObjCtrlJs() {
		if ($this->objCtrlJs == null)
			$this->objCtrlJs = ControlJS::getJS();
		return $this->objCtrlJs;
	}

	public function getObjSmarty() {
		if ($this->objSmarty == null)
			$this->objSmarty = ControlSmarty::getSmarty();
		return $this->objSmarty;
	}

	/**
	 * Método de inicialização da classe de controle dos xmls de configuração
	 *
	 * @author Andre
	 * @since 1.0 - 11/04/2010
	 */
    private function getObjCtrlXml(){
		if($this->objCtrlXml == null)
			$this->objCtrlXml = new ControlXML();
		return $this->objCtrlXml;
    }

    private function getDadosXmlPasta($strXml = null){
    	$camXml = ($strXml!=null)?$strXml:self::getConfigFile();
		try{
			return self::getObjCtrlXml()->getXML($camXml);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

    public function setConfigFile($strCaminhoXml){
		$this->strCaminhoXml = $strCaminhoXml;
    }

    public function getConfigFile(){
		if($this->strCaminhoXml == null)
			$this->strCaminhoXml = CONFIG_FILE;
		return $this->strCaminhoXml;
    }

    private function verificaXmlConfigs(){
		try{
			//configura título página
			self::getTituloPag();
			//configura os Css das áreas
			self::getCssArea();
			//configura os Js das áreas
			self::getJsArea();
		}catch(XMLException $e){
			throw new XMLException($e);
		}
	}

	private function verificaXmlsConteudo($isCont){
		try{
			self::trataDadosAreas(XML_CABECALHO,"CABECALHO");
			self::trataDadosAreas(XML_SUB_CABECALHO,"SUB_CABECALHO");
			self::trataDadosAreas(XML_LAT_DIREITA,"LAT_DIREITA");
			self::trataDadosAreas(XML_LAT_ESQUERDA,"LAT_ESQUERDA");
			self::trataDadosAreas(XML_RODAPE,"RODAPE");
			if(!$isCont)
				self::trataDadosContCentral(XML_CONT_CENTRO,"CORPO");
		}catch(XMLException $e){
			throw new XMLException($e);
		}catch(ConfigException $e){
			throw new ConfigException($e);
		}
	}

	private function trataDadosAreas($xmlTratar,$writeHere, $setIdioma = true){
		try{
			$objXmlCab = self::getXmlConteudos($xmlTratar);
			$strConteudo = "";
			if($objXmlCab == null)
				return;
			if(count($objXmlCab->conteudo) == 0)
				return;
			foreach ($objXmlCab->conteudo as $conteudo) {
				if((string)$conteudo->attributes()->ativo == "S"){
					if(($setIdioma) && ((string)$conteudo->attributes()->idioma == self::getIdiomaUsuario()))
						$strConteudo = self::getObjSmarty()->fetch("text:$conteudo");
					else
						$strConteudo = self::getObjSmarty()->fetch("text:$conteudo");
				}
			}
			if($strConteudo == "" && $setIdioma == true)// caso não tenha o conteudo no idioma pedido, ignora-o
				self::trataDadosAreas($xmlTratar,$writeHere,false);
			self::getObjSmarty()->assign($writeHere,trim((string)$strConteudo));
		}catch(XMLException $e){
			throw new XMLException($e);
		}
	}

	private function getIdiomaUsuario(){
		$strIdioma =  self::getUsuarioSessao()->getIdioma();
		if(!isset($strIdioma) || $strIdioma == "")
			return "pt_br";
		return $strIdioma;
	}

	public function getXmlConteudos($caminhoXml, $teste=0){
		if($teste == 25)
			return null;
			//throw new ConfigException("Não foi possível encontrar o xml: ".$caminhoXml." depois de ".$teste." tentativas."); //evita o loop infinito caso exista
		//configura título página
		if(!file_exists($caminhoXml) && !is_file($caminhoXml)){
				return self::getXmlConteudos("../".$caminhoXml,++$teste);
		}else{
			try{
				return self::getObjCtrlXml()->getXML($caminhoXml);
			}catch(XMLException $e){
				throw new XMLException($e);
			}
		}
		return self::getXmlCabecalho("../".$caminhoXml,++$teste);
	}

	public function trataDadosContCentral($caminhoXml,$writeHere){
		if(!file_exists($caminhoXml) && !is_file($caminhoXml)){
				return null;
		}else{
			try{
				$objXmlCont = self::getObjCtrlXml()->getXML($caminhoXml);
				if(count($objXmlCont->conteudo) == 0)
				return;
				foreach ($objXmlCont->conteudo as $conteudo) {
					if((string)$conteudo->attributes()->ativo == "S"){
						if((string)$conteudo->attributes()->idioma == self::getIdiomaUsuario()){
							$strConteudo = self::getObjSmarty()->fetch("text:$conteudo");
							self::getObjSmarty()->assign($writeHere,trim((string)$strConteudo));
						}
						return;
					}
				}
			}catch(XMLException $e){
				throw new XMLException($e);
			}
		}
	}


	public function getTituloPag($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->titulo) && $dadosPortal->portal->titulo == ""){
				return self::getTituloPag("../".$caminhoXml);
			}else{
				$this->getObjSmarty()->assign("TITULO_PAG", trim((string)$dadosPortal->portal->titulo));
				return;
			}
		}
		return self::getTituloPag("../".$caminhoXml);
	}

	public function getStrTituloArea($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->titulo) && $dadosPortal->portal->titulo == ""){
				return self::getStrTituloArea("../".$caminhoXml);
			}else{
				return (string)$dadosPortal->portal->titulo;
			}
		}
		return self::getStrTituloArea("../".$caminhoXml);
	}
	
	public function getStrTituloPortal($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->tituloPortal) && $dadosPortal->portal->tituloPortal == ""){
				return self::getStrTituloPortal("../".$caminhoXml);
			}else{
				return (string)$dadosPortal->portal->tituloPortal;
			}
		}
		return self::getStrTituloPortal("../".$caminhoXml);
	}
	
	public function getStrEmailPortal($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->emailPortal) && $dadosPortal->portal->emailPortal == ""){
				return self::getStrEmailPortal("../".$caminhoXml);
			}else{
				return (string)$dadosPortal->portal->emailPortal;
			}
		}
		return self::getStrEmailPortal("../".$caminhoXml);
	}

	public function setStrTituloArea($strTituloArea){
		$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml)){
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			}
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->titulo) && $dadosPortal->portal->titulo == ""){
				//cria os nós e a estrutura para o título
				$novoTitulo = $dadosPortal->portal->addChild("titulo",$strTituloArea);
			}else{
				$dadosPortal->portal->titulo = $strTituloArea;
			}
		}

		self::getObjCtrlXml()->salvaXml($caminhoXml,$dadosPortal->asXML());
	}

	/**
	 * Função de configuração da URL estática do site para linkedição dos componentes do site.
	 *
	 * @author Andre
	 * @since 1.0 - 28/07/2010
	 */
	public function getUrlSite($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		//configura título página
		if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
			if(!isset($dadosPortal->portal->url) && $dadosPortal->portal->url == ""){
				return self::getUrlSite("../".$caminhoXml);
			}else{
				return (string)$dadosPortal->portal->url;
			}
		}
		return self::getUrlSite("../".$caminhoXml);
	}

	public function getCssArea($caminhoXml = null){
		if($caminhoXml == null)
			$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else{
				foreach ($this->arrCtrlCss as $delCss){
					self::getObjCtrlCss()->removeCss($delCss);
				}
				self::getObjCtrlCss()->escreveCss();
				return;
			}
		}
		if(isset($dadosPortal->portal->stilos)){
			if(!isset($dadosPortal->portal->stilos) && count($dadosPortal->portal->stilos) == 0){
				return self::getCssArea("../".$caminhoXml);
			}else{
				//configura css pagina -> herda as configurações da pasta anterior
				if(isset($dadosPortal->portal->stilos) && $dadosPortal->portal->stilos != null){
					if(count($dadosPortal->portal->stilos->css) != 0){
						foreach ($dadosPortal->portal->stilos->css as $css) {
							if($css->attributes()->ativo == "S")
								self::getObjCtrlCss()->setStrCss(URL_DEP_CSS.$css);
							else if($css->attributes()->ativo == "N")
								$this->arrCtrlCss[] = URL_DEP_CSS.$css;
						}
					}
				}
			}
		}
		return self::getCssArea("../".$caminhoXml);
	}

	public function getJsArea($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml)){
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			}else{
				foreach ($this->arrCtrlJs as $delJs){
					self::getObjCtrlJs()->removeJs($delJs);
				}
				self::getObjCtrlJs()->escreveJs();
				return;
			}
		}
		if(isset($dadosPortal->portal->javascripts)){
			if(!isset($dadosPortal->portal->javascripts) && count($dadosPortal->portal->javascripts) == 0){
				return self::getJsArea("../".$caminhoXml);
			}else{
				if(isset($dadosPortal->portal->javascripts) && $dadosPortal->portal->javascripts != null){
					if(count($dadosPortal->portal->javascripts->js) != 0){
						foreach ($dadosPortal->portal->javascripts->js as $strJs) {
							if($strJs->attributes()->ativo == "S"){
								self::getObjCtrlJs()->setStrJs(URL_DEP_JS.$strJs, false);
							}
							else if($strJs->attributes()->ativo == "N")
								$this->arrCtrlJs[] = URL_DEP_JS.$strJs;
						}
					}
				}
			}
		}
		return self::getJsArea("../".$caminhoXml);
	}

	public function getAllCss4NivelInferior($caminhoXml = null, $arrCssXmls = array()){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return $arrCssXmls;
		}
		if(isset($dadosPortal->portal->stilos)){
			if(!isset($dadosPortal->portal->stilos) && count($dadosPortal->portal->stilos) == 0){
				return self::getAllCss4NivelInferior("../".$caminhoXml,$arrCssXmls);
			}else{
				//configura css pagina -> herda as configurações da pasta anterior
				if(isset($dadosPortal->portal->stilos) && $dadosPortal->portal->stilos != null){
					if(count($dadosPortal->portal->stilos->css) != 0){
						foreach ($dadosPortal->portal->stilos->css as $css) {
								$arrCssXmls[] = $css;
						}
					}
				}
			}
		}
		return self::getAllCss4NivelInferior("../".$caminhoXml,$arrCssXmls);
	}

	/**
	 * Método para buscar no xml de configuração da determinada pasta o wireframe para estruturar a tela.
	 * Esse método é chamado e tratado na classe ControlFactory, método getWireFrame()
	 *
	 * @author Andre
	 * @since 1.0 - 17/07/2010
	 */
	public function getWireframePag($caminhoXml = null){
		if($caminhoXml == null)
				$caminhoXml = self::getConfigFile();
		if($caminhoXml != null){
			if(is_file($caminhoXml))
				$dadosPortal = self::getDadosXmlPasta($caminhoXml);
			else
				return;
		}
		if(isset($dadosPortal->conteudo) && $dadosPortal->conteudo !=""){
			if(!isset($dadosPortal->conteudo->wireframe) || $dadosPortal->conteudo->wireframe == ""){
				return self::getWireframePag("../".$caminhoXml);
			}else{
				return FWK_HTML_AREAS.(string)$dadosPortal->conteudo->wireframe;
			}
		}
		return self::getWireframePag("../".$caminhoXml);
	}

	/**
	 * Método para customização da barra do menu.
	 * Esse método é chamado na classe Main, método getTplEstruturaMenu()
	 *
	 * @author Andre
	 * @since 2.0 - 17/07/2010
	 */
	public function getMenuByXml($caminhoXml = null){
		try{
			if($caminhoXml == null)
					$caminhoXml = self::getConfigFile();
			if($caminhoXml != null){
				if(is_file($caminhoXml)){
					$dadosPortal = self::getDadosXmlPasta($caminhoXml);
				}else{
					return null;
				}
			}
			if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
				if(!isset($dadosPortal->portal->menu) && $dadosPortal->portal->menu == ""){
					return self::getMenuByXml("../".$caminhoXml);
				}else{
					if($dadosPortal->portal->menu->tpl->attributes()->ativo == "S"){
						self::setAssignMenu((string)$dadosPortal->portal->menu->tpl->attributes()->assinatura);
						return (string)$dadosPortal->portal->menu->tpl;
					}
				}
			}
			return self::getMenuByXml("../".$caminhoXml);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
	}

	/**
	 * Método para alterar, se necessário, o local onde é escrito o menu na tela
	 * Esse método é chamado na classe Main método getAssinaturaMenu()
	 */
	public function getAssignMenu(){
		if($this->strAssignMenu == null)
			self::getMenuByXml();
		return $this->strAssignMenu;
	}

	public function setAssignMenu($strAssign){
		$this->strAssignMenu = $strAssign;
	}

	/**
	 * Método público para chamar as configurações da área
	 * Método chamado na classe ControlFactory, método buildMotor()
	 *
	 * @author Andre
	 * @since 1.0 - 18/03/2010
	 */
    public function registraConfigs(){
		try{
			self::verificaXmlConfigs();
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }

    public function registraConteudos($isCont=false){
		try{
			self::verificaXmlsConteudo($isCont);
		}catch(XMLException $e){
			throw new XMLException($e);
		}catch(ConfigException $e){			throw new ConfigException($e);
		}
    }

	/**
	 * Busca os recursos para a customização das telas de cadastro de usuario
	 * Classe que utiliza: framework/classes/view/ViewCadUsuarios.class.php
	 *
	 * @author Andre
	 * @since 1.0 - 09/08/2010
	 */
    public function getCustomCadUsuarios($caminhoXml = null, $situacao, $caminhoPasta = DEPOSITO_TPLS){
		try{
			if($caminhoXml == null)
					$caminhoXml = self::getConfigFile();
			if($caminhoXml != null){
				if(is_file($caminhoXml))
					$dadosPortal = self::getDadosXmlPasta($caminhoXml);
				else
					return null;
			}
			if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
				if(!isset($dadosPortal->portal->customizacao) && $dadosPortal->portal->customizacao == ""){
					return self::getCustomCadUsuarios("../".$caminhoXml, $situacao,$caminhoPasta);
				}else{
					if(isset($dadosPortal->portal->customizacao->$situacao) && $dadosPortal->portal->customizacao->$situacao != ""){
						if($dadosPortal->portal->customizacao->$situacao->attributes()->ativo == "S"){
							$nomeElemento = strip_tags((string)$dadosPortal->portal->customizacao->$situacao, '<(.*?)>');
							return $caminhoPasta.trim($nomeElemento);
						}else{
							return null;
						}
					}
				}
			}
			return self::getCustomCadUsuarios("../".$caminhoXml, $situacao,$caminhoPasta);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }


    public function getCustomForms($caminhoXml = null, $situacao, $caminhoPasta = DEPOSITO_TPLS){
		try{
			if($caminhoXml == null)
					$caminhoXml = self::getConfigFile();
			if($caminhoXml != null){
				if(is_file($caminhoXml))
					$dadosPortal = self::getDadosXmlPasta($caminhoXml);
				else
					return null;
			}
			if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
				if(!isset($dadosPortal->portal->customizacao) && $dadosPortal->portal->customizacao == ""){
					return self::getCustomForms("../".$caminhoXml, $situacao,$caminhoPasta);
				}else{
					if(isset($dadosPortal->portal->customizacao->$situacao) && $dadosPortal->portal->customizacao->$situacao != ""){
						if($dadosPortal->portal->customizacao->$situacao->attributes()->ativo == "S"){
							$nomeElemento = strip_tags((string)$dadosPortal->portal->customizacao->$situacao, '<(.*?)>');
							return $caminhoPasta.trim($nomeElemento);
						}else{
							return null;
						}
					}
				}
			}
			return self::getCustomCadUsuarios("../".$caminhoXml, $situacao,$caminhoPasta);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }


    public function getCustomTplSis($caminhoXml = null, $situacao, $caminhoPasta = DEPOSITO_TPLS){
		try{
			if($caminhoXml == null)
					$caminhoXml = self::getConfigFile();
			if($caminhoXml != null){
				if(is_file($caminhoXml))
					$dadosPortal = self::getDadosXmlPasta($caminhoXml);
				else
					return null;
			}
			if(isset($dadosPortal->portal) && $dadosPortal->portal !=""){
				if(!isset($dadosPortal->portal->customizacao) && $dadosPortal->portal->customizacao == ""){
					return self::getCustomTplSis("../".$caminhoXml, $situacao,$caminhoPasta);
				}else{
					if(isset($dadosPortal->portal->customizacao->$situacao) && $dadosPortal->portal->customizacao->$situacao != ""){
						if($dadosPortal->portal->customizacao->$situacao->attributes()->ativo == "S"){
							$nomeElemento = strip_tags((string)$dadosPortal->portal->customizacao->$situacao, '<(.*?)>');
							return $caminhoPasta.trim($nomeElemento);
						}else{
							return null;
						}
					}
				}
			}
			return self::getCustomTplSis("../".$caminhoXml, $situacao,$caminhoPasta);
		}catch(XMLException $e){
			throw new XMLException($e);
		}
    }
}
?>