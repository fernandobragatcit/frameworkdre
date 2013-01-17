<?php

require ("confs_DRE.php");
require_once (FWK_CONTROL . "ControlCSS.class.php");
require_once (FWK_CONTROL . "ControlJS.class.php");
require_once (FWK_CONTROL . "ControlHttp.class.php");
require_once (FWK_CONTROL . "ControlSessao.class.php");
require_once (FWK_CONTROL . "ControlSmarty.class.php");
require_once (FWK_CONTROL . "ControlLogs.class.php");
require_once (FWK_CONTROL . "ControlConfiguracoes.class.php");
require_once (FWK_CONTROL . "ControlFactory.class.php");

require_once (FWK_VIEW . "ViewMenu.class.php");

require_once(FWK_UTIL . "FormataParametros.class.php");
require_once(FWK_UTIL . "FormataLink.class.php");
require_once(FWK_UTIL . "DadosAcessoUsuario.class.php");

require_once (FWK_EXCEPTION . "HtmlException.class.php");
require_once (FWK_EXCEPTION . "XMLException.class.php");
include_once (FWK_EXCEPTION . "FactoryException.class.php");


require_once(FWK_DAO . "TemasDAO.class.php");

/**
 * Classe principal do sistema, manipuladora das demais e otimmizadora de processos.
 *
 * @author André Coura
 * @since 2.0 - 02/04/2010
 */
class Main {

    private $objSmarty;
    private $objHttp;
    private $objCtrlJs;
    private $objCtrlCss;
    private $objCtrlSess;
    private $objCtrlLogs;
    private $objConfigPasta;
    private $objAdminPage = null;
    private $cssMenu;
    private $strEstiloAdmin;
    private $strTplFormLogin;
    private $strEstruturaAdmin;
    private $strEstruturaLogin;
    private $strRodapeLogin;
    private $pastaClassesView;
    private $pagAdminPage;
    private $strXmlConfigPasta;
    private $strTplMenu;
    private $strTplEstruturaRecuperaSenha;
    private $strTplEstMenu;
    private $objCrypt;
    private $objCtrlConfigs;
    private $strAssinMenu;

    public function __construct() {
        self::criaSessao();
        //seta na sessão os navegadores homologados para o portal.
        $arrNavs = explode(";", NAVEGADORES_HOMOLOGADOS);
        $arrVersionNavs = explode(";", VERSAO_NAVEGADORES_HOMOLOGADOS);
        foreach ($arrNavs as $kn => $nn) {
            $_SESSION["navegadores"][$kn] = array("navegador" => $nn, "menorVersao" => intval($arrVersionNavs[$kn]));
        }
    }

    public function getCtrlConfigs() {
        if ($this->objCtrlConfigs == null)
            $this->objCtrlConfigs = new ControlConfiguracoes();
        return $this->objCtrlConfigs;
    }

    public function getObjAdminPage() {
        if ($this->objAdminPage == null)
            $this->objAdminPage = new ViewAdminPage("");
        return $this->objAdminPage;
    }

    public function getObjCtrlLogs() {
        if ($this->objCtrlLogs == null)
            $this->objCtrlLogs = new ControlLogs();
        return $this->objCtrlLogs;
    }

    public function getObjCtrlCss() {
        if ($this->objCtrlCss == null)
            $this->objCtrlCss = ControlCSS::getCSS();
        return $this->objCtrlCss;
    }

    public function getObjCtrlJs() {
        if ($this->objCtrlJs == null)
            $this->objCtrlJs = ControlJS::getJS();
        return $this->objCtrlJs;
    }

    public function getObjSmarty() {
        if ($this->objSmarty == null)
            $this->objSmarty = ControlSmarty::getSmarty();
        return $this->objSmarty;
    }

    public function getObjHttp() {
        if ($this->objHttp == null)
            $this->objHttp = new ControlHttp($this->getObjSmarty());
        return $this->objHttp;
    }

    public function setCaminhoLogs($strPastaLogs = null) {
        if ($strPastaLogs == null)
            $strPastaLogs = PASTA_LOG;
        self::getObjCtrlLogs()->setPastaLogs($strPastaLogs);
    }

    public function setConfigFile($strXml) {
        $this->strXmlConfigPasta = $strXml;
    }

