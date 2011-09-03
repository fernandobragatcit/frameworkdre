<?php
class FormataPost {

	public static function classificaRetiraSufixo($arrPost, $sufixo) {
		$arrNovoPost = array ();
		foreach ($arrPost as $key => $valor) {
			if (strpos($key, $sufixo) > 0) {
				$arrNovoPost[str_replace($sufixo, "", $key)] = $valor;
			}
		}
		return $arrNovoPost;
	}

	public static function classificaColocaSufixo($arrPost, $sufixo) {
		$arrNovoPost = array ();
		foreach ($arrPost as $key => $valor) {
			$arrNovoPost[$key.$sufixo] = $valor;
		}
		return $arrNovoPost;
	}

	public static function mergeArrayPost($arrDados, $post){
		$novoArrDados = array();
		$novoPost = array();
		if(count($arrDados)>0)
			foreach ($arrDados as $key => $valor) {
				$novoArrDados[strtolower($key)] = $valor;
			}
		return  array_merge($novoArrDados,$post);
	}

	public static function diferenca2array($arrDados, $post){
		$novoArray = array();
		if(count($arrDados)>0 && count($post)>0){
			foreach ($arrDados as $key1 => $valor1)
				foreach ($post as $key2 => $valor2)
					if($key1 == $key2)
						if($valor1 != $valor2)
							$novoArray[strtolower($key1)] = $valor2;
			return $novoArray;
		}
	}

	public static function limpaArray($arrDados){
		$novoArray = array();
		if(count($arrDados)>0){
			foreach ($arrDados as $key1 => $valor1)
				if($valor1 != "")
					$novoArray[strtolower($key1)] = $valor1;
			return $novoArray;
		}else
			return $arrDados;
	}
	
}
?>