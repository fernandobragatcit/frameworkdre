<?php

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
						$strBase .= "&{$var}={$value}";
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
				$strBase .= "&";
			}
			else {
				$strBase .= "?";
			}
		}

		
		return $strBase;
	}
}
?>