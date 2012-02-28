<?php
require_once(FWK_COMP."AbsCompHtml.class.php");

class Button extends AbsCompHtml {

	protected $strLabelBtn;
	protected $strParam1;
	protected $strParam2;

   public function getComponente($label = ""){
		self::setLabel($label);
		self::regTags();
		self::regDataBtn();
    	self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."buttonDre.tpl"));
    	self::setCampos();
    }

    public function setParam1($param1 = null){
		$this->strParam1 =$param1;
    }

    public function getParam1(){
		return $this->strParam1;
    }
    public function setParam2($param2 = null){
		$this->strParam2 =$param2;
    }
    public function setIdReferencia($idReferencia = null){
		$this->idReferencia =$idReferencia;
    }

    public function getIdReferencia(){
		return $this->idReferencia;
    }

    public function getParam2(){
		return $this->strParam2;
    }

    private function setLabel($label){
    	$this->strLabelBtn = $label;
    }

    public function assignButton($strLocation){
		$this->objSmarty->assign($strLocation,$this->objHtmlComp);
    }

    /**
     * Registra as tags do componente html
     *
     * @author André Coura
     * @since 1.0 - 13/07/2008
     */
    private function regDataBtn(){
    	try{
	    	if(isset($this->objXmlComp->imagemBtn) && $this->objXmlComp->imagemBtn!="")
	    		$this->objSmarty->assign("VALUE_COMP","<img src=\"".(string)$this->objXmlComp->imagemBtn."\" />");
	    	else if($this->strLabelBtn!="" && self::getValueBtn() == "Cadastrar")
	    		$this->objSmarty->assign("VALUE_COMP",$this->strLabelBtn);
	    	else
	    		$this->objSmarty->assign("VALUE_COMP",self::getValueBtn());
	    		
	    	if(isset($this->objXmlComp->name) && !isset($this->objXmlComp->id) ){
	    		if(!isset($this->objXmlComp->semId))
	    			$this->objSmarty->assign("ID_COMP","id=\"".$this->objXmlComp->name."\"");
	    		$this->objSmarty->assign("NAME_COMP",$this->objXmlComp->name);
	    	}else if(isset($this->objXmlComp->id) && !isset($this->objXmlComp->name) ){
	    		if(!isset($this->objXmlComp->semId))
	    			$this->objSmarty->assign("ID_COMP","id=\"".$this->objXmlComp->name."\"");
	    		$this->objSmarty->assign("NAME_COMP",$this->objXmlComp->id);
	    	}
	    	
	    	$this->objSmarty->assign("ACTION_COMP",self::getActionBtn());
	    	$this->objSmarty->assign("GOTO_COMP",self::getGoToBtn());
	    	$this->objSmarty->assign("CONFIRME_COMP",self::getConfirmeBtn());
    	}catch(ElementsException $e){
			die($e->__toString());
    	}
    }

    private function getConfirmeBtn(){
    	$this->objCrypt = new Cryptografia();
		if(isset($this->objXmlComp->confirme) && $this->objXmlComp->confirme!=""){
			$this->objSmarty->assign("GOTO_COMP","");
			$strMens = $this->objXmlComp->mens;
			if( $this->objXmlComp->confirme == "?")
				return "onclick=\"return confirmIr('?','".$strMens."')\"";
				
			$strCam = $this->objCrypt->cryptData((self::getCategoria()!=""?self::getCategoria()."&f=":"").parent::getClass()."&".(((string)$this->objXmlComp->goto != "")?(string)$this->objXmlComp->goto."&":"").(((string)$this->objXmlComp->param1 != "")?(string)$this->objXmlComp->param1."=".self::getIdReferencia()."&":""));
			return "onclick=\"return confirmIr('?".FormataLink::definiTipoLink(self::getTipo())."=".$strCam."','".$strMens."');\"";
		}
    }

    private function getGoToBtn(){
		$this->objCrypt = new Cryptografia();
		$strParam1 = "";
		if(isset($this->objXmlComp->goto) && $this->objXmlComp->goto!=""){
			if( $this->objXmlComp->goto == "?")
				return "onclick=\"return vaiPara('?');\"";


			if(isset($this->objXmlComp->param1) && $this->objXmlComp->param1 != ""){
				$strParam1 = "&".$this->objXmlComp->param1. "=".self::getParam1();
			}
//			die(self::getParam2());
			if(isset($this->objXmlComp->param2) && $this->objXmlComp->param2 != ""){
				$strParam2 = "&".$this->objXmlComp->param2. "=".self::getParam2();
			}
			$strLink = $this->objCrypt->cryptData((self::getCategoria()!=""?self::getCategoria()."&f=":"").parent::getClass()."&".(string)$this->objXmlComp->goto."".$strParam1.$strParam2);
			return "onclick=\"return vaiPara('?".FormataLink::definiTipoLink(self::getTipo())."=".$strLink."');\"";
		}
    }

    private function getActionBtn(){
		if((!$this->objXmlComp->type || $this->objXmlComp->type=="") &&
			(!$this->objXmlComp->action || $this->objXmlComp->action=="") &&
				(!$this->objXmlComp->goto || $this->objXmlComp->goto==""))
		throw new ElementsException(FORM_NOACTION);
		if(isset($this->objXmlComp->action) && $this->objXmlComp->action!="")
			return "onclick=\"".$this->objXmlComp->action."\"";
		return "";
    }

    private function getValueBtn(){
		if(!self::getAttrDefautl())
			throw new ElementsException(FORM_BUTTON_IDNAMEVALUE);
		if(!$this->objXmlComp->value || $this->objXmlComp->value == "")
			return self::getAttrDefautl();
		return (string)$this->objXmlComp->value;
    }

    private function getAttrDefautl(){
		if($this->objXmlComp->name && $this->objXmlComp->name != "")
			return $this->objXmlComp->name;
    	if($this->objXmlComp->id && $this->objXmlComp->id != "")
			return $this->objXmlComp->id;
    	if($this->objXmlComp->value && $this->objXmlComp->value != "")
			return $this->objXmlComp->value;
    	return false;
    }
}
?>