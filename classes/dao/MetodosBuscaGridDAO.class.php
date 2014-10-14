<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class MetodosBuscaGridDAO extends AbsModelDao {

    public function getNomeUsuarioById($id = null) {
        $strQuery = "SELECT
					usu.nome_usuario  
				FROM
					fwk_usuario usu 
                               	WHERE
					usu.id_usuario=" . $id;
        //self::debuga($strQuery);
        return end(ControlDb::getRow($strQuery, 0));
    }

    public function getNomeDireito($id=null) {
        $strQuery = "SELECT nome_direito 
					FROM fwk_direitos 
					WHERE id_direitos = '" . $id . "'";

        return end(ControlDb::getRow($strQuery, 0));
    }
    public function getNomeGrupoById($id = null) {
        $strQuery = "SELECT
					nome_grupo  
				FROM
					fwk_grupo  
                               	WHERE
					id_grupo=" . $id;
        return end(ControlDb::getRow($strQuery, 0));
    }


}

?>