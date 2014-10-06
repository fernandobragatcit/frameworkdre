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

    /**
     * Função para limpar espaços e compactar uma string de código html.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 09/07/2013 
     * @return String //um html em formato string.
     */
    public static function compactarLimparStringHtml($strHtml) {
        $strHtml = str_replace("\n", ' ', $strHtml);
        $strHtml = ereg_replace('[[:space:]]+', ' ', $strHtml);
        return $strHtml;
    }

    public static function comparaStringSensitive($str1, $str2) {
        //comparacao case sensitive
        if (strcmp($str1, $str2) != 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function comparaStringInsensitive($str1, $str2) {
        //comparacao case insensitive
        if (strcasecmp($str1, $str2) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getPastaAtual() {
        $caminhoFisico = explode("\\", getcwd());
        $pasta = $caminhoFisico[count($caminhoFisico) - 1];
        return $pasta;
    }

    public static function raw_json_encode($input) {

        return preg_replace_callback(
                '/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {
            return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
        }, json_encode($input)
        );
    }

    /** método que prepara nome da pasta de uma nova área ou caminho
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @param String $name_pasta
     * @return String $nome_formatado
     * */
    public static function formataNomePastaArea($nomeArea) {
        //chamo um método já criado anteriormente que retira os caracteres especiais e prepara o nome.
        $nomeArea = strtolower(self::renomeiaPasta($nomeArea));
        return $nomeArea;
    }

    /* exemplo de uso: chamo a função copiar passando url origem e destino respectivamente. 
     */

    public static function rrmdir($Dir_destino) {
        if (is_dir($Dir_destino)) {
            $files = scandir($Dir_destino);
            foreach ($files as $file)
                if (is_dir("$Dir_destinho / $Dir_Origem")) {
                    if ($file != "." && $file != "..")
                        self::rrmdir("$Dir_destinho/$file");
                }else {
                    if ($file != "." && $file != "..")
                        self::rrmdir("$Dir_destinho/$file");
                }
            self::rrmdir($dir);
        }else if (file_exists($dir))
            unlink($dir);
    }

    public static function copiar($src, $dst) {
        if (file_exists($dst))
            self::rrmdir($dst);
        if (is_dir($src)) {
            if (!is_dir($dst)) {
                mkdir($dst, 0777);
            }
            $files = scandir($src);
            foreach ($files as $file)
                if ($file != "." && $file != "..")
                    self::copiar("$src/$file", "$dst/$file");
        }
        else if (file_exists($src))
            copy($src, $dst);
    }

    public static function renomeiaPastaNoCaminho($strPastaAntiga, $strPastaNova, $strCaminhoPasta = "") {
        if (isset($strPastaAntiga) && $strPastaAntiga != "" && isset($strPastaNova) && $strPastaNova != "") {
            if (!is_dir($strCaminhoPasta . $strPastaAntiga . "/"))
                echo "Nao existe uma pasta com o nome especificado.";
            if (!rename($strCaminhoPasta . $strPastaAntiga, $strCaminhoPasta . $strPastaNova))
                echo "Não foi possível renomear a pasta.";
//Criada a pasta, gera-se a estrutura interna dela.
        }else {
            echo "Não foi passado o nome para renomear a pasta.";
        }
        return true;
    }

    /** método deleta pasta do caminho da area e as subpastas.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @param String $strPasta - nome da pasta
     * @param String $strCaminhoPasta - caminho da pasta que deve ser deletada
     * * */
    public static function deletaPastaeSubpasta($strPasta, $strCaminhoPasta = "") {
        if (isset($strPasta) && $strPasta != "") {
            if (is_dir($strCaminhoPasta . $strPasta . "/")) {
                if ($ponteiro = opendir($strCaminhoPasta . $strPasta . "/")) {
                    while ($nome_itens = readdir($ponteiro)) {
                        if ($nome_itens != "." && $nome_itens != "..") {
                            if (is_dir($strCaminhoPasta . $strPasta . "/" . $nome_itens)) {
                                $pastas[] = $nome_itens;
                            } else {
                                chmod($strCaminhoPasta . $strPasta . "/" . $nome_itens, 0777);
                                unlink($strCaminhoPasta . $strPasta . "/" . $nome_itens);
                            }
                        }
                    }

                    if ($pastas[0] != "") {
                        foreach ($pastas as $pasta) {
                            self::deletaPastaeSubpasta($pasta, $strCaminhoPasta . $strPasta . "/");
                        }
                    }
                }
                closedir($ponteiro);
                rmdir($strCaminhoPasta . $strPasta . "/");
            } else {
                echo "A pasta referida não existe.";
            }
        }
    }

    /** método que gerencia a pasta do caminho da area
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @param String $nome_pasta_destino - nome da pasta
     * @param String $origem - caminho da pasta que deve ser copiada
     * @param String $destino - caminho para onde a pasta deve ser copiada
     * @param String $nome_pasta_copia - nome da pasta modelo que será copiada
     * @param String $renomear_pasta - nome para o qual a pasta será renomeada
     * * */
    public static function salvaAlteraPastaArea($nome_pasta_destino, $destino, $origem = null, $nome_pasta_copia = null, $pasta_atual = null) {
        $origem = PASTA_FWK . "files/" . $nome_pasta_copia . "/";
        $destino = SERVIDOR_FISICO . PASTA_CONTEUDO . "/";
        if ($nome_pasta_destino != $pasta_atual && $pasta_atual!=null) {
            if (is_dir($destino . $pasta_atual . "/")) {
                FormataString::renomeiaPastaNoCaminho($pasta_atual, $nome_pasta_destino, $destino);
            } elseif (!is_dir($destino . $nome_pasta_destino . "/")) {
                FormataString::copiar($origem, $destino . $nome_pasta_destino);
            }
        } elseif (!is_dir($destino . $nome_pasta_destino . "/")) {
            FormataString::copiar($origem, $destino . $nome_pasta_destino);
        }
    }

}
?>