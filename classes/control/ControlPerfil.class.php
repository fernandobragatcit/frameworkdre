<?php
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class ControlPerfil {

    public function __construct() {
    }



    public function getTplEstruturaPerfil() {
		$strTplPerfil = self::getCtrlConfiguracoes()->getCustomCadUsuarios(null, "perfilUsuario");
		if (isset ($strTplPerfil) && $strTplPerfil != "")
			return $strTplPerfil;

		return FWK_HTML_DEFAULT."perfilUsuario.tpl";
	}

	public function getCtrlConfiguracoes() {
		if ($this->objCtrlConfiguracoes == null)
			$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}
}
?>