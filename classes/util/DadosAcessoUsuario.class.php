<?php
require_once (BIB_ISMOBILE);

class DadosAcessoUsuario {

	public static function is_mobile(){
		$ismobi = new IsMobile();
		if($ismobi->CheckMobile())
			return true;
		else
			return false;
	}
	
}
?>