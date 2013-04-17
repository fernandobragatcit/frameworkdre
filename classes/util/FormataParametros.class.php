<?php

require_once(FWK_UTIL . "Cryptografia.class.php");

/**
 * Classe para formatar os dados passados por parametro
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 13/07/2008
 */
class FormataParametros {

    private $arrParametros = array();
    private $arrParametrosRequest = array();

    public function __construct($arrParametro = null) {
        if (isset($arrParametro) && is_array($arrParametro))
            self::setParametros($arrParametro);
    }

    public function setParametros($arrParametro) {
        if (is_array($arrParametro) && count($arrParametro) > 0)
            foreach ($arrParametro as $tipo => $strParam) {
                if ($tipo == "buscar") {
                    self::explodeParametros($strParam, $tipo);
                } else {
                    self::explodeParametros(self::getObjCrypt()->decryptData($strParam), $tipo);
                }
            }
    }

    public function getParametros() {
        return $this->arrParametros;
    }

    private function explodeParametros($strParametro, $tipo) {
        $strParametro = $tipo . "=" . $strParametro;
        $arrDados = explode("&", $strParametro);
        //$arrParam = array();
        if (count($arrDados)) {
            foreach ($arrDados as $dados) {
                $arrData2 = explode("=", $dados);
                //$arrParam[$arrData2[0]] = $arrData2[1];
                $this->arrParametros[$arrData2[0]] = $arrData2[1];
            }
            //return $arrParam;
        }
        return null;
    }

    public function setParametrosRequest($arrParametro) {
        if (is_array($arrParametro) && count($arrParametro) > 0)
            foreach ($arrParametro as $tipo => $strParam) {
                self::explodeParametrosRequest($strParam, $tipo);
            }
        unset($this->arrParametrosRequest["ajx"]);
        unset($this->arrParametrosRequest["jsoncallback"]);
        unset($this->arrParametrosRequest["PHPSESSID"]);
    }

    public function getParametrosRequest() {
        return $this->arrParametrosRequest;
    }

    private function explodeParametrosRequest($strParametro, $tipo) {
        $strParametro = $tipo . "=" . $strParametro;
        $arrDados = explode("&", $strParametro);
        if (count($arrDados)) {
            foreach ($arrDados as $dados) {
                $arrData2 = explode("=", $dados);
                $this->arrParametrosRequest[$arrData2[0]] = $arrData2[1];
            }
        }
        return null;
    }

    public function decodificaTokenLoginIntegrado($token) {
        $valor = self::getObjCrypt()->decryptData($token);
        $arrayValores = explode(":", $valor);

        return $arrayValores;
    }
    public function codificaTokenLoginIntegrado($token) {
        $valor = self::getObjCrypt()->decryptData($token);
        $arrayValores = explode(":", $valor);

        return $arrayValores;
    }

    private function getObjCrypt() {
        if ($this->objCrypt == null)
            $this->objCrypt = new Cryptografia();
        return $this->objCrypt;
    }

}

?>