    public function getConfigFile() {
        if ($this->strXmlConfigPasta == null)
            $this->strXmlConfigPasta = CONFIG_FILE;
        return $this->strXmlConfigPasta;
    }

    public function registraLogs($bool = true) {
        if ($bool) {
            $this->getObjCtrlLogs()->registraLogs();
        }
    }

    public function setTituloPag($strPag) {
        $this->getObjSmarty()->assign("TITULO_PAG", $strPag);
    }

    public function setDescricaoPag($strPag) {
        $this->getObjSmarty()->assign("DESCRIPTION_PAG", $strPag);
    }

    public function setKeyWordsPag($strPag) {
        $this->getObjSmarty()->assign("KEYWORDS_PAG", $strPag);
    }

    public function regTplEm($tag, $tpl) {
        $this->getObjSmarty()->assign($tag, $this->getObjSmarty()->fetch($tpl));
    }

    public function escreveHtmlEm($tag, $texto) {
        $this->getObjSmarty()->assign($tag, $texto);
    }

    public function addCss($strCss) {
        $this->getObjCtrlCss()->addCss($strCss);
    }

    public function setEstiloAdmin($strEstiloAdmin) {
        $this->strEstiloAdmin = $strEstiloAdmin;
    }

    public function getEstiloAdmin() {
        return $this->strEstiloAdmin;
    }

    public function addJs($strJs) {
        ControlJS::getJS()->addJs($strJs);
    }

    public function setTxtJs($strJs) {
        ControlJS::getJS()->addScriptTxt($strJs);
    }

    public function registraTag($strNomeTag, $strValorTag) {
        $this->getObjSmarty()->assign($strNomeTag, $strValorTag);
    }

    /**
     * Método para definir a assinatura do menu
     */
    public function getAssinaturaMenu() {
        if ($this->strAssinMenu == null) {
            $strAssin = self::getCtrlConfigs()->getAssignMenu();
            if (isset($strAssin) && $strAssin != "")
                $this->strAssinMenu = $strAssin;
            else
                $this->strAssinMenu = "MENU";
        }
        return $this->strAssinMenu;
    }

    public function setAssinaturaMenu($strAssina) {
        $this->strAssinMenu = $strAssina;
    }

    /**
     * Método responsável por criar a estrutura de todo o sistema
     *
     * @author Andre
     * @since 5.1 - 17/07/2010
     */
    public function makeScreen(&$arrGet, &$arrPost, $srcClass = "", &$arrFile = array()) {
        try {
//			self::registraLogs(); //- com problemas
            //habilitar PopUp
            self::getPopUp();
            self::setTituloPag(self::getTituloPagina());
            $objFormatParam = new FormataParametros();
            $objFormatParam->setParametros($arrGet);
            self::registraTagsBasicas($objFormatParam->getParametros(), $arrPost);
            self::regsMenu();
            self::verificaShortUrl();
            self::getObjSmarty()->assign("AVISO_NAVEGADOR", DadosAcessoUsuario::validaNavegador());
            $this->getObjHttp()->escreEm(self::getAssinaturaMenu(), self::getTplEstruturaMenu());
            $objFactoryTela = new ControlFactory($srcClass);
            $objFactoryTela->setDirClassDefault(self::getPastaClassesView());
            $objFactoryTela->setArquivoConfig(self::getConfigFile());
            $objFactoryTela->buildMotor($objFormatParam->getParametros(), $arrPost, $arrFile);
            $objFactored = $objFactoryTela->getObjClassFactory();
            if ($objFactored->getWireFrame() != null && $objFactored->getWireFrame() != "")
                $strWireFrame = $objFactored->getWireFrame();
            else
                $strWireFrame = $objFactoryTela->getWireFrame($objFormatParam->getParametros());
            self::showTela($strWireFrame);
        } catch (Exception $e) {
            die($e->__toString());
        }
    }

    public function showTela($strCorpo, $strEstrutura = "index.tpl") {
        if (!is_file($this->getObjSmarty()->template_dir . $strCorpo) && !is_file($strCorpo))
            throw new HtmlException("Não foi possível encontrar o arquivo '" . $strCorpo . "' para a criação da tela.");
        if (!is_file($this->getObjSmarty()->template_dir . $strEstrutura) && !is_file($strEstrutura))
            throw new HtmlException("Não foi possível encontrar o arquivo '" . $strEstrutura . "' para a criação da tela.");
        $this->getObjHttp()->exibeTela($strCorpo);
        //self::registraTagsBasicas();
        $this->getObjSmarty()->display($strEstrutura);
    }

