<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "DireitosAdmin.class.php");
require_once(FWK_DAO . "LogDAO.class.php");

class CrudLogGrupoUsuario extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlGrid(FWK_XML_CRUD . "gridLogGrupoUsuario.xml");
        self::setStringClass("" . __CLASS__ . "");
        switch ($get['a']) {
            case "exibeLog":
                self::exibeLog($get["id"]);
                break;
            case "lista":
            default:
                self::listDados($get, $post);
                break;
        }
    }

    private function exibeLog($id) {
        $stringAnt = "";
        $stringNovo = "";
        $arrLog = self::getObjLog()->getDadosLogGruposUsuario($id);

        if ($arrLog["texto_log"]) {
            $arrParseTexto = get_object_vars(json_decode($arrLog["texto_log"]));
        }
        foreach ($arrParseTexto["dados_anteriores"] as $dAnt) {
            $direitosAnt.="<br />" . self::getObjLog()->getNomeGrupoById($dAnt);
        }
        foreach ($arrParseTexto["dados_novos"] as $dAtu) {
            $direitosAtu.="<br />" . self::getObjLog()->getNomeGrupoById($dAtu);
        }
        $stringAnt .="<b>Grupos</b>:<br /> " . $direitosAnt;
        $stringNovo .="<b>Grupos</b>:<br /> " . $direitosAtu;
        //self::debuga($stringAnt,$stringNovo);




        self::getObjSmarty()->assign("ARRLOG", $arrLog);
        self::getObjSmarty()->assign("DADOS_ANT", $stringAnt);
        self::getObjSmarty()->assign("DADOS_NOVO", $stringNovo);
        self::getObjSmarty()->assign("TITULO_FORMS", "Log de GrupoUsuarios de GrupoUsuario");
        self::getObjSmarty()->assign("BTN_CANCELAR", "<a class='button' style='cursor:pointer;' onclick=\"return vaiPara('?c=" . self::getObjCrypt()->cryptData("CrudLogGrupoUsuario") . "');\" name=\"Voltar\">Voltar</a>");
        self::getObjHttp()->escreEm("CORPO", FWK_HTML_VIEWS . "relLogDireitos.tpl");
    }

    private function getObjLog() {
        if ($this->getObjLog == null) {
            $this->getObjLog = new LogDAO();
        }
        return $this->getObjLog;
    }

}

?>