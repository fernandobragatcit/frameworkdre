<?php

class FormataActions {

    private $objCrypt;

    public function __construct() {
        $this->objCrypt = new Cryptografia();
    }

    private function getObjCrypt() {
        if ($this->objCrypt == null)
            $this->objCrypt = new Cryptografia();
        return $this->objCrypt;
    }

    /**
     * Método default de criação de actions para o grid
     *
     * @author André Coura
     * @since 2.0 - 29/07/2010
     * @param String data: o valor do campo a ser transformado
     * @param String value: o valor do parametro a ser colocado
     * @param String classe: a classe em questão para o link
     * @param String $label: label do link
     * @param String $tipo: tipo de parametro a ser setado
     * @param String $strCategoria: caso seja de alguma categoria, é setado aqui
     * @return String $strLink: o html do link criado //$strLink = "<a href=\"javascript:void(vaiPara('?".$tipo."=".$link."'))\">".$label."</a>";
     * 
     * 
     * @author Matheus Vieira
     * @since 2.1 - 15/12/2011
     * @return String $strLink: o html do link criado direto no href, desta forma o botão voltar do navegador funciona.
     */
    public function gridAction($data, $value, $classe, $label, $tipo = "c", $strCategoria = "", $param1 = "", $valParam1 = "", $param2 = "", $valParam2 = "", $xmlParam = "", $pag = "", $filtros = "", $buscaGrid = "") {
        $strParam = "";
        $strParam3 = "";
        if ($param1 != "" && $valParam1 != "")
            $strParam = "&" . $param1 . "=" . $valParam1;
        if ($param2 != "" && $valParam2 != "")
            $strParam2 = "&" . $param2 . "=" . $valParam2;
        if ($pag != "")
            $strParam3 .="&pag=" . $pag;
        if ($filtros != "")
            $strParam3 .="&filtros=" . $filtros;
        if ($buscaGrid != "")
            $strParam3 .=$buscaGrid;

        $link = self::getObjCrypt()->cryptData(($strCategoria != "" ? $strCategoria . "&f=" : "") . $classe . "&" . $value . "&id=" . $data . $strParam . $strParam2 . $xmlParam . $strParam3);
        $strLink = "<a href=\"?" . $tipo . "=" . $link . "\">" . $label . "</a>";
        return $strLink;
    }

    /**
     * Método default de criação de confirms para o grid
     *
     * @author André Coura
     * @since 2.0 - 29/07/2010
     * @param String data: o valor do campo a ser transformado
     * @param String value: o valor do parametro a ser colocado
     * @param String classe: a classe em questão para o link
     * @param String $label: label do link
     * @param String $tipo: tipo de parametro a ser setado
     * @param String $strCategoria: caso seja de alguma categoria, é setado aqui
     * @return String $strLink: o html do link criado
     */
    public function gridConfirm($data, $value, $classe, $label, $mensConfirm, $tipo = "c", $strCategoria = "", $param1 = "", $valParam1 = "", $param2 = "", $valParam2 = "") {
        $strParam = "";
        if ($param1 != "" && $valParam1 != "")
            $strParam = "&" . $param1 . "=" . $valParam1;
        if ($param2 != "" && $valParam2 != "")
            $strParam2 = "&" . $param2 . "=" . $valParam2;

        $link = self::getObjCrypt()->cryptData(($strCategoria != "" ? $strCategoria . "&f=" : "") . $classe . "&" . $value . "&id=" . $data . $strParam . $strParam2);
        $strLink = "<a href=\"javascript:void(confirmIr('?" . $tipo . "=" . $link . "','" . $mensConfirm . "'))\">" . $label . "</a>";
        return $strLink;
    }

    /**
     * Método de criação de impressao para o grid
     *
     * @author Matheus Vieira
     * @since 1.0 - 31/05/2011
     * @param String data: o valor do campo a ser transformado
     * @param String value: o valor do parametro a ser colocado
     * @param String classe: a classe em questão para o link
     * @param String $label: label do link
     * @param String $tipo: tipo de parametro a ser setado
     * @param String $strCategoria: caso seja de alguma categoria, é setado aqui
     * @return String $strLink: o html do link criado
     */
    public function gridImprime($data, $value, $classe, $label, $tipo = "c", $strCategoria = "", $param1 = "", $valParam1 = "") {
        $strParam = "";
        if ($param1 != "" && $valParam1 != "")
            $strParam = "&" . $param1 . "=" . $valParam1;
        $link = (($strCategoria != "" ? $strCategoria . "&f=" : "") . $classe . "&" . $value . "&id=" . $data . $strParam);
        $value = end(explode("a=", $value));
        $link2 = self::getObjCrypt()->cryptData(SERVIDOR_FISICO . "modulos/inventario/classes/view/&classe=" . $classe . "&metodo=" . $value . "&id=" . $data);


        $strLink = "<a href=\"javascript:void(imprimir('" . $link2 . "'))\">" . $label . "</a>";
        return $strLink;
    }

    /**
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 - 02/09/2014
     * @return altera memória limite do php para o padrão do sistema informado no arquivo configs.php
     */
    public static function setLimitMemory() {
        $memory_limit_sistema = ControlDb::getCol("SELECT memory_limit FROM fwk_config_sistema WHERE id_config=1");
        if (!empty($memory_limit_sistema["memory_limit"])) {
            ini_set("memory_limit", $memory_limit_sistema);
        }
    }

    /**
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 - 02/09/2014
     * @param valor limite de memória do PHP a ser alterado.
     */
    public static function setValueLimitMemory($memory) {
        ini_set("memory_limit", $memory);
    }

}

?>