    public function exibeAdminPage(& $arrGet, & $arrPost, & $arrFile) {
        self::getObjAdminPage()->setObjJs($this->getObjCtrlJs());
        self::getObjAdminPage()->setObjCss($this->getObjCtrlCss());
        self::getObjAdminPage()->setTplFormLogin(self::getTplFormLogin());
        self::getObjAdminPage()->setEstruturaAdmin(self::getEstruturaAdmin());
        self::getObjAdminPage()->setEstruturaLogin(self::getEstruturaLogin());
        self::getObjAdminPage()->setRodapeLogin(self::getRodapeLogin());
        self::getObjAdminPage()->setAdminPageViewFolder(self::getPastaClassesViewe());
        self::getObjAdminPage()->setAdminPageHome(self::getPagAdminPage());
        self::getObjAdminPage()->executa($arrGet, $arrPost, $arrFile);
    }

    public function getPastaClassesView() {
        if ($this->pastaClassesView == null)
            $this->pastaClassesView = CLASSES_VIEW;
        return $this->pastaClassesView;
    }

    public function setPastaClassesView($folderAdmin) {
        $this->pastaClassesView = $folderAdmin;
    }

    public function getPagAdminPage() {
        return $this->pagAdminPage;
    }

    public function setPagAdminPage($pagAdmin) {
        $this->pagAdminPage = $pagAdmin;
    }

    public function getEstruturaAdmin() {
        return $this->strEstruturaAdmin;
    }

    public function setEstruturaAdmin($strEstruturaAdmin) {
        $this->strEstruturaAdmin = $strEstruturaAdmin;
    }

    public function getEstruturaLogin() {
        return $this->strEstruturaLogin;
    }

    public function setEstruturaLogin($strEstruturaLogin) {
        $this->strEstruturaLogin = $strEstruturaLogin;
    }

    public function setCabecalho($strCabecalho) {
        $this->getObjAdminPage()->setCabecalho($strCabecalho);
    }

    public function setRodape($strRodape) {
        $this->getObjAdminPage()->setRodape($strRodape);
    }

    public function setTplMenu($strMenu) {
        $this->getObjAdminPage()->setTplMenu($strMenu);
    }

    /**
     * Método que serve para criar uma sessão para o usuário caso ela ainda não tenha sido criada
     *
     * @author André Coura
     * @since 1.0 - 03/07/2008
     */
    private function criaSessao() {
        if ($this->objCtrlSess == null)
            $this->objCtrlSess = new ControlSessao();
        if (!$this->objCtrlSess->verifSessao(SESSAO_FWK_DRE))
            $this->objCtrlSess->setVisitante(SESSAO_FWK_DRE);
    }

    public function getObjSessao() {
        if ($this->objCtrlSess == null) {
            self::criaSessao();
        }
        return $this->objCtrlSess->getObjSessao(SESSAO_FWK_DRE);
    }

    public function setCssMenu($css) {
        $this->getObjAdminPage()->setCssMenu($css);
    }

    public function setCssGrid($css) {
        $this->getObjAdminPage()->setCssGrid($css);
    }

    private function regsMenu() {
        $objMenu = new ViewMenu();
        $objMenu->setObjCss(self::getObjCtrlCss());
        $objMenu->setObjJs(self::getObjCtrlJs());
        $objMenu->setCssMenu(self::getCssMenuGlobal());
        $objMenu->setTplMenu(self::getTplMenuGlobal());
        $objUsuario = self::getObjSessao();
        self::getObjSmarty()->assign("LINK_LOGIN", "?c=" . self::getObjCrypt()->cryptData("login"));
        self::getObjSmarty()->assign("CAD_USUARIO", "?c=" . self::getObjCrypt()->cryptData("ViewCadUsuarios"));
        if ($objUsuario->getNomeUsuario() != "Visitante") {
            self::getObjSmarty()->assign("MENU_GLOBAL", $objMenu->geraMenu($objUsuario->getGrupoUsuario(), $objUsuario->getIdUsuario()));
            self::getObjSmarty()->assign("ARR_MENU", $objMenu->pegaMenu($objUsuario->getGrupoUsuario(), $objUsuario->getIdUsuario()));
        }
    }

