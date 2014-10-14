<?php
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_CONTROL."ControlSmarty.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");

/**
 * Classe responsávekl por montar o menu de acordo com o grupo do usuário
 *
 * @author André Coura
 * @since 1.0 - 05/02/2008
 */
class ViewMenu {

	private $objSmarty;
	private $objCtrlConfiguracoes;
	private $objDB;
	private $objJs;
	private $objCss;
	private $objCrypt;
	private $cssMenu = "";
	private $idsGrupos;
	private $idUsuario;

	/**
	 * Construtor
	 *
	 * @author André Coura
	 * @since 1.0 - 05/02/2008
	 * @param Object Objeto smarty anteriormente instanciado
	 */
    public function __construct() {
    	$this->objCrypt = new Cryptografia();
    	$this->objDB = ControlDB::getBanco();
    	$this->objDB->SetFetchMode(ADODB_FETCH_NUM);
    }
    
    private function getObjSmarty(){
    	if($this->objSmarty == null)
    		$this->objSmarty = ControlSmarty::getSmarty();
    	return $this->objSmarty;
    }
    
    private function getCtrlConfiguracoes(){
    	if($this->objCtrlConfiguracoes == null)
    		$this->objCtrlConfiguracoes = new ControlConfiguracoes();
    	return $this->objCtrlConfiguracoes;
    }

    /**
     * Verifica o banco e cria os itens de Menu
     *
     * @author André Coura
     * @since 1.0 - 05/02/2008
     */
    private function criaMenu(){
    	self::getObjSmarty()->assign("ARR_MENU",self::getMenuPai());
    	self::getObjSmarty()->assign("ARR_FILHOS",self::getMenuFilhos());
    	self::getObjSmarty()->assign("ARR_FILHOS2",self::getSubMenuFilhos());
    	self::getObjSmarty()->assign("ARR_FILHOS3",self::getSubMenuNetos());
    }

    /**
     * Método para gerar o menu de acordo com o grupo que o usuário pertence.
     *
     * @author André Coura
     * @since 1.0 - 01/02/2008
     * @since 2.0 - 10/02/2008
     * @param int id od grupo que o usuário em questão pertence
     * @return String a compilação da tela de login
     */
    public function geraMenu($idGrupos,$idUsuario){
    	if(self::getCssMenu()!="")
	    	$this->objCss->addCss(self::getCssMenu());
    	self::setIdUsuario($idUsuario);
    	self::criaMenu();
    	return self::getObjSmarty()->fetch(self::getTplMenu());
    }

    /**
     * Método para gerar o menu de acordo com o grupo que o usuário pertence.
     *
     * @author Matheus Vieira
     * @since 1.0 - 28/08/2011
     * @param int id od grupo que o usuário em questão pertence
     * @return array com os dados do menu
     */
    public function pegaMenu($idGrupos,$idUsuario){
    	if(self::getCssMenu()!="")
	    	$this->objCss->addCss(self::getCssMenu());
    	self::setIdUsuario($idUsuario);
    	$arrMenu["arr_menu"] = self::getMenuPai();
    	$arrMenu["arr_filhos"] = self::getMenuFilhos();
    	$arrMenu["arr_filhos2"] = self::getSubMenuFilhos();
    	$arrMenu["arr_filhos3"] = self::getSubMenuNetos();
    	return $arrMenu;
    }

    private function setIdUsuario($idUser){
    	$this->idUsuario = $idUser;
    }

    private function getIdUsuario(){
    	return $this->idUsuario;
    }

    public function setCssMenu($cssMenu = ""){
   		$this->cssMenu = $cssMenu;
    }

    public function setTplMenu($strTplMenu){
		$this->tplMenu = $strTplMenu;
    }

    public function getTplMenu(){
    	if($this->tplMenu == null)
    		$this->tplMenu = FWK_HTML_MENU."menuDre.tpl";
    	return $this->tplMenu;
    }

