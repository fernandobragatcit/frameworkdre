<?php
require_once(FWK_EXCEPTION."FormException.class.php");
require_once(FWK_UTIL."FormataLink.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");
require_once(FWK_CONTROL."ControlSmarty.class.php");

class Form{

	private $objXML;
	private $objSmarty;
	private $strAction;
	private $strCaminhoTpls = "";
	private $tplForm;
	private $tplEstruturaForm;
	private $nomeForm;
	private $tipoForm = "basico";

	public function __construct(&$objXml,&$objSmarty){
		$this->objXML = $objXml;
		self::setObjSmartyForms($objSmarty);
	}

	public function setObjSmartyForms($objSmarty){
		$this->objSmarty = $objSmarty;
	}

	public function getObjSmarty(){
		if($this->objSmarty == null){
			$this->objSmarty = ControlSmarty::getSmarty();
		}
		return $this->objSmarty;
	}

	public function getTipoForm(){
		return $this->tipoForm;
	}

	public function setTipoForm($strTipoForm){
		$this->tipoForm = $strTipoForm;
	}

	protected function getObjCrypt(){
		if($this->objCrypt == null)
			$this->objCrypt = new Cryptografia();
		return $this->objCrypt;
	}

	public function setActionForm($strAction){
		$strCategoria = ((string)$this->objXML->attributes()->categoria!=""?(string)$this->objXML->attributes()->categoria."&f=":"");
		$this->strAction = "?".FormataLink::definiTipoLink((string)$this->objXML->attributes()->tipo)."=".self::getObjCrypt()->cryptData($strCategoria.$strAction);
	}

	public function setCaminhoTpls($strCaminho){
		$this->strCaminhoTpls = $strCaminho;
	}

	public function getCaminhoTplForm(){
		if(isset($this->objXML->attributes()->tipo) ){
			switch((string)$this->objXML->attributes()->tipo){
				case "FWK":
					$this->strCaminhoTpls = FWK_HTML_CRUDS;
					break;
				case "FWK_VIEW":
					$this->strCaminhoTpls = FWK_HTML_VIEWS;
					break;
				case "MODULO":
					$strCategoria = (string)$this->objXML->attributes()->categoria;
					if($strCategoria == null)
						throw new FormException("É necessário passar a categoria do módulo via XML.");
					$this->strCaminhoTpls = PASTA_MODULOS.$strCategoria."/".CAMINHO_TPLS_MODULOS;
					break;
			}
		}
		return $this->strCaminhoTpls;
	}

	/**
	 * Método que busca o tpl de construção do forumlário
	 */
	private function getTplForm(){
		if($this->tplForm == null){
			$this->tplForm = (string)$this->objXML->attributes()->tpl;
			if(is_file(self::getCaminhoTplForm().$this->tplForm))
				return  self::getCaminhoTplForm().$this->tplForm;
			if(is_file(self::getObjSmarty()->template_dir.$this->tplForm))
				return  self::getObjSmarty()->template_dir.$this->tplForm;
			throw new FormException(ERRO_TPL_XML_FORM_INEX. "<br /> Arquivo: ".self::getCaminhoTplForm().$this->tplForm);
		}else
			return $this->tplForm;
	}

	public function setTplForm($strTplForm){
		$this->tplForm = $strTplForm;
	}

	private function getTagForm(){
		$tagForm = (string)$this->objXML->attributes()->tag;
		if($tagForm=="" || !$tagForm)
			$tagForm = "CORPO";
		return  $tagForm;
	}

	private function getMethodForm(){
		$methodForm = $this->objXML->attributes()->method;
		if($methodForm=="" || !$methodForm)
			$methodForm = "post";
		return  (string)$methodForm;
	}

	private function getActionForm(){
		if(!$this->strAction || !isset($this->strAction) || $this->strAction == ""){
			if($this->objXML->attributes()->action=="" || !$this->objXML->attributes()->action)
				throw new FormException(ERRO_ACTION_XML_FORM);
			$this->strAction = (string)$this->objXML->attributes()->action;
		}
		$this->strAction .= self::getAtributosAction();
		return  $this->strAction;
	}

	private function getAtributosAction(){
		$strAddCation = "";
		if(isset($this->objXML->attributes()->paramAction1) && $this->objXML->attributes()->paramAction1!="")
			$strAddCation .= "&amp;atrib1=".self::getObjCrypt()->cryptData((string)$this->objXML->attributes()->paramAction1);
		if(isset($this->objXML->attributes()->paramAction2) && $this->objXML->attributes()->paramAction2!="")
			$strAddCation .= "&amp;atrib2=".self::getObjCrypt()->cryptData((string)$this->objXML->attributes()->paramAction2);
		if(isset($this->objXML->attributes()->paramAction3) && $this->objXML->attributes()->paramAction3!="")
			$strAddCation .= "&amp;atrib3=".self::getObjCrypt()->cryptData((string)$this->objXML->attributes()->paramAction3);
		if(isset($this->objXML->attributes()->tipo) && $this->objXML->attributes()->tipo!="")
			$strAddCation .= "&amp;tipo=".self::getObjCrypt()->cryptData((string)$this->objXML->attributes()->tipo);
		return $strAddCation;
	}

