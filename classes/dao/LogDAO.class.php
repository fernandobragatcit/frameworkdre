<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");

class LogDAO extends AbsModelDao {

    public function registraLog($tabela, $valores) {
        $campos = array_keys($valores);
        $valores = array_values($valores);
        //self::debuga($campos, $valores);
        $virgula = ",";
        $valor = "";
        $campo = "";
        foreach ($valores as $key => $val) {
            if ($key == count($valores) - 1) {
                $virgula = "";
            }
            $valor.="'" . $val . "'" . $virgula;
        }
        $virgula = ",";
        foreach ($campos as $i => $cam) {
            if ($i == count($campos) - 1) {
                $virgula = "";
            }
            $campo.=$cam . $virgula;
        }
        $valor = str_replace("''", "NULL", $valor);
        $strQuery = "INSERT INTO " . $tabela . " 
            (" . $campo . ") VALUES (" . $valor . ")";

        //self::debuga($strQuery);
        ControlDb::getBanco()->Execute($strQuery);
    }

    public function getDadosNovosCadastros($intPag = 0,$numElementos=5) {
        $intPag = ($intPag > 0) ? (int) $intPag - 1 : $intPag;
        $inicio = ((int) $intPag) * $numElementos;

        $strQuery = "SELECT cli.id_cliente,log.nome_registro as cliente,con.tel1_contato,con.cel1_contato,end.cidade_endereco,DATE_FORMAT(log.data_log, '%d/%m/%Y - %H:%i') as data_log,col_usu.nome_usuario as atendente
					FROM fgv_log_cliente log
                                        LEFT JOIN fwk_usuario usu ON usu.id_usuario=log.id_usuario_cad
                                        LEFT JOIN fgv_cliente cli ON cli.id_usuario=usu.id_usuario
                                        LEFT JOIN fwk_contato con ON con.id_contato=usu.id_contato
                                        LEFT JOIN fwk_endereco end ON end.id_endereco=cli.id_endereco
                                        LEFT JOIN fgv_colaborador col ON cli.colaborador_cliente=col.id_colaborador
                                        LEFT JOIN fwk_usuario col_usu ON col_usu.id_usuario=col.id_usuario
    				WHERE log.descricao='Cliente se cadastrou' ORDER BY log.id_log_cliente DESC LIMIT " . $inicio . ", " . $numElementos;
        
       // self::debuga($strQuery);
        return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery, 0));
    }

    public function getCountDadosNovosCadastros() {
        $strQuery = "SELECT count(id_log_cliente) 
					FROM fgv_log_cliente log 
                                        LEFT JOIN fwk_usuario usu ON usu.id_usuario=log.id_usuario_cad 
                                        LEFT JOIN fgv_cliente cli ON cli.id_usuario=usu.id_usuario 
                                        LEFT JOIN fwk_contato con ON con.id_contato=usu.id_contato 
                                        LEFT JOIN fwk_endereco end ON end.id_endereco=cli.id_endereco
    				WHERE log.descricao='Cliente se cadastrou' ";
        $arrDados = Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery, 0));
        return end($arrDados);
    }

}

?>