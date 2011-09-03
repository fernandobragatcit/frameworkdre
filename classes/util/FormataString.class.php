<?php

class FormataString {

	public function __construct(){	}

	/**
	 * Método responsável pela substituição de caracteres inválidos por correspondentes válidos
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
	public static function subsChars($string){
		// Aplicando utf8_decode à string que define os caracteres a serem convertidos, a string
		// fornecida também nao pode estar em utf8
		$a = utf8_decode("ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ");
		return strtr($string, $a, "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	}

	public static function retiraCharsInvalidos($strSTR){
		$paradas = array("'", "\\", "\"");
		return htmlspecialchars(strip_tags(str_replace($paradas, "", $strSTR)), ENT_QUOTES);
	}

	public static function renomeiaPasta($string){
		$a = array("á","ã","à","ä","â");
		$string = str_replace($a, "a",$string);
		$e = array("é","ë","è","ê");
		$string = str_replace($e, "e",$string);
		$i = array("í","ï","ì","î");
		$string = str_replace($i, "i",$string);
		$o = array("ó","ö","ò","õ","ô");
		$string = str_replace($o, "o",$string);
		$u = array("ú","ü","ù","û");
		$string = str_replace($u, "u",$string);
		$string = str_replace("ç", "c",$string);
		$string = ereg_replace("[^a-zA-Z0-9_]", "", strtr($string, " ", "_"));
		return $string;
	}
	
	
	public static function retiraHtmlVetor($array){
		for ($i = 0; $i < count($array); $i++) {
			$array[$i] = htmlspecialchars($array[$i]); 
		}
		return $array;
	}
	
	public static function retiraHtmlMatriz($array){
		for ($i = 0; $i < count($array); $i++) {
			for($j = 0; $j < count($array[$i]); $j++){
				$array[$i][$j] = htmlspecialchars($array[$i][$j]); 
			}
		}
		return $array;
	}
	
	public static function colocaHtmlVetor($array){
		$arrDadosNovo = array();
		foreach ($array as $index => $dados) {
			$arrDadosNovo[$index] = htmlspecialchars_decode($dados); 
		}
		return $arrDadosNovo;
	}
	
	public static function colocaHtmlMatriz($array){
		$arrDadosNovo = array();
		for ($i = 0; $i < count($array); $i++) {
			foreach ($array[$i] as $index => $dados) {
				$arrDadosNovo[$i][$index] = htmlspecialchars_decode($dados); 
			}
		}
		return $arrDadosNovo;
	}

}
?>