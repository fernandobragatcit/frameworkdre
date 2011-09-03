<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

/**
 * Direitos de Usuarios
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 07/11/2009
 */
class DireitosUsuarioAdmin extends AbsModelCruds{

     /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_direitos_usuario";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_usuario";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){ }

    private function salvaDireitosUsuario($arrValores,$idUsuario){
		ControlDb::getBanco()->StartTrans();
		foreach ($arrValores as $valor) {
			$strQuery = "INSERT INTO ".$this->_table."
							(id_direitos,id_usuario)
						VALUES
							('".$valor."','".$idUsuario."')";
			if(!ControlDb::getBanco()->Execute($strQuery)){
				ControlDb::getBanco()->FailTrans();
				throw new CrudException("Erro ao cadastrar os Direitos para o Usuário!");
			}
		}
		ControlDb::getBanco()->CompleteTrans();
    }

	private function limpaDiretosPorUsuario($id){
    	ControlDb::delRowTable(array("table" => $this->_table,
									 "campo" => $this->_id,
									 "valor" => $id));
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 05/09/2008
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			self::limpaDiretosPorUsuario($id);
			self::salvaDireitosUsuario(self::verificaCampos($post,"direitosUsuario"), $id);
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

     public function setIdDireito($id_direitos){
		$this->id_direitos = $id_direitos;
    }

    public function getIdDireito(){
		return $this->id_direitos;
    }

    public function setIdUsuario($id_grupo){
		$this->id_usuario = $id_grupo;
    }

    public function getIdUsuario(){
		return $this->id_usuario;
    }

}
?>