<?php
require_once(FWK_DAO."LinkEncurtadoDAO.class.php");
require_once(FWK_UTIL."Cryptografia.class.php");

class FormataLink {
	
     public static function definiTipoLink($strTipo){
     	switch($strTipo){
			case "MODULO":
				return "m";
			default:
				return "c";
		}
     }

	/**
	 * Prepara um link com as variaveis GET especicicadas, opcionalmente filtrando
	 * uma delas.
	 *
	 * Exemplo:
	 * $strBase = "dir1/dir2/"
	 * $arrVarsGet = array("key1" => "val1", "key2" => "", "key3" => "val3");
	 *
	 * preparaLinkGet($strBase, $arrVarsGet);
	 *   --> "dir1/dir2/?key1=val1&key3=val3"
	 *
	 * prepareLinkGet($strBase, $arrVarsGet, "key1");
	 *   --> "dir1/dir2/?key3=val3"
	 *
	 * preparaLinkGet($strBase, $arrVarsGet, "key1", TRUE);
	 *   --> "dir1/dir2/?key3=val3&"
	 *
 	 * preparaLinkGet($strBase, $arrVarsGet, null, TRUE);
	 *   --> "dir1/dir2/?key1=val1&key3=val3&"
	 *
	 * @param string $strBase URL base para o link a ser criado
	 * @param array $arrVarsGet Array que mapeia cada variavel no seu valor, inclusive a
	 *                          variavel filtrada.
	 * @param string $strVarFiltro Opcional: Nome da variavel a ser filtrada
	 * @param bool $incluiAmp Opcional: Se for TRUE acrescenta & no final do link para
	 *                        posterior inclusao de variaveis.
	 * @return Link com variaveis GET especificadas
	 */
	public static function preparaLinkGet($strBase, $arrVarsGet, $strVarFiltro="", $incluiAmp=FALSE) {
		$boolTemVar = FALSE;

		// Acrescentando cada variavel 'a URL, exceto a variavel do filtro
		foreach ( $arrVarsGet as $var => $value ) {
			if ( $var != null and $var != "" and $var != $strVarFiltro) {
				if ( $value != null and $value != "") {
					if ( $boolTemVar ) {
						$strBase .= "&amp;{$var}={$value}";
					}
					else {
						$strBase .= "?{$var}={$value}";
						$boolTemVar = TRUE;
					}
				}
			}
		}

		// Acrescentando ao final a variavel do filtro
		if ( $incluiAmp ) {
			if ( $boolTemVar ) {
				$strBase .= "&amp;";
			}
			else {
				$strBase .= "?";
			}
		}

		
		return $strBase;
	}
	
	/*
	 * Converte uma url em mini url.
	 * 
	 * Exemplo:
	 * $urlLong = "http://www.tcit.com.br/?m=KAJSD983123LKJADASLKJDAS891230983432KL4JASDLKJSOADSIUASDOIUASDLKU123098="
	 * 
	 * $urlShort = "http://www.tcit.com.br/?s=KA3D="
	 * 
	 * @param = string $urlLong -> Url parametros.
	 * @return = string $urlShort -> Mini-Url para ser enviada.
	 * 
	 */
	public static function getMiniUrl($urlLong) {
		$urlShort = null;
		$objLink = new LinkEncurtadoDAO();
		$idUrlShort = $objLink->cadastrar($urlLong);
		$objCrypt = new Cryptografia();
		return $urlShort = RET_SERVIDOR."?s=".$objCrypt->cryptData($idUrlShort);
	}
	
	public static function abreMiniUrl($idUrlShort){
		$objLink = new LinkEncurtadoDAO();
		$urlLong = $objLink->getUrlById($idUrlShort);
		header("Location: ".$urlLong);
	}
	
}
?>