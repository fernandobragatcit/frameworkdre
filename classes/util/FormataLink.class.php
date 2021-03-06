<?php

require_once(FWK_DAO . "LinkEncurtadoDAO.class.php");
require_once(FWK_DAO . "VerificacaoDAO.class.php");
require_once(FWK_UTIL . "Cryptografia.class.php");

class FormataLink {

    public static function definiTipoLink($strTipo) {
        switch ($strTipo) {
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
    public static function preparaLinkGet($strBase, $arrVarsGet, $strVarFiltro = "", $incluiAmp = FALSE) {
        $boolTemVar = FALSE;

        // Acrescentando cada variavel 'a URL, exceto a variavel do filtro
        foreach ($arrVarsGet as $var => $value) {
            if ($var != null and $var != "" and $var != $strVarFiltro) {
                if ($value != null and $value != "") {
                    if ($boolTemVar) {
                        $strBase .= "&amp;{$var}={$value}";
                    } else {
                        $strBase .= "?{$var}={$value}";
                        $boolTemVar = TRUE;
                    }
                }
            }
        }

        // Acrescentando ao final a variavel do filtro
        if ($incluiAmp) {
            if ($boolTemVar) {
                $strBase .= "&amp;";
            } else {
                $strBase .= "?";
            }
        }


        return $strBase;
    }

    /*
     * Converte uma url em mini url. 
     * Esta fica salva no banco por um período de 30 dias.
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
        return $urlShort = RET_SERVIDOR . "?s=" . $objCrypt->cryptData($idUrlShort);
    }

    public static function getMiniUrlVirtual($urlLong) {
        $urlShort = null;
        $objLink = new LinkEncurtadoDAO();
        $idUrlShort = $objLink->cadastrar($urlLong);
        $objCrypt = new Cryptografia();
        return $urlShort = URL_VIRTUAL . "?s=" . $objCrypt->cryptData($idUrlShort);
    }

    public static function abreMiniUrl($idUrlShort) {
        $objLink = new LinkEncurtadoDAO();
        $urlLong = $objLink->getUrlById($idUrlShort);
        if ($urlLong != "")
            header("Location: " . $urlLong);
        else {
            $_SESSION["erro_short_url"] = true;
            header("Location: " . RET_SERVIDOR . "aviso/");
        }
    }

    public static function verificaMiniUrl($tabela) {
        $objVer = new VerificacaoDAO();
        if (!$objVer->checkHoje($tabela)) {
            $objLink = new LinkEncurtadoDAO();
            $objLink->verificaMiniUrl();
            $objVer->setVerificacao($tabela);
        }
    }

    public static function colocaHttp($strLink) {
        if (trim($strLink) == "#") {
            $strLink = trim($strLink);
        } else {
            if ($strLink != "") {
                $linkPartido = substr($strLink, 0, 4);
                if ($linkPartido != "http") {
                    $strLink = "http://" . $strLink;
                }
            }
        }

        return $strLink;
    }

    public static function limpaEmail($strEmail) {
        $a = array("á", "ã", "à", "ä", "â", "Á", "Ã", "À", "Ä", "Â");
        $strEmail = str_replace($a, "a", $strEmail);
        $e = array("é", "ë", "è", "ê", "É", "Ë", "È", "Ê");
        $strEmail = str_replace($e, "e", $strEmail);
        $i = array("í", "ï", "ì", "î", "Í", "Ï", "Ì", "Î");
        $strEmail = str_replace($i, "i", $strEmail);
        $o = array("ó", "ö", "ò", "õ", "ô", "Ó", "Ö", "Ò", "Õ", "Ô");
        $strEmail = str_replace($o, "o", $strEmail);
        $u = array("ú", "ü", "ù", "û", "Ú", "Ü", "Ù", "Û");
        $strEmail = str_replace($u, "u", $strEmail);
        $c = array("ç", "Ç");
        $strEmail = str_replace($c, "c", $strEmail);
        $n = array("ñ", "Ñ");
        $strEmail = str_replace($n, "n", $strEmail);
        $strEmail = trim($strEmail);
        return $strEmail;
    }

    public static function curPageURLSemParametro() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        } $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        $url = explode("?", $pageURL);
        return $url[0];
    }

    public static function getAreaUrl() {
        $strArea = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $arrArea1 = explode("?", $strArea);
        $arrArea = explode("/", $arrArea1[0]);
        $arrTratado = array();
        foreach ($arrArea as $strArea) {
            if (isset($strArea) && trim($strArea) != "") {
                $arrTratado[] = $strArea;
            }
        }
        return $arrTratado;
    }

    public static function getBairroUrl() {
        $arrUrl = self::getAreaUrl();
        $bairro = null;
        for ($z = 0; $z < count($arrUrl); $z++) {
            if ($arrUrl[$z] == "cidades") {
                $bairro = $arrUrl[$z + 2];
            }
        }
        return $bairro;
    }

    public static function getCidadeUrl() {
        $arrUrl = self::getAreaUrl();
        $cidade = null;
        for ($z = 0; $z < count($arrUrl); $z++) {
            if ($arrUrl[$z] == "cidades") {
                $cidade = $arrUrl[$z + 1];
            }
        }
        return $cidade;
    }

    public static function validaUrl($url) {
        $result = false;
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    public static function getUrlAtualDoNavegador() {
        $server = $_SERVER['SERVER_NAME'];
        $endereco = $_SERVER ['REQUEST_URI'];
        $url = "http://" . $server . $endereco;
        return $url;
    }

}

?>