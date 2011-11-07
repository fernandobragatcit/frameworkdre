<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

class UsuariosGrupo extends AbsModelCruds{


    /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_grupo_usuario";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_grupo_usuario";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){ }

    private function salvaUsuariosGrupo($arrValores,$idGrupo){
		$objBanco = ControlDb::getBanco();
		foreach ($arrValores as $valor) {
			$strQuery = "INSERT INTO ".$this->_table."
							(id_usuario, id_grupo)
						VALUES
							('".$valor."','".$idGrupo."')";
		if(!$objBanco->Execute($strQuery))
			throw new CrudException("Erro ao cadastrar os Usuários para o Grupo!");
		}
    	if(self::ErrorMsg()){
			print("<pre>");
			print_r($post);
			die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
		}
    }

	private function limpaUsuariosGrupo($id){
    	ControlDb::delRowTable(array("table" => $this->_table,
									 "campo" => "id_grupo",
									 "valor" => $id));
		if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 08/11/2009
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			self::limpaUsuariosGrupo($id);
			self::salvaUsuariosGrupo(self::verificaCampos($post,"usuariosGrupo"), $id);
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

}
?>