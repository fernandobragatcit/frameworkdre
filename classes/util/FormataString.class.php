<?php

class FormataString {

    public function __construct() {
        
    }

    /**
     * Método responsável pela substituição de caracteres inválidos por correspondentes válidos
     *
     * @author André Coura
     * @since 1.0 - 10/08/2008
     */
    public static function subsChars($string) {
        // Aplicando utf8_decode à string que define os caracteres a serem convertidos, a string
        // fornecida também nao pode estar em utf8
        $a = utf8_decode("ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ");
        return strtr($string, $a, "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
    }

    public static function retiraCharsInvalidos($strSTR) {
        $paradas = array("'", "\\", "\"");
        return htmlspecialchars(strip_tags(str_replace($paradas, "", $strSTR)), ENT_QUOTES);
    }

    public static function debuga() {
        $arrDados = func_get_args();
        print("<pre>");
        for ($i = 0; $i < count($arrDados); $i++) {
            print("<BR>");
            print_r($arrDados[$i]);
        }
        die();
    }

    public static function renomeiaPasta($string) {
        $a = array("á", "ã", "à", "ä", "â", "Á", "Ã", "À", "Ä", "Â");
        $string = str_replace($a, "a", $string);
        $e = array("é", "ë", "è", "ê", "É", "Ë", "È", "Ê", "&");
        $string = str_replace($e, "e", $string);
        $i = array("í", "ï", "ì", "î", "Í", "Ï", "Ì", "Î");
        $string = str_replace($i, "i", $string);
        $o = array("ó", "ö", "ò", "õ", "ô", "Ó", "Ö", "Ò", "Õ", "Ô");
        $string = str_replace($o, "o", $string);
        $u = array("ú", "ü", "ù", "û", "Ú", "Ü", "Ù", "Û");
        $string = str_replace($u, "u", $string);
        $c = array("ç", "Ç");
        $string = str_replace($c, "c", $string);
        $n = array("ñ", "Ñ");
        $string = str_replace($n, "n", $string);
        $string = ereg_replace("[^a-zA-Z0-9-]", "", strtr($string, " ", "-"));
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        return $string;
    }

    public static function retiraCaracterEspecial($string) {
        $a = array("á", "ã", "à", "ä", "â", "Á", "Ã", "À", "Ä", "Â");
        $string = str_replace($a, "a", $string);
        $e = array("é", "ë", "è", "ê", "É", "Ë", "È", "Ê", "&");
        $string = str_replace($e, "e", $string);
        $i = array("í", "ï", "ì", "î", "Í", "Ï", "Ì", "Î");
        $string = str_replace($i, "i", $string);
        $o = array("ó", "ö", "ò", "õ", "ô", "Ó", "Ö", "Ò", "Õ", "Ô");
        $string = str_replace($o, "o", $string);
        $u = array("ú", "ü", "ù", "û", "Ú", "Ü", "Ù", "Û");
        $string = str_replace($u, "u", $string);
        $c = array("ç", "Ç");
        $string = str_replace($c, "c", $string);
        $n = array("ñ", "Ñ");
        $string = str_replace($n, "n", $string);
        return $string;
    }

    public static function retiraTodoHtmlCampo($campo) {
        $result = html_entity_decode($campo, ENT_QUOTES);

        return $result;
    }

    public static function retiraHtmlVetor($array) {
        for ($i = 0; $i < count($array); $i++) {
            $array[$i] = htmlspecialchars($array[$i]);
        }
        return $array;
    }

    public static function retiraHtmlMatriz($array) {
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($array[$i]); $j++) {
                $array[$i][$j] = htmlspecialchars($array[$i][$j]);
            }
        }
        return $array;
    }

    public static function colocaHtmlVetor($array) {
        $arrDadosNovo = array();
        foreach ($array as $index => $dados) {
            $arrDadosNovo[$index] = htmlspecialchars_decode($dados);
        }
        return $arrDadosNovo;
    }

    public static function colocaHtmlMatriz($array) {
        $arrDadosNovo = array();
        for ($i = 0; $i < count($array); $i++) {
            foreach ($array[$i] as $index => $dados) {
                $arrDadosNovo[$i][$index] = htmlspecialchars_decode($dados);
            }
        }
        return $arrDadosNovo;
    }

    public static function getCampoAlteradosVetor($array1, $array2) {
        //converto array de index com nomes por nomes e numeros
        $array1 = FormataPost::parseIndexNameArray($array1);
        $array2 = FormataPost::parseIndexNameArray($array2);

        $arrCont1 = count($array1);
        $arrCont2 = count($array2);
        if ($arrCont1 < $arrCont2) {
            $arrAux = $array2;
            $array2 = $array1;
            $array1 = $arrAux;
            $inverteu = true;
        }

        $result = "";
        for ($i = 0; $i < count($array1); $i++) {
            for ($j = 0; $j < count($array2); $j++) {
                if (strcmp($array1[$i][1], $array2[$j][1]) == 0) {
                    if (strcmp(self::removerCaracterEspeciais(strip_tags($array1[$i][0])), self::removerCaracterEspeciais(strip_tags($array2[$j][0]))) != 0) {
                        if ($inverteu) {
                            $result = $result . "<br /><br />|CAMPO " . $array2[$j][1] . " : ALTERADO DE: " . $array2[$j][0] . "<br /><br />PARA: " . $array1[$i][0] . "";
                        } else {
                            $result = $result . "<br /><br />|CAMPO " . $array1[$i][1] . " : ALTERADO DE: " . $array1[$i][0] . "<br /><br />PARA: " . $array2[$j][0] . "";
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function removerCaracterEspeciais($string) {

        $string = strip_tags($string);
        $string = ereg_replace("[áàâãª]", "a", $string);
        $string = ereg_replace("[ÁÀÂÃ]", "A", $string);
        $string = ereg_replace("[éèê]", "e", $string);
        $string = ereg_replace("[ÉÈÊ]", "E", $string);
        $string = ereg_replace("[íì]", "i", $string);
        $string = ereg_replace("[ÍÌ]", "I", $string);
        $string = ereg_replace("[óòôõº]", "o", $string);
        $string = ereg_replace("[ÓÒÔÕ]", "O", $string);
        $string = ereg_replace("[úùû]", "u", $string);
        $string = ereg_replace("[ÚÙÛ]", "U", $string);
        $string = ereg_replace("ç", "c", $string);
        $string = ereg_replace("Ç", "C", $string);
        $string = ereg_replace("[][><}{)(:;,!?*%~^`&#@]", "", $string);
        $string = addslashes($string);
        $string = ereg_replace("\\'", "", $string);
        $string = htmlspecialchars($string);
        $string = str_replace("\'", "", $string);
        $string = str_replace("039", "", $string);
        $string = str_replace("\r\n", "", $string);
        $string = preg_replace('/\s/', '', $string);
        $string = trim($string);


        return $string;
    }

    public static function formataUrlVideoForEmbed($string) {
        if (strpos($string, "embed/")) {
            $pos = stripos($string, "embed/");
            $ini = (int) $pos + 5;
            $url = substr($string, $ini);
            $url = explode("&", $url);
            $url = $url[0];
        } else if (strpos($string, "v=")) {
            $pos = stripos($string, "v=");
            $ini = (int) $pos + 2;
            $url = substr($string, $ini);
            $url = explode("&", $url);
            $url = $url[0];
        } else {
            $url = null;
        }

        return $url;
    }

    public static function getPastaDoCaminho($request_uri) {
        $request_uri = explode("/", $request_uri);
        $arrTratado = array();
        foreach ($request_uri as $strValor) {
            if (!empty($strValor)) {
                $arrTratado[] = $strValor;
            }
        }
        return $arrTratado[count($arrTratado) - 1];
    }

    /**
     * Função para contar quantidade de caracteres de uma string.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 14/05/2013 
     * @param string $string //uma string para ser contada.
     * @return Integer $numCaracteres //quantidade de caracteres da string.
     */
    public static function contaCaracteresString($string) {
        $aString = str_split($string);
        $numCaracteres = count($aString);
        return $numCaracteres;
    }

    /**
     * Função para converter um array XML em uma string.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 01/07/2013 
     * @param array $arrayXml //um array de um xml.
     * @return String $strXml //uma string xml.
     */
    public static function parseArrayXmlToStringAkna($arrayXml) {
        $strXml = htmlspecialchars($arrayXml[3]);
        $strXml = substr(htmlspecialchars_decode($strXml, ENT_QUOTES), 12, 7);
        $strXml = explode("=", preg_replace('/(\'|")/', "", $strXml));
        $valor = $strXml[1];
        return $valor;
    }

    /**
     * Função para gerar um código único que nunca vai se repetir.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 09/07/2013 
     * @return String //um código único.
     */
    public static function gerarCodigoUnico() {
        usleep(100);
        $codigo = str_replace(" ", "", str_replace(".", "", date("dmYHis") . microtime()));
        return md5($codigo);
    }

}

?>