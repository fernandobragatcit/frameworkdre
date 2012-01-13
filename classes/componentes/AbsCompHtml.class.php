<?php
require_once(FWK_CONTROL."ControlSmarty.class.php");
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_CONTROL."ControlCSS.class.php");
require_once(FWK_CONTROL."ControlJS.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");
require_once(FWK_EXCEPTION."ElementsException.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_UTIL."FormataLink.class.php");
require_once(FWK_UTIL."Utf8Parsers.class.php");

/**
 * Classe abstrata de padronização das classes de componentes HTML
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 20/07/2008
 */

abstract class AbsCompHtml{

	protected $objXmlComp = null;
	protected $objXmlCompChilds = null;
	protected $objBanco = null;
	protected $objControlConfiguracoes = null;
	protected $objHtmlComp = null;
	protected $objSmarty = null;
	protected $strClass = null;
	protected $strValue = "";

	protected $prefixo = "";
	protected $sufixo = "";

	protected $strTipo = "";
	protected $strCategoria = "";

	protected $strNomeCampo = "";

	private $param1;
	private $param2;

	private $idReferencia;
	private $idReferencia2;
	private $idPortal;

	public function __construct($objXMLComp) {
		self::setIdPortal(self::getCtrlConfiguracoes()->getIdPortal());
		$this->objXmlComp = null;
		$this->objXmlComp = $objXMLComp->attributes();
		self::getSmarty();
		if($objXMLComp->children())
			$this->objXmlCompChilds = $objXMLComp->children();
		self::regTags();
    }

    public function setIdReferencia($intIdRef){
		$this->idReferencia = $intIdRef;
	}
	public function getIdReferencia(){
		return $this->idReferencia;
	}
    public function setIdReferencia2($intIdRef){
		$this->idReferencia2 = $intIdRef;
	}
	public function getIdReferencia2(){
		return $this->idReferencia2;
	}
    public function setIdPortal($intIdPortal){
		$this->idPortal = $intIdPortal;
	}
	public function getIdPortal(){
		return $this->idPortal;
	}

    abstract public function getComponente($value = "");

    protected function getObjXmlCompDados(){
    	if($this->objXmlComp == null)
    		$this->objXmlComp = $objXMLComp->attributes();
    	return $this->objXmlComp;
    }
    
    protected function getCtrlConfiguracoes(){
    	if($this->objControlConfiguracoes == null)
    		$this->objControlConfiguracoes = new ControlConfiguracoes();
    	return $this->objControlConfiguracoes;
    }

    protected function setValue($value){
    	$this->strValue = $value;
    }

    protected function getValue(){
    	return $this->strValue;
    }

    protected function getBanco(){
    	if($this->objBanco == null)
    		$this->objBanco = ControlDB::getBanco();
    	return $this->objBanco;
    }

	/**
	 * @deprecated
	 */
    protected function getSmarty(){
    	if($this->objSmarty == null)
    		$this->objSmarty = ControlSmarty::getSmarty();
    	return $this->objSmarty;
    }

    protected function getObjSmarty(){
    	if($this->objSmarty == null)
    		$this->objSmarty = ControlSmarty::getSmarty();
    	return $this->objSmarty;
    }

   	protected function setCampos(){
    	try{
    		self::getObjSmarty()->assign(self::getName()."_check",self::getCheck());
    		self::getObjSmarty()->assign(self::getName()."_label",self::getLabel());
    		self::getObjSmarty()->assign(self::getName()."_campo",self::getHtmlComp());
    	}catch(ElementsException $e){
    		die($e->__toString());
    	}
    }

    protected function setHtmlComp($htmlComp){
    	$this->objHtmlComp = $htmlComp;
    }

    public function getHtmlComp(){
    	return $this->objHtmlComp;
    }

    protected function regTags(){
    	self::getObjSmarty()->assign("TYPE_COMP",self::getObjXmlCompDados()->type);
    	self::getObjSmarty()->assign("TYPE_COMP_BTN","type=\"".self::getObjXmlCompDados()->type."\"");

    	switch((string)self::getObjXmlCompDados()->type){
			case "inteiro" :
			case "integer":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,soNumeros)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "cpf":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,cpf)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "cnpj":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,cnpj)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "telefone":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,telefone)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "email":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,email)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "cep":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,cep)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "hora":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,hora)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "double":
				$strUnidade = 3;
				$strDecimais = 2;
				if(isset(self::getObjXmlCompDados()->masckNum) && (string)self::getObjXmlCompDados()->masckNum !="" )
					$strUnidade = (string)self::getObjXmlCompDados()->masckNum;
				if(isset(self::getObjXmlCompDados()->masckDec) && (string)self::getObjXmlCompDados()->masckDec !="" )
					$strDecimais = (string)self::getObjXmlCompDados()->masckDec;
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascaraParams(this,double, ".$strUnidade.", ".$strDecimais.")\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "site":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,site)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "longlat":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"mascara(this,longlat)\"");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "text":
				self::getObjSmarty()->assign("ONKEYPRESS_COMP"," ");
				break;
			case "data":
				self::getObjSmarty()->assign("FILE_PATH_CALENDAR",FWK_JS."vlaCalendar.v2.1/inc/");
				self::getObjSmarty()->assign("TYPE_COMP","text");
				break;
			case "file":
				if((string)self::getObjXmlCompDados()->colocaNull == "true")
					self::getObjSmarty()->assign("COLOCA_NULL",true);
					
				if((string)self::getObjXmlCompDados()->obrigatorio == "true")
					self::getObjSmarty()->assign("OBRIGATORIO",true);
				break;
			default:
				self::getObjSmarty()->assign("ONKEYPRESS_COMP"," ");
				break;
		}

    	if(isset(self::getObjXmlCompDados()->style) && self::getObjXmlCompDados()->style!="")
    		self::getObjSmarty()->assign("STYLE_COMP","style=\"".self::getObjXmlCompDados()->style."\"");
    	else
    		self::getObjSmarty()->assign("STYLE_COMP","");

    	if(isset(self::getObjXmlCompDados()->size) && self::getObjXmlCompDados()->size!="")
    		self::getObjSmarty()->assign("SIZE_COMP","size=\"".self::getObjXmlCompDados()->size."\"");
    	else
    		self::getObjSmarty()->assign("SIZE_COMP","");

		if(isset(self::getObjXmlCompDados()->class) && self::getObjXmlCompDados()->class!="")
    		self::getObjSmarty()->assign("CLASS_COMP",self::getObjXmlCompDados()->class);
    	else
    		self::getObjSmarty()->assign("CLASS_COMP","");

    	self::getObjSmarty()->assign("NOME_COMP",self::getPrefixo().self::getName().self::getSufixo());
    	self::getObjSmarty()->assign("TITLE_COMP",self::getObjXmlCompDados()->title?self::getObjXmlCompDados()->title:self::getName());
    	self::getObjSmarty()->assign("ID_COMP",self::getPrefixo().self::getObjXmlCompDados()->id.self::getSufixo());

    	if(self::getObjXmlCompDados()->dir && self::getObjXmlCompDados()->dir!="")
    		self::getObjSmarty()->assign("DIR_COMP","lang=\"".self::getObjXmlCompDados()->dir."\"");
    	else
    		self::getObjSmarty()->assign("DIR_COMP","");

		if(self::getObjXmlCompDados()->lang && self::getObjXmlCompDados()->lang!="")
    		self::getObjSmarty()->assign("LANG_COMP","lang=\"".self::getObjXmlCompDados()->lang."\"");
    	else
    		self::getObjSmarty()->assign("LANG_COMP","");

    	if(self::getObjXmlCompDados()->tabindex && self::getObjXmlCompDados()->tabindex!="")
    		self::getObjSmarty()->assign("TABINDEX_COMP","tabindex=\"".self::getObjXmlCompDados()->tabindex."\"");
    	else
    		self::getObjSmarty()->assign("TABINDEX_COMP","");

		if(self::getObjXmlCompDados()->maxlength && self::getObjXmlCompDados()->maxlength!="")
    		self::getObjSmarty()->assign("MAXLENGTH_COMP","maxlength=\"".self::getObjXmlCompDados()->maxlength."\"");
    	else
    		self::getObjSmarty()->assign("MAXLENGTH_COMP","");

		if(isset(self::getObjXmlCompDados()->estiloCalendario))
    		self::getObjSmarty()->assign("ESTILO_CALENDARIO",self::getObjXmlCompDados()->estiloCalendario);
    	else if(self::getObjXmlCompDados()->type!="data" && !isset(self::getObjXmlCompDados()->estiloCalendario))
    		self::getObjSmarty()->assign("ESTILO_CALENDARIO","apple_widget");
    	else
    		self::getObjSmarty()->assign("ESTILO_CALENDARIO","");

		if(isset(self::getObjXmlCompDados()->onchange) && self::getObjXmlCompDados()->onchange!="")
			self::getObjSmarty()->assign("ONCHANGE_COMP","onchange=\"".(string)self::getObjXmlCompDados()->onchange."\"");
		else
			self::getObjSmarty()->assign("ONCHANGE_COMP","");

		if(isset(self::getObjXmlCompDados()->disabled) && self::getObjXmlCompDados()->disabled!="")
			self::getObjSmarty()->assign("DISABLED_COMP","disabled=\"".(string)self::getObjXmlCompDados()->disabled."\"");
		else
			self::getObjSmarty()->assign("DISABLED_COMP","");

		if(isset(self::getObjXmlCompDados()->readonly) && self::getObjXmlCompDados()->readonly!="")
			self::getObjSmarty()->assign("READONLY","readonly=\"".(string)self::getObjXmlCompDados()->readonly."\"");
		else
			self::getObjSmarty()->assign("READONLY","");


		if(self::getObjXmlCompDados()->value != "" ){
    		self::getObjSmarty()->assign("VALUE_COMP",self::getObjXmlCompDados()->value);
		}else
			self::getObjSmarty()->assign("VALUE_COMP","");

		if(self::getObjXmlCompDados()->valor !="")
			self::getObjSmarty()->assign("VALUE_COMP",self::getObjXmlCompDados()->valor);
		else if((!isset(self::getObjXmlCompDados()->valor) && self::getObjXmlCompDados()->valor=="") && (!isset(self::getObjXmlCompDados()->value) && self::getObjXmlCompDados()->value==""))
			self::getObjSmarty()->assign("VALUE_COMP","");

		if(self::getObjXmlCompDados()->onclick!= "" )
    		self::getObjSmarty()->assign("COMP_ONCLICK","onclick=\"".self::getObjXmlCompDados()->onclick."\"");
    	else
    		self::getObjSmarty()->assign("COMP_ONCLICK"," ");
    		
		if(self::getObjXmlCompDados()->onblur!= "" )
    		self::getObjSmarty()->assign("ONBLUR_COMP","onblur=\"".self::getObjXmlCompDados()->onblur."\"");
    	else
    		self::getObjSmarty()->assign("ONBLUR_COMP"," ");
    		
		if(self::getObjXmlCompDados()->onkeydown!= "" )
    		self::getObjSmarty()->assign("ONKEYDOWN_COMP","onkeydown=\"".self::getObjXmlCompDados()->onkeydown."\"");
    	else
    		self::getObjSmarty()->assign("ONKEYDOWN_COMP"," ");
    		
		if(self::getObjXmlCompDados()->onkeyup!= "" )
    		self::getObjSmarty()->assign("ONKEYUP_COMP","onkeyup=\"".self::getObjXmlCompDados()->onkeyup."\"");
    	else
    		self::getObjSmarty()->assign("ONKEYUP_COMP"," ");
    		
		if(self::getObjXmlCompDados()->onfocus!= "" )
    		self::getObjSmarty()->assign("ONFOCUS_COMP","onfocus=\"".self::getObjXmlCompDados()->onfocus."\"");
    	else
    		self::getObjSmarty()->assign("ONFOCUS_COMP"," ");
    		
		if(self::getObjXmlCompDados()->onkeypress!= "" )
    		self::getObjSmarty()->assign("ONKEYPRESS_COMP","onkeypress=\"".self::getObjXmlCompDados()->onkeypress."\"");
    	else
    		self::getObjSmarty()->assign("ONKEYPRESS_COMP"," ");

    	if(self::getObjXmlCompDados()->rows!= "" )
    		self::getObjSmarty()->assign("ROWS_COMP","rows=\"".self::getObjXmlCompDados()->rows."\"");
    	else
    		self::getObjSmarty()->assign("ROWS_COMP","rows=\"3\"");

    	if(self::getObjXmlCompDados()->cols!= "" )
    		self::getObjSmarty()->assign("COLS_COMP","cols=\"".self::getObjXmlCompDados()->cols."\"");
    	else
    		self::getObjSmarty()->assign("COLS_COMP","cols=\"30\"");

    	if(self::getObjXmlCompDados()->cssEditor!= "" )
    		self::getObjSmarty()->assign("CSS_EDITOR", self::getObjXmlCompDados()->cssEditor);
    	else
    		self::getObjSmarty()->assign("CSS_EDITOR","");
		
		if(self::getValue() != "")
    		self::getObjSmarty()->assign("VALUE_COMP",utf8_encode(self::getValue()));
    }

   public function getName(){
    	if($this->strNomeCampo == null){
	    	if(isset(self::getObjXmlCompDados()->name) && self::getObjXmlCompDados()->name!="")
	    		$this->strNomeCampo = (string)self::getObjXmlCompDados()->name;
	    	else
	    		throw new ElementsException(FORM_NAME_ERRO);
    	}
    	return $this->strNomeCampo;
    }

    /**
     * TODO:
     */
    public function setNameCampo($strNomeCampo){
		$this->strNomeCampo = $strNomeCampo;
    }

	public function getLabel(){
    	$strDado = self::getObjXmlCompDados()->label;
    	$strCampObrig = self::getObjXmlCompDados()->obrigatorio;
    	$strObrig = "";
    	if(isset($strDado) && $strDado!=""){
    		if(isset($strCampObrig) && $strCampObrig !="" && $strCampObrig == "true")
    			$strObrig = " <span class=\"campoObrigatorio\">*</span> ";
    		return (string)self::getObjXmlCompDados()->label.$strObrig;
    	}
    	if(self::getObjXmlCompDados()->type=="button" || self::getObjXmlCompDados()->type=="submit")
    		return "";
    	throw new ElementsException(FORM_LABEL_ERRO);
    }

	public function getCheck(){
    	$strDado = (string)self::getObjXmlCompDados()->name;
    	if(self::getObjXmlCompDados()->habilita == "true"){
			$campoCheck = '<input type="checkbox" class="check" name="'.$strDado.'_check" value="S" onclick="habilitaCampo(\''.$strDado.'\')"> ';
    		return $campoCheck;
    	}else
    		return "";
    	throw new ElementsException(FORM_LABEL_ERRO);
    }

    public function getClass(){
    	return $this->strClass;
    }

    public function setClass($strClass=""){
    	$this->strClass = (string)$strClass;
    }

    public function getTipo(){
		return $this->strTipo;
	}

	public function setTipo($tipo){
		$this->strTipo = $tipo;
	}

	public function getCategoria(){
		return $this->strCategoria;
	}

	public function setCategoria($cat){
		$this->strCategoria = $cat;
	}

	public function getSufixo(){
    	return $this->sufixo;
    }

    public function getPrefixo(){
    	return $this->prefixo;
    }

    public function setSufixo($strSufixo){
    	$this->sufixo = $strSufixo;
    }

    public function setPrefixo($strPrefixo){
    	$this->prefixo = $strPrefixo;
    }

    public function setParam1($param1){
		$this->param1 = $param1;
    }

    public function getParam1(){
		return $this->param1;
    }
    public function setParam2($param2){
		$this->param2 = $param2;
    }

    public function getParam2(){
		return $this->param2;
    }

 }
?>