<?php

require_once(FWK_CONTROL . "ControlDB.class.php");
require_once(FWK_UTIL . "Cryptografia.class.php");
require_once(FWK_UTIL . "FormataLink.class.php");
require_once(FWK_UTIL . "FormataPost.class.php");
require_once(FWK_CONTROL . "ControlSmarty.class.php");
require_once(FWK_CONTROL . "ControlGrid.class.php");
require_once(FWK_CONTROL . "ControlForms.class.php");
require_once(FWK_CONTROL . "ControlSessao.class.php");
require_once(FWK_CONTROL . "ControlPost.class.php");
require_once(FWK_CONTROL . "ControlXML.class.php");
require_once(FWK_CONTROL . "ControlJS.class.php");
require_once(FWK_CONTROL . "ControlPastas.class.php");

require(FWK_CONTROL . "ControlReports.class.php");

require_once(FWK_CONTROL . "ControlConfiguracoes.class.php");
require_once(FWK_EXCEPTION . "CrudException.class.php");

require_once (FWK_DAO . "DocUsuario.class.php");
require_once (FWK_DAO . "DocGrupo.class.php");
require_once (FWK_DAO . "GrupoUsuarioDAO.class.php");
require_once (FWK_DAO . "FotosCropDAO.class.php");

abstract class AbsCruds {

    private $objSmarty;
    private $objHttp;
    private $objBanco;
    private $objCtrlJs;
    private $objCtrlCss;
    private $strCaminhoXml;
    private $strXmlForm;
    private $strXmlGrid;
    private $strXmlReport;
    private $objModelClass;
    private $strCssGridCrud;
    private $objGrid;
    private $strCssGrid;
    private $objUserSess;
    private $objCrypt;
    private $strTipo;
    private $strCategoria;
    private $strTipoForm;
    private $objCtrlForm;
    private $strWireFrame;
    private $objDocUsuario;
    private $objDocGrupo;
    private $objCtrlReports;

    public function __construct() {
        
    }

    public function debuga() {
        $arrDados = func_get_args();
        print("<pre>");
        for ($i = 0; $i < count($arrDados); $i++) {
            print_r($arrDados[$i]);
            print"<br />";
        }
        die();
    }

    protected function getObjUsrSessao() {
        if ($this->objUserSess == null) {
            $objCtrlSess = new ControlSessao();
            $this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
        }
        return $this->objUserSess;
    }

    protected function impressao($idCrud, $strNomePdf) {
        header("Content-Disposition: attachment; filename=\"" . $strNomePdf . ".pdf\"");
        readfile(URL_SERVIDOR . "report.php?xml=" . self::getXmlReport() . "&id=" . $idCrud);
    }

    public function getCtrlReports() {
        if ($this->objCtrlReports == null)
            $this->objCtrlReports = new ControlReports();
        return $this->objCtrlReports;
    }

    public function getWireFrame() {
        return $this->strWireFrame;
    }

    public function setWireFrame($strWire) {
        $this->strWireFrame = $strWire;
    }

    protected function setXmlForm($strCaminhoXml) {
        $this->strCaminhoXml = $strCaminhoXml;
    }

    protected function getXmlForm() {
        if ($this->strCaminhoXml == null)
            throw new CrudException("Não foi setado o XML referente ao formulário.");
        return $this->strCaminhoXml;
    }

    /**
     * Método abstrato onde é implementado em todas as classes filhas
     * responsável pela atribuição de funcionalidades do crud
     *
     * @author André Coura.
     * @since 1.0 - 23/08/2009
     */
    abstract public function executa($get, $post, $file);

    protected function getObjSessaoAdmin() {
        if ($this->objUserSess == null) {
            $objCtrlSess = new ControlSessao();
            $this->objUserSess = $objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
        }
        return $this->objUserSess;
    }

    /**
     * Monta o formulário de acordo com o xml passado
     *
     * @author André Coura
     * @since 1.0 - 23/08/2009
     */
    protected function formCadastro() {
        self::getCtrlForm()->setTipoForm(self::getTipoForm());
        self::getCtrlForm()->setTplsFile(ADMIN_TPLS);
        self::getCtrlForm()->setActionForm(self::getStringClass() . "&a=cadastra");
        self::getCtrlForm()->registraForm();
    }

    public function getCtrlForm() {
        if ($this->objCtrlForm == null)
            $this->objCtrlForm = new ControlForm(self::getXmlForm());
        return $this->objCtrlForm;
    }

    public function getTipoForm() {
        return $this->strTipoForm;
    }

    public function setTipoForm($strTipoForm) {
        $this->strTipoForm = $strTipoForm;
    }

