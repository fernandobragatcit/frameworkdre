<?php
ob_start();

/**
 * Index default das pastas, tem a função de buscar as funcionalidades
 * do index principal default.
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 2.0 - 30/08/2010
 */
function getIndexPrincipal($strCaminho = ""){
	if(file_exists($strCaminho."indexPrincipal.php")){
		return $strCaminho."indexPrincipal.php";
	}
	return getIndexPrincipal("../".$strCaminho);
}

$_SERVIDOR_FIS = getIndexPrincipal();

include($_SERVIDOR_FIS);

?>