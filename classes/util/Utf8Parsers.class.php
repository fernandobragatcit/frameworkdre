<?php

class Utf8Parsers {


	public static function arrayUtf8Decode($array){
		$newArray = array();
		if(is_array($array) && sizeof($array) > 0)
			foreach ($array as  $indx=>$campo) {
				$newArray[$indx] = utf8_decode($campo);
			}
		return $newArray;
	}

	public static function arrayUtf8Encode($array){
		$newArray = array();
		if(is_array($array) && sizeof($array) > 0)
			foreach ($array as  $indx=>$campo) {
				$newArray[$indx] = utf8_encode($campo);
			}
		return $newArray;
	}


	public static function matrizUtf8Decode($matriz){
		$i=0;$j=0;
		$newMatriz=array();
		if(is_array($matriz) && sizeof($matriz) > 0){
			foreach ($matriz as $array) {
				foreach ($array as $indx=>$campo) {
					$newMatriz[$i][$indx] = utf8_decode($campo);
				}
				$i++;
			}
		}
		return $newMatriz;
	}

	public static function matrizUtf8Encode($matriz){
		$i=0;$j=0;
		$newMatriz=array();
		if(is_array($matriz) && sizeof($matriz) > 0){
			foreach ($matriz as $array) {
				if(is_array($array) && sizeof($array) > 0){
					foreach ($array as $indx=>$campo) {
						$newMatriz[$i][$indx] = utf8_encode($campo);
					}
				}
				$i++;
			}
		}
		return $newMatriz;
	}
	
}
?>