    protected function postCadastro($post, $file) {
        try {
            self::getClassModel()->cadastrar(self::getXmlForm(), $post, $file);
            self::vaiPara(self::getStringClass() . "&msg=Item cadastrado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        } catch (UploadException $e2) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e2->getMensagem());
        }
    }

    protected function formAltera($id) {
        $arrDados = self::getClassModel()->buscaCampos($id);
        if (isset($arrDados["id_foto"]) || $arrDados["id_foto"] != "") {
            self::getObjSmarty()->assign("ID_FOTO", $arrDados["id_foto"]);
        }
        if (!isset($arrDados["status"])) {
            $arrDados["status"] = "S";
        }
        self::getClassModel()->setTipoForm(self::getTipoForm());
        self::getClassModel()->preencheForm(self::getXmlForm(), $id, self::getStringClass());
    }

    protected function postAltera($id, $post, $file) {
        try {
            self::getClassModel()->alterar($id, self::getXmlForm(), $post, $file);
            self::vaiPara(self::getStringClass() . "&msg=Item alterado com sucesso!");
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
        }
    }

    protected function anulaCampo($id, $campo) {
        try {
            self::getClassModel()->anulaCampo($id, $campo);
            return true;
        } catch (CrudException $e) {
            return false;
        }
    }

    protected function listDados($get, $post) {
        $intDocsGrupo = self::getDocsGrupo();
        if ($intDocsGrupo > 0) {
            self::getObjGrid()->setVariavelWhere2($intDocsGrupo);
        }
        self::getObjGrid()->setUtf8Decode(true);
        self::getObjGrid()->setXmlGrid(self::getXmlGrid());
        self::getObjGrid()->setArrPost($post);
        self::getObjGrid()->setArrGet($get);
        self::getObjGrid()->showGrid();
    }

    protected function getObjGrid() {
        if ($this->objGrid == null) {
            $this->objGrid = ControlGrid::getGrid();
        }
        return $this->objGrid;
    }

    private $idGrupoUsuario;

    public function setIdGrupoUsuario($intIdRef) {
        $this->idGrupoUsuario = $intIdRef;
    }

    public function getIdGrupoUsuario() {
        return $this->idGrupoUsuario;
    }

    protected function deletaFoto($id) {
        try {
            self::getClassModel()->deletarFoto($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=�?tem deletado com sucesso!");
    }

    protected function deleta($id) {
//		die(self::getStringClass());
        try {
            self::getClassModel()->deletar($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=Item deletado com sucesso!");
    }

    protected function status($id) {
        try {
            self::getClassModel()->alteraStatus($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=Status alterado com sucesso!");
    }

    public function getObjSmarty() {
        if ($this->objSmarty == null)
            $this->objSmarty = ControlSmarty::getSmarty();
        return $this->objSmarty;
    }

    public function getObjHttp() {
        if ($this->objHttp == null)
            $this->objHttp = new ControlHttp(self::getObjSmarty());
        return $this->objHttp;
    }

    public function getObjDB() {
        if ($this->objBanco == null)
            $this->objBanco = ControlDB::getBanco();
        return $this->objBanco;
    }

    /**
     * Método para definir o objeto css ja usado para ser adicionado ao componente
     *
     * @author André Coura
     * @since 1.0 - 05/07/2008
     * @return VOID
     */
    public function setObjCss($objCss) {
        $this->objCtrlCss = $objCss;
    }

    public function getObjCss() {
        if ($this->objCtrlCss == null)
            $this->objCtrlCss = ControlCSS::getCSS();
        return $this->objCtrlCss;
    }

    /**
     * Método para definir o objeto js ja usado para ser adicionado ao componente
     *
     * @author André Coura
     * @since 1.0 - 05/07/2008
     * @return VOID
     */
    public function setObjJs($objJs) {
        $this->objCtrlJs = $objJs;
    }

    public function getObjJs() {
        if ($this->objCtrlJs == null)
            $this->objCtrlJs = ControlJS::getJS();
        return $this->objCtrlJs;
    }

    /**
     * redirecionador de páginas
     */
    protected function vaiPara($strLocal) {
        $strLink = self::getObjCrypt()->cryptData(self::getStrLinkLocal($strLocal));
        header("Location: ?" . FormataLink::definiTipoLink(self::getTipo()) . "=" . $strLink);
    }

    protected function vaiParaConfirmaReserva($strLocal, $pag) {
//		die($strLocal);
        $strLink = self::getObjCrypt()->cryptData(self::getStrLinkLocal($strLocal));
        header("Location: ?" . FormataLink::definiTipoLink(self::getTipo()) . "=" . $strLink . "&pag=" . $pag);
    }

    protected function getLinkCompleto($strLocal) {
        $strLink = self::getObjCrypt()->cryptData(self::getStrLinkLocal($strLocal));
        return FormataLink::definiTipoLink(self::getTipo()) . "=" . $strLink;
    }

    protected function getStrLinkClass() {
        return self::getStrLinkLocal(self::getStringClass());
    }

    protected function getStrLinkLocal($strLocal) {
        return (self::getCategoria() != "" ? self::getCategoria() . "&f=" : "") . $strLocal;
    }

    protected function getObjCrypt() {
        if ($this->objCrypt == null) {
            $this->objCrypt = new Cryptografia();
        }
        return $this->objCrypt;
    }

    protected function vaiParaNormal($strLocal) {
        header("Location: ?m=" . $strLocal);
    }

    protected function setXmlGrid($strXmlGrid) {
        $this->strXmlGrid = $strXmlGrid;
    }

    protected function getXmlGrid() {
        if ($this->strXmlGrid == null)
            throw new CrudException("Não foi setado o XML referente ao grid.");
        return $this->strXmlGrid;
    }

    protected function setXmlReport($strXmlReport) {
        $this->strXmlReport = $strXmlReport;
    }

    protected function getXmlReport() {
        if ($this->strXmlReport == null)
            throw new CrudException("Não foi setado o XML referente ao Report.");
        return $this->strXmlReport;
    }

    protected function setStringClass($strClassCrud) {
        $this->strClassCrud = $strClassCrud;
    }

    protected function getStringClass() {
        if ($this->strClassCrud == null)
            throw new CrudException("Não foi setado a String referente ao nome do Crud em questão.");
        return $this->strClassCrud;
    }

    protected function getClassModel() {
        if ($this->objModelClass == null)
            throw new CrudException("Não foi setado o XML referente ao formulário.");
        return $this->objModelClass;
    }

    protected function setClassModel($objModelClass) {
        $this->objModelClass = $objModelClass;
    }

    protected function alteraStatus($id, $parametro = null) {
        try {
            self::getClassModel()->alteraStatus($id);
        } catch (CrudException $e) {
            self::vaiPara(self::getStringClass() . "&msg=" . $e->getMensagem());
            return;
        }
        self::vaiPara(self::getStringClass() . "&msg=O Status foi foi alterado com sucesso!");
    }

    public function getTipo() {
        if (!isset($this->strTipo) || $this->strTipo == "")
            $this->strTipo = "c";
        return $this->strTipo;
    }

    public function setTipo($tipo) {
        $this->strTipo = $tipo;
    }

    public function getCategoria() {
        if (!isset($this->strCategoria) || $this->strCategoria == "")
            $this->strCategoria = "";
        return $this->strCategoria;
    }

    public function setCategoria($cat) {
        $this->strCategoria = $cat;
    }

    protected function getCtrlConfiguracoes() {
        if ($this->objCtrlConfiguracoes == null)
            $this->objCtrlConfiguracoes = new ControlConfiguracoes();
        return $this->objCtrlConfiguracoes;
    }

    protected function getObjDocUsuario() {
        if ($this->objDocUsuario == null)
            $this->objDocUsuario = new DocUsuario();
        return $this->objDocUsuario;
    }

    protected function getObjDocGrupo() {
        if ($this->objDocGrupo == null)
            $this->objDocGrupo = new DocGrupo();
        return $this->objDocGrupo;
    }
    
    protected function getObjFotoCrop() {
        if ($this->obFotoCrop == null) {
            $this->obFotoCrop = new FotosCropDAO();
        }
        return $this->obFotoCrop;
    }

    /**
     * Método que retorno os documentos que o usuário administra
     * @TODO atualmente ele retorna apenas um documento, futuramente será otimizado.;
     */
    protected function getDocsUsr() {
        $arrDados = self::getObjDocUsuario()->getIdDoc4Usr(self::getObjSessaoAdmin()->getIdUsuario());
        if (is_array($arrDados) && count($arrDados) > 0)
            return (int) $arrDados[0][0];
        return 0;
    }

    /**
     * Método que retorno os documentos que o usuário administra
     * @TODO atualmente ele retorna apenas um documento, futuramente será otimizado.;
     */
    protected function getDocsGrupo() {
        $arrDados = self::getObjDocGrupo()->getIdDoc4Grupo(self::getObjSessaoAdmin()->getIdUsuario());
        if (is_array($arrDados) && count($arrDados) > 0)
            return (int) $arrDados[0][0];
        return 0;
    }

    /**
     * Busca as respectivas Ã¡reas, lembrando que o ultimo elemento do array
     *
     * @author AndrÃ© Coura
     * @since 1.0 - 15/04/2011
     */
    public function getAreaUrl() {
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

    public function getStrUrl($url = "") {
        if ($url == "")
            $arrArea = self::getAreaUrl();
        else
            $arrArea = $url;
        array_shift($arrArea);
        return "http://" . implode("/", $arrArea);
    }

}

?>