    /**
     * Método para setar o tpl do Menu
     *
     * @author André Coura
     * @since 1.0 - 18/04/2010
     * @param String $strTplMenu
     */
    public function setTplMenuGlobal($strTplMenu) {
        $this->strTplMenu = $strTplMenu;
    }

    /**
     * Método para recuperar o tpl do Menu
     *
     * @author André Coura
     * @since 1.0 - 18/04/2010
     */
    public function getTplMenuGlobal() {
        return $this->strTplMenu;
    }

    private function getTplEstruturaMenu() {
        try {
            $xmlMenu = self::getCtrlConfigs()->getMenuByXml();
            if (isset($xmlMenu) && $xmlMenu != "") {
                $this->strTplEstMenu = DEPOSITO_TPLS . $xmlMenu;
            } else {
                $this->strTplEstMenu = "menu.tpl";
            }
            return $this->strTplEstMenu;
        } catch (XMLException $e) {
            die($e->getMensagem());
        }
    }

    /**
     * Método para setar o css do Menu
     *
     * @author André Coura
     * @since 1.0 - 18/04/2010
     * @param String $cssMenu
     */
    public function setCssMenuGlobal($cssMenu) {
        $this->cssMenu = $cssMenu;
    }

    /**
     * Método para recuperar o css do Menu
     *
     * @author André Coura
     * @since 1.0 - 18/04/2010
     */
    public function getCssMenuGlobal() {
        return $this->cssMenu;
    }

    /**
     * Busca da tela de login para a área
     *
     * @author André Coura
     * @since 1.0 - 03/07/2008
     */
    private function getLoginPage($get, $post) {
        switch (true) {
            case (self::getObjCrypt()->decryptData($get["c"]) == "Login"):
                try {
                    $objCtrlLogin = new ControlLogin();
                    $objCtrlLogin->verificaUsuario($post);
                    self::getObjHttp()->irPag("c=" . self::getObjCrypt()->cryptData(self::getAdminPageHome()));
                } catch (Exception $e) {
                    self::getObjSmarty()->assign("MENS_ERRO", $e->__toString());
                    self::telaLogin();
                }
                break;
            default:
                self::telaLogin();
                break;
        }
    }

    /**
     * Exibe a tela de login na tela
     *
     * @author André Coura
     * @since 1.0 - 05/07/2008
     */
    private function telaLogin() {
        self::getObjSmarty()->assign("POST_LOGIN", "?c=" . self::getObjCrypt()->cryptData("Login"));
        $this->getObjJs()->addJs(FWK_JS . "validaLogin.js");
        try {
            self::getObjHttp()->escreEm("RODAPE_LOGIN_ADMIN", self::getRodapeLogin());
            self::getObjHttp()->escreEm("CORPO", self::getTplFormLogin());
        } catch (HtmlException $e) {
            die("ViewAdminPage()->telaLogin(): " . self::getTplFormLogin() . $e->__toString());
        }
    }

    /**
     * Exibe a tela de recuperar senha na tela
     *
     * @author Matheus Vieira
     * @since 1.0 - 05/11/2010
     */
    private function telaRecuperaSenha() {
        //self::getObjSmarty()->assign("POST_LOGIN","?c=".self::getObjCrypt()->cryptData("Login"));
        //$this->getObjJs()->addJs(FWK_JS."validaLogin.js");
        try {
            //self::getObjHttp()->escreEm("RODAPE_LOGIN_ADMIN",self::getRodapeRecuperaSenha());
            self::getObjHttp()->escreEm("CORPO", self::getTplRecuperaSenha());
        } catch (HtmlException $e) {
            die("ViewAdminPage()->telaLogin(): " . self::getTplRecuperaSenha() . $e->__toString());
        }
    }

    /**
     * Realiza a limpeza das short urls.
     *
     * @author Matheus Vieira
     * @since 1.0 - 15/12/2011
     */
    private function verificaShortUrl() {
        FormataLink::verificaMiniUrl("fwk_link_encurtado");
    }

