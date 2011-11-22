<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

class GruposUsuario extends AbsModelCruds{


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

    private function salvaGruposUsuario($arrValores,$idUsuario){
		$objBanco = ControlDb::getBanco();
		foreach ($arrValores as $valor) {
			$strQuery = "INSERT INTO ".$this->_table."
							(id_grupo, id_usuario)
						VALUES
							('".$valor."','".$idUsuario."')";
		if(!$objBanco->Execute($strQuery))
			throw new CrudException("Erro ao cadastrar os Grupos para o Usuário!");
		}
    	if(self::ErrorMsg()){
			print("<pre>");
			print_r($post);
			die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
		}
    }

	private function limpaGruposUsuario($id){
    	ControlDb::delRowTable(array("table" => $this->_table,
									 "campo" => "id_usuario",
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
			self::limpaGruposUsuario($id);
			self::salvaGruposUsuario(self::verificaCampos($post,"gruposUsuario"), $id);
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

    public function setUsuarioGrupo($idUsuario, $idGrupo = 3){
		self::limpaGruposUsuario($idUsuario);
    	self::salvaGruposUsuario(array($idGrupo),$idUsuario);
    }

    public function getGrupoUsuario($idUsuario){

    	$strQuery = "SELECT id_grupo
    				 FROM fwk_grupo_usuario
    				 WHERE id_usuario = '".$idUsuario."'";

    	return ControlDb::getAll($strQuery);
    }
    

    public function getLastGrupoUsuario($idUsuario){

    	$strQuery = "SELECT id_grupo
    				 FROM fwk_grupo_usuario
    				 WHERE id_usuario = '".$idUsuario."'";

    	return ControlDb::getRow($strQuery);
    }
}
?>