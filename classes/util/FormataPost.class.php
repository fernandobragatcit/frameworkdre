<?php

class FormataPost {

    public static function classificaRetiraSufixo($arrPost, $sufixo) {
        $arrNovoPost = array();
        foreach ($arrPost as $key => $valor) {
            if (strpos($key, $sufixo) > 0) {
                $arrNovoPost[str_replace($sufixo, "", $key)] = $valor;
            }
        }
        return $arrNovoPost;
    }

    public static function classificaColocaSufixo($arrPost, $sufixo) {
        $arrNovoPost = array();
        foreach ($arrPost as $key => $valor) {
            $arrNovoPost[$key . $sufixo] = $valor;
        }
        return $arrNovoPost;
    }

    public static function mergeArrayPost($arrDados, $post) {
        $novoArrDados = array();
        $novoPost = array();
        if (count($arrDados) > 0)
            foreach ($arrDados as $key => $valor) {
                $novoArrDados[strtolower($key)] = $valor;
            }
        return array_merge($novoArrDados, $post);
    }

    public static function diferenca2array($arrDados, $post) {
        $novoArray = array();
        if (count($arrDados) > 0 && count($post) > 0) {
            foreach ($arrDados as $key1 => $valor1)
                foreach ($post as $key2 => $valor2)
                    if ($key1 == $key2)
                        if ($valor1 != $valor2)
                            $novoArray[strtolower($key1)] = $valor2;
            return $novoArray;
        }else {
            return $post;
        }
    }

    public static function limpaArray($arrDados) {
        $novoArray = array();
        if (count($arrDados) > 0) {
            foreach ($arrDados as $key1 => $valor1)
                if ($valor1 != "")
                    $novoArray[strtolower($key1)] = $valor1;
            return $novoArray;
        }else
            return $arrDados;
    }

    public static function parseIndexNameArray($arrDados) {
        $i = 0;
        foreach ($arrDados as $index => $dados) {
            $st = array_keys($arrDados);
            $result[] = Array($dados, $st[$i]);
            $i++;
        }
        return $result;
    }

    /**
     * Método para montar uma estrutura de array com dados de log
     *
     * @author Fernando Braga
     * @since 1.0 - 22/06/2012
     */
    public static function montaArrayLog($desc, $id_usuario_cad, $nome_usuario_cad, $email_usuario_cad, $data_cadastro, $id_usuario_alt, $nome_usuario_alt, $email_usuario_alt, $data_alteracao, $texto_log, $nome_registro) {
        $valores = array("descricao" => utf8_decode($desc),
            "id_usuario_cad" => $id_usuario_cad,
            "nome_usuario_cad" => utf8_decode($nome_usuario_cad),
            "email_usuario_cad" => $email_usuario_cad,
            "data_cadastro" => $data_cadastro,
            "id_usuario_alt" => $id_usuario_alt,
            "nome_usuario_alt" => utf8_decode($nome_usuario_alt),
            "email_usuario_alt" => $email_usuario_alt,
            "data_alteracao" => $data_alteracao,
            "texto_log" => $texto_log,
            "nome_registro" => utf8_decode($nome_registro));
        return $valores;
    }

    public static function verificaArraySeExisteAlgumValor($array) {
        $result = false;
        foreach ($array as $valor) {
            if (!empty($valor)) {
                $result = true;
            }
        }
        return $result;
    }
    
    //organiza o array com os resultados vindos do banco em sequência.
    //impedindo que fique array dentro de array
    //serve apenas para quando houver um segundo array dentro do primeiro.
    public static function colocaValoresEmSequenciaAposUmSelect($array = null) {
        $arrResult = Array();
        if (!empty($array)) {
            foreach ($array as $i => $valor) {
                $arrResult[] = $valor[0];
            }
        }
        return $arrResult;
    }

}

?>