    public function getCssMenu(){
    	return $this->cssMenu;
    }

    private function getMenuPai(){
    	$strQuery = "SELECT DISTINCT
						me.nome_menu, me.link_menu, me.id_menu, me.tipo_menu
					FROM
						fwk_menu me
						INNER JOIN fwk_direitos di ON me.id_menu = di.id_menu
						LEFT JOIN fwk_direitos_usuario du ON di.id_direitos = du.id_direitos
						LEFT JOIN fwk_direitos_grupo dg	ON di.id_direitos = dg.id_direitos
						INNER JOIN fwk_grupo gr
						ON dg.id_grupo = gr.id_grupo
					WHERE
						(me.id_portal = '".self::getCtrlConfiguracoes()->getIdPortal()."' OR me.id_portal = '1') 
						AND
						(gr.id_grupo in(
								select id_grupo from fwk_grupo_usuario g1
								where g1.id_usuario = ".self::getIdUsuario()."
									  )
						OR du.id_usuario = ".self::getIdUsuario().")
					ORDER BY
						ordem_menu";
    	
		$arrMenu = $this->objDB->GetAll($strQuery);
		for ($cont = 0; $cont < sizeof($arrMenu); $cont++){
			if($arrMenu[$cont][0] != null && $arrMenu[$cont][0] !="" )
				$arrMenu[$cont][0] =$arrMenu[$cont][0];
			if($arrMenu[$cont][1] != null && $arrMenu[$cont][1] !="" && $arrMenu[$cont][1] !="#"){
				$arrMenu[$cont][1] ="?".$arrMenu[$cont][3]."=".$this->objCrypt->cryptData($arrMenu[$cont][1]);
			}

		}
    	return $arrMenu;
    }

    private function getMenuFilhos(){
    	$itemMenu = 0;
    	$arrMenuFi = array();
    	$arrDados = self::getMenuPai();
    	if(is_array($arrDados) && count($arrDados)>0){
	    	foreach($arrDados as $menu){
	    		$arrMenuFi[$itemMenu++] = self::getItensMenu($menu[2]);
	    	}
   		}
    	return $arrMenuFi;
    }

    private function getSubMenuFilhos(){
		$itemMenu = 0;
		$itemSubMenu = 0;
    	$arrSubMenuFi = array();
    	$arrDados = self::getMenuPai();
    	if(is_array($arrDados) && count($arrDados)>0){
	    	foreach($arrDados as $menu){
	    		$itemSubMenu = 0;
	    		if(is_array($menu) && count($menu)>0){
		    		foreach(self::getItensMenu($menu[2]) as $subMenus){
						$arrAdd = self::getSubItensMenu($subMenus[2]);
						//evita que o menu se repita caso tenham mesmo id menu pai e filho
						$arrSubMenuFi[$itemMenu][$itemSubMenu++]=($subMenus[2]!=$arrAdd[0][2])?$arrAdd:array();
		    		}
	    		}
	    		$itemMenu++;
	    	}
   		}
    	return $arrSubMenuFi;
    }

    private function getSubMenuNetos(){
    	$arrMenu = self::getMenuPai();
    	if(is_array($arrMenu) && count($arrMenu)>0){
    		for($m = 0; $m < count($arrMenu); $m++){
	    		if(is_array($arrMenu[$m]) && count($arrMenu[$m])>0){
	    			$arrFilho1 = self::getItensMenu($arrMenu[$m][2]);
	    			for($f1 = 0; $f1 < count($arrFilho1); $f1++){
						$arrFilho2 = self::getSubItensMenu($arrFilho1[$f1][2]);
						for($f2 = 0; $f2 < count($arrFilho2); $f2++){
							if(is_array($arrFilho2[$f2]) && count($arrFilho2[$f2])>0){
								$arrFilho3 = self::getSubItensMenu($arrFilho2[$f2][2]);
								$arrSubMenuNets[$m][$f1][$f2] = $arrFilho3;
							}
						}
		    		}
	    		}
	    	}
   		}
    	return $arrSubMenuNets;
    }

