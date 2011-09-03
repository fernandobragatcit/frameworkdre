<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

/**
 * Classe de entidade e relacionamentos de direitos para grupos
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 07/11/2009
 */
class DireitosGrupoAdmin extends AbsModelCruds{

     /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_direitos_grupo";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_grupo";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){ }

    private function salvaDireitosGrupo($arrValores,$idGrupo){
		$objBanco = ControlDb::getBanco();
		foreach ($arrValores as $valor) {
			$strQuery = "INSERT INTO ".$this->_table."
							(id_grupo, id_direitos)
						VALUES
							('".$idGrupo."','".$valor."')";
		if(!$objBanco->Execute($strQuery))
			throw new CrudException("Erro ao cadastrar os Direitos para o Grupo!");
		}
    }

	private function limpaDiretosPorGrupo($id){
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
			self::limpaDiretosPorGrupo($id);
			self::salvaDireitosGrupo(self::verificaCampos($post,"direitosGrupo"), $id);
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

    public function setIdGrupo($id_grupo){
		$this->id_grupo = $id_grupo;
    }

    public function getIdGrupo(){
		return $this->id_grupo;
    }

}
?>