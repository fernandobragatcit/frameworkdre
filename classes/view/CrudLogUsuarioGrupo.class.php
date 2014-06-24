<?php

require_once(FWK_MODEL . "AbsCruds.class.php");
require_once(FWK_MODEL . "DireitosAdmin.class.php");
require_once(FWK_DAO . "LogDAO.class.php");

class CrudLogUsuarioGrupo extends AbsCruds {

    public function executa($get, $post, $file) {
        self::setXmlGrid(FWK_XML_CRUD . "gridLogUsuarioGrupo.xml");
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
        $arrLog = self::getObjLog()->getDadosLogUsuariosGrupo($id);

        if ($arrLog["texto_log"]) {
            $arrParseTexto = get_object_vars(json_decode($arrLog["texto_log"]));
        }
        foreach ($arrParseTexto["dados_anteriores"] as $dAnt) {
            $direitosAnt.="<br />" . self::getObjLog()->getNomeUsuarioById($dAnt);
        }
        foreach ($arrParseTexto["dados_novos"] as $dAtu) {
            $direitosAtu.="<br />" . self::getObjLog()->getNomeUsuarioById($dAtu);
        }
        $stringAnt .="<b>Usuários</b>:<br /> " . $direitosAnt;
        $stringNovo .="<b>Usuários</b>:<br /> " . $direitosAtu;
        //self::debuga($stringAnt,$stringNovo);




        self::getObjSmarty()->assign("ARRLOG", $arrLog);
        self::getObjSmarty()->assign("DADOS_ANT", $stringAnt);
        self::getObjSmarty()->assign("DADOS_NOVO", $stringNovo);
        self::getObjSmarty()->assign("TITULO_FORMS", "Log de UsuarioGrupos de UsuarioGrupo");
        self::getObjSmarty()->assign("BTN_CANCELAR", "<a class='button' style='cursor:pointer;' onclick=\"return vaiPara('?c=" . self::getObjCrypt()->cryptData("CrudLogUsuarioGrupo") . "');\" name=\"Voltar\">Voltar</a>");
        self::getObjHttp()->escreEm("CORPO", FWK_HTML_VIEWS . "relLogDireitosGrupo.tpl");
    }

    private function getObjLog() {
        if ($this->getObjLog == null) {
            $this->getObjLog = new LogDAO();
        }
        return $this->getObjLog;
    }

}

?>