    /**
     * Busca o tpl referente ao
     */
    public function getTplFormLogin() {
        if ($this->strTplFormLogin == "")
            $this->strTplFormLogin = FWK_HTML_ADMINPAGE . "AdminLogin.tpl";
        return $this->strTplFormLogin;
    }

    public function setTplFormLogin($strTplFormLogin) {
        $this->strTplFormLogin = $strTplFormLogin;
    }

    public function setRodapeLogin($strRodapeLogin) {
        $this->strRodapeLogin = $strRodapeLogin;
    }

    public function getRodapeLogin() {
        if ($this->strRodapeLogin == null) {
            if (self::getRodape() != null)
                $this->strRodapeLogin = self::getRodape();
            else
                $this->strRodapeLogin = FWK_HTML_ADMINPAGE . "rodape.tpl";
        }
        return $this->strRodapeLogin;
    }

    private function getObjCrypt() {
        if ($this->objCrypt == null)
            $this->objCrypt = new Cryptografia();
        return $this->objCrypt;
    }

    private function getTemasUsuario() {
        $objTema = new TemasDAO();
        $arrDadosTema = $objTema->getTemaUsuario(self::getObjSessao()->getIdUsuario());
        self::getObjSmarty()->assign("CABECALHO_TEMA", $arrDadosTema["cabecalho_css"]);
        self::getObjSmarty()->assign("RODAPE_TEMA", $arrDadosTema["rodape_css"]);
    }

    private function registraTagsBasicas($get, $post) {
        $_SESSION["ACAO_FORM"]=$get["a"];
        $nParams = array();
        //define o tema do usuário logado
        self::getTemasUsuario();
        if (isset($get) && $get != null)
            $nParams = array_merge($nParams, $get);
        if ($post["param"] != null)
            $nParams = array_merge($nParams, unserialize(self::getObjCrypt()->decryptData($post["param"])));
        self::getObjSmarty()->assign("PARAM_LOGIN", self::getObjCrypt()->cryptData(serialize($nParams)));
        self::getObjSmarty()->assign("POST_LOGIN", "?c=" . self::getObjCrypt()->cryptData("Login&a=Login"));
        self::getObjSmarty()->assign("FORM_REC_SENHA", "?c=" . self::getObjCrypt()->cryptData("Login&a=FormRecuperaSenha"));
        self::getObjSmarty()->assign("POST_REC_SENHA", "?c=" . self::getObjCrypt()->cryptData("Login&a=PostRecuperaSenha"));
        self::getObjSmarty()->assign("URL_SITE", self::getCtrlConfigs()->getUrlSite());
        self::getObjSmarty()->assign("URL_IMAGENS", URL_DEP_IMGS);
        self::getObjSmarty()->assign("URL_BDIMG", URL_BDIMG);

        $ArrNomeUsr = explode(" ", self::getObjSessao()->getNomeUsuario());
        self::getObjSmarty()->assign("NOME_USER_LOG", $ArrNomeUsr[0]);
        self::getObjSmarty()->assign("URL_LOGOUT", "?c=" . self::getObjCrypt()->cryptData("Login&a=Logout"));
        self::getObjSmarty()->assign("URL_PERFIL", "?c=" . self::getObjCrypt()->cryptData("ViewPerfil"));
        self::getObjSmarty()->assign("URL_PERFIL_MEUS_FAVORITOS", "?c=" . self::getObjCrypt()->cryptData("ViewFavoritosGeral"));
        self::getObjSmarty()->assign("FORM_ALT_SENHA", "?c=" . self::getObjCrypt()->cryptData("ViewPerfil&a=formAltSenha"));
        self::getObjSmarty()->assign("RET_SERVIDOR", RET_SERVIDOR);
        self::getObjSmarty()->assign("SERVIDOR_FIS", SERVIDOR_FISICO);
    }

    private function getTituloPagina() {
        return strtoupper(FormataString::getPastaDoCaminho($_SERVER['REQUEST_URI']));
    }

    private function getPopUp() {
        //FormataString::debuga($_COOKIE);
        if ($_COOKIE["popup"] != "false") {
            self::regTplEm("POPUP", PASTA_TPLS . "popup.tpl");
        }
    }

}

?>