    private function getItensMenu($idMenuPai){
    	$strQuery = "SELECT DISTINCT
						im.nome_item_menu, im.link_item_menu, im.id_item_menu, im.id_menu_pai, tipo_item_menu
					FROM
						fwk_item_menu im LEFT JOIN fwk_direitos di
						ON di.id_item_menu = im.id_item_menu
						LEFT JOIN fwk_direitos_usuario du
						ON di.id_direitos = du.id_direitos
						LEFT JOIN fwk_direitos_grupo dg
						ON di.id_direitos = dg.id_direitos
						INNER JOIN fwk_grupo gr
						ON dg.id_grupo = gr.id_grupo
					WHERE
						(im.id_portal = '".self::getCtrlConfiguracoes()->getIdPortal()."' OR im.id_portal = '1') 
						AND
						im.id_menu_pai = ".$idMenuPai." AND
						(gr.id_grupo in(select id_grupo from fwk_grupo_usuario g1
										where g1.id_usuario = ".self::getIdUsuario().")
						OR du.id_usuario = ".self::getIdUsuario().")
					ORDER BY
						im.ordem_item_menu";
    	$arrMenu = $this->objDB->GetAll($strQuery);
    	for ($cont = 0; $cont < sizeof($arrMenu); $cont++){
			if($arrMenu[$cont][0] != null && $arrMenu[$cont][0] !="" )
				$arrMenu[$cont][0] =$arrMenu[$cont][0];
			if($arrMenu[$cont][1] != null && $arrMenu[$cont][1] !="" && $arrMenu[$cont][1] !="#")
				$arrMenu[$cont][1] ="?".$arrMenu[$cont][4]."=".$this->objCrypt->cryptData($arrMenu[$cont][1]);
		}
    	return $arrMenu;
    }


	private function getSubItensMenu($idMenuPai){
    	$strQuery = "SELECT DISTINCT
						im.nome_item_menu, im.link_item_menu, im.id_item_menu, im.id_menu_pai, tipo_item_menu
					FROM
						fwk_item_menu im LEFT JOIN fwk_direitos di
						ON di.id_item_menu = im.id_item_menu
						LEFT JOIN fwk_direitos_usuario du
						ON di.id_direitos = du.id_direitos
						LEFT JOIN fwk_direitos_grupo dg
						ON di.id_direitos = dg.id_direitos
						INNER JOIN fwk_grupo gr
						ON dg.id_grupo = gr.id_grupo
					WHERE
						(im.id_portal = '".self::getCtrlConfiguracoes()->getIdPortal()."' OR im.id_portal = '1') 
						AND
						im.id_item_menu_pai = ".$idMenuPai." AND
						(gr.id_grupo in(select id_grupo from fwk_grupo_usuario g1
										where g1.id_usuario = ".self::getIdUsuario().")
						OR du.id_usuario = ".self::getIdUsuario().")
					ORDER BY
						im.ordem_item_menu";
    	$arrMenu = $this->objDB->GetAll($strQuery);
    	for ($cont = 0; $cont < sizeof($arrMenu); $cont++){
			if($arrMenu[$cont][0] != null && $arrMenu[$cont][0] !="" )
				$arrMenu[$cont][0] =$arrMenu[$cont][0];
			if($arrMenu[$cont][1] != null && $arrMenu[$cont][1] !="" && $arrMenu[$cont][1] !="#")
				$arrMenu[$cont][1] ="?".$arrMenu[$cont][4]."=".$this->objCrypt->cryptData($arrMenu[$cont][1]);
		}
    	return $arrMenu;
	}

    public function setObjCss($objCss){
		$this->objCss = $objCss;
    }

    public function setObjJs($objJs){
		$this->objJs = $objJs;
    }
    
	public function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print_r($arrDados[$i]);
			print"<br />";
		}
		die();
	}
}
?>