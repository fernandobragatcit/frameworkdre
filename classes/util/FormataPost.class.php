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
        } else
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

    /**
     * Método para montar uma estrutura de array com dados de log específico para log de célula
     *
     * @author Fernando Braga
     * @since 1.0 - 22/05/2012
     */
    public static function montaArrayLogCelula($desc, $id_usuario, $nome_usuario, $email_usuario, $id_celula, $nome_celula, $texto_log) {
        $valores = array("descricao" => utf8_decode($desc),
            "id_usuario" => $id_usuario,
            "nome_usuario" => utf8_decode($nome_usuario),
            "email_usuario" => $email_usuario,
            "id_celula" => $id_celula,
            "nome_celula" => $nome_celula,
            "texto_log" => utf8_decode($texto_log));
        return $valores;
    }
     /**
     * Método para montar uma estrutura de array com dados de log específico para log de colaborador
     *
     * @author Fernando Braga
     * @since 1.0 - 22/05/2012
     */
    public static function montaArrayLogColaborador($desc, $id_usuario, $nome_usuario, $email_usuario, $id_colaborador, $nome_colaborador, $texto_log) {
        $valores = array("descricao" => utf8_decode($desc),
            "id_usuario" => $id_usuario,
            "nome_usuario" => utf8_decode($nome_usuario),
            "email_usuario" => $email_usuario,
            "id_colaborador" => $id_colaborador,
            "nome_colaborador" => $nome_colaborador,
            "texto_log" => utf8_decode($texto_log));
        return $valores;
    }
      /**
     * Método para montar uma estrutura de array com dados de log específico para log de direitos de usuário
     *
     * @author Fernando Braga
     * @since 1.0 - 13/06/2012
     */
    public static function montaArrayLogDireitosUser($desc, $id_usuario_alt, $nome_usuario_alt, $email_usuario_alt, $id_usuario, $nome_usuario,$email_usuario, $texto_log) {
        $valores = array("descricao" => utf8_decode($desc),
            "id_usuario_alt" => $id_usuario_alt,
            "nome_usuario_alt" => utf8_decode($nome_usuario_alt),
            "email_usuario_alt" => $email_usuario_alt,
            "id_usuario" => $id_usuario,
            "nome_usuario" => $nome_usuario,
            "email_usuario" => $email_usuario,
            "texto_log" => utf8_decode($texto_log));
        return $valores;
    }
      /**
     * Método para montar uma estrutura de array com dados de log específico para log de direitos de grupo
     *
     * @author Fernando Braga
     * @since 1.0 - 13/06/2012
     */
    public static function montaArrayLogDireitosGrupo($desc, $id_usuario_alt, $nome_usuario_alt, $email_usuario_alt, $id_grupo, $nome_grupo,$texto_log) {
        $valores = array("descricao" => utf8_decode($desc),
            "id_usuario_alt" => $id_usuario_alt,
            "nome_usuario_alt" => utf8_decode($nome_usuario_alt),
            "email_usuario_alt" => $email_usuario_alt,
            "id_grupo" => $id_grupo,
            "nome_grupo" => $nome_grupo,
            "texto_log" => utf8_decode($texto_log));
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

    public static function anti_injection($sql) {
        $sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $sql);
        $sql = trim($sql);
        $sql = strip_tags($sql);
        $sql = addslashes($sql);
        return $sql;
    }

    //removendo duplicação de um array por determinado campo
    public static function eliminaDuplicacaoArrayPorCampo($array, $strCampo) {
        if ($array) {
            $arrayAux = Array();
            foreach ($array as $rs) {
                if (!in_array($rs[$strCampo], $arrayAux)) {
                    $arrayNovo[] = $rs;
                }
                $arrayAux[] = $rs[$strCampo];
            }
        }
        return $arrayNovo;
    }

    //removendo ultimo valor de um array
    public static function eliminaUltimoValorArray($array) {
        unset($array[count($array) - 1]);
    }

}

?>