	private function getCssForm(){
		$classForm = (string)$this->objXML->attributes()->class;
		return  $classForm?"class=\"".$classForm."\"":"";
	}

	private function getJsForm(){
		$jsForm = (string)$this->objXML->attributes()->js;
		return  $jsForm;
	}

	private function getSubmitForm(){
		$submitForm = $this->objXML->attributes()->submit;
		return  (string)$submitForm;
	}

	private function getNomeForm(){
		if(!isset($this->nomeForm) && $this->nomeForm == ""){
			$this->nomeForm = $this->objXML->attributes()->nome;
			if($this->nomeForm == "" || !$this->nomeForm)
				throw new FormException(ERRO_NOME_XML_FORM);
		}
		return  (string)$this->nomeForm;
	}

	public function setNomeForm($strNomeForm){
		$this->nomeForm = $strNomeForm;
	}

	public function getEstruturaForm(){
		try{
			self::getObjSmarty()->assign("ACTION_FORM",self::getActionForm());
			self::getObjSmarty()->assign("TITULO_FORMS",self::getNomeForm());
			self::getObjSmarty()->assign("SUBMIT_FORM",self::getSubmitForm());
			self::getObjSmarty()->assign("CSS_FORM",self::getCssForm());
			self::getObjSmarty()->assign("JS_FORM",self::getJsForm());
			self::getObjSmarty()->assign("METHOD",self::getMethodForm());
			self::getObjSmarty()->assign("ID_FORM",self::getIdForm());
			self::getObjSmarty()->assign("ONSUBMIT_FORM",self::regOnSubmit());
			self::regTagsForm();
		}catch(FormException $erro){
			die($erro->__toString());
		}
	}

	public function regTagsForm(){
		$strEstFields = self::getObjSmarty()->fetch(self::getTplForm());
		self::getObjSmarty()->assign("ESTRUTURA_FIELDS_FORMS",$strEstFields);
		$strEstForm = self::getObjSmarty()->fetch(self::getTplEstruturaForm());
		self::getObjSmarty()->assign(self::getTagForm(),$strEstForm);
	}

	public function getTplEstruturaForm(){
		if(!isset($this->tplEstruturaForm) && $this->tplEstruturaForm == ""){
			switch(self::getTipoForm()){
				case "basico":
					$this->tplEstruturaForm = self::getCtrlConfiguracoes()->getCustomForms(null,"estruturaForms",DEPOSITO_TPLS);
					if(!isset($this->tplEstruturaForm) && $this->tplEstruturaForm == "")
						$this->tplEstruturaForm = FWK_HTML_FORMS."estruturaForm.tpl";
					break;
				case "inventario":
					$this->tplEstruturaForm = self::getCtrlConfiguracoes()->getCustomForms(null,"estruturaFormsInv",DEPOSITO_TPLS);
					if(!isset($this->tplEstruturaForm) && $this->tplEstruturaForm == "")
						$this->tplEstruturaForm = FWK_HTML_FORMS."estruturaFormInv.tpl";
					break;
				case "formularios":
					$this->tplEstruturaForm = self::getCtrlConfiguracoes()->getCustomForms(null,"estruturaModuoloForms",DEPOSITO_TPLS);
					if(!isset($this->tplEstruturaForm) && $this->tplEstruturaForm == "")
						$this->tplEstruturaForm = FWK_HTML_FORMS."estruturaModuloForms.tpl";
					break;
				default:
					$this->tplEstruturaForm = self::getCtrlConfiguracoes()->getCustomForms(null,"estruturaForms",DEPOSITO_TPLS);
					if(!isset($this->tplEstruturaForm) && $this->tplEstruturaForm == "")
						$this->tplEstruturaForm = FWK_HTML_FORMS."estruturaForm.tpl";
					break;
			}
		}

		return $this->tplEstruturaForm;
	}

	public function setTplEstruturaForm($strTplEstForm){
		$this->tplEstruturaForm = $strTplEstForm;
	}

	private function getIdForm(){
		$idForm = $this->objXML->attributes()->id;
		if($idForm=="" || !$idForm)
			return self::getNomeForm();
		return  (string)$idForm;
	}

	private function regOnSubmit(){
		$onsubmit = $this->objXML->attributes()->onsubmit;
		if($onsubmit=="" || !$onsubmit)
			return "onsubmit=\"return validaForms(this);\"";
		return  "onsubmit=\"".(string)$onsubmit.";\"";
	}

	private function getCtrlConfiguracoes() {
		if ($this->objCtrlConfiguracoes == null)
			$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}
}
?>