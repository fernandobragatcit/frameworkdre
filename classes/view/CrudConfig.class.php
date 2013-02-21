<?php

require(PASTA_MODULOS . "formularios/configFormularios.php");
require_once(FORMS_MODEL . "AbsModelForms.class.php");
require_once(FWK_DAO . "SubDireitosUsuarioDAO.class.php");
require_once(FWK_DAO . "SubDireitosGrupoDAO.class.php");

class CrudConfig extends AbsModelForms {

    private $obFoto;

    public function executa($get, $post, $file) {
        switch ($get["a"]) {
            default:
                //self::attrSubPermissoesTodosColaboradores();
                //self::limparSubPermissoesTodosColaboradores();
                //self::attrSubPermissoesPorGrupo();
                //self::limparSubPermissoesPorGrupo();
                break;
        }
    }

    private function limparSubPermissoesTodosColaboradores() {
        $arrCols = self::getArrayColaboradores();
        foreach ($arrCols as $usu) {
            $direitos = array();
            $direitos = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirUsuarios()->getDireitosFormUsuarioByUsuario($usu));
            foreach ($direitos as $dir) {
                self::getObjSubDirUsuarios()->limpaTabelaPermissoesUsuarios($dir, $usu);
            }
        }
    }

    private function attrSubPermissoesTodosColaboradores() {
        $arrCols = self::getArrayColaboradores();
        foreach ($arrCols as $usu) {
            $direitos = array();
            $direitos = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirUsuarios()->getDireitosFormUsuarioByUsuario($usu));
            foreach ($direitos as $dir) {
                self::getObjSubDirUsuarios()->limpaTabelaPermissoesUsuarios($dir, $usu);
                self::getObjSubDirUsuarios()->inserirPermissoesUsuarios($dir, $usu, CADASTRAR);
                self::getObjSubDirUsuarios()->inserirPermissoesUsuarios($dir, $usu, ALTERAR);
                self::getObjSubDirUsuarios()->inserirPermissoesUsuarios($dir, $usu, EXCLUIR);
            }
        }
    }

    private function limparSubPermissoesPorGrupo() {
        $arrGp = self::getArrayGrupos();
        foreach ($arrGp as $gp) {
            $direitos = array();
            $direitos = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirGrupo()->getDireitosByGrupo($gp));
            foreach ($direitos as $dir) {
                self::getObjSubDirGrupo()->limpaTabelaPermissoesGrupo($dir, $gp);
            }
        }
    }

    private function attrSubPermissoesPorGrupo() {
        $arrGp = self::getArrayGrupos();
        foreach ($arrGp as $gp) {
            $direitos = array();
            $direitos = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirGrupo()->getDireitosByGrupo($gp));
            foreach ($direitos as $dir) {
                self::getObjSubDirGrupo()->limpaTabelaPermissoesGrupo($dir, $gp);
                self::getObjSubDirGrupo()->inserirPermissoesGrupo($dir, $gp, CADASTRAR);
                self::getObjSubDirGrupo()->inserirPermissoesGrupo($dir, $gp, ALTERAR);
                self::getObjSubDirGrupo()->inserirPermissoesGrupo($dir, $gp, EXCLUIR);
            }
        }
    }

    private function getArrayColaboradores() {
        $arrCol = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirUsuarios()->getUsuariosColaboradores());
        return $arrCol;
    }

    private function getArrayGrupos() {
        $arrGp = FormataPost::colocaValoresEmSequenciaAposUmSelect(self::getObjSubDirGrupo()->getTodosGrupos());
        return $arrGp;
    }

    private function getObjSubDirUsuarios() {
        if ($this->obDir == null) {
            $this->obDir = new SubDireitosUsuarioDAO();
        }
        return $this->obDir;
    }

    private function getObjSubDirGrupo() {
        if ($this->obDirGp == null) {
            $this->obDirGp = new SubDireitosGrupoDAO();
        }
        return $this->obDirGp;
    }

}

?>