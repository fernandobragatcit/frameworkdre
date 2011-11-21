<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

/**
 * Classe de entidade e relacionamentos de direitos para grupos
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 07/11/2009
 */
class DocumentoUsuario extends AbsModelCruds{

     /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_documento_usuario";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_usuario";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){ }

    private function salvaDireitosGrupo($arrValores,$idUsuario){
		$objBanco = ControlDb::getBanco();
		foreach ($arrValores as $valor) {
			$strQuery = "INSERT INTO ".$this->_table."
							(id_usuario, id_documento)
						VALUES
							('".$idUsuario."','".$valor."')";
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
			self::salvaDireitosGrupo(self::verificaCampos($post,"documentoUsuario"), $id);
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

    public function salvaDocumentoIndividual($idUsuario, $idDocumento){
		$objBanco = ControlDb::getBanco();
		$strQuery = "INSERT INTO ".$this->_table."
							(id_usuario, id_documento)
						VALUES
							('".$idUsuario."','".$idDocumento."')";
//		die($strQuery);
		if(!$objBanco->Execute($strQuery))
			throw new CrudException("Erro ao cadastrar os Direitos para o Grupo!");

    }

    public function getDocumentosUsuario($idUsuario){

    	$strQuery = "SELECT fd.id_documento, id_tipo_documento
    				FROM fwk_documento_usuario fdu
    				JOIN fwk_documento fd ON fd.id_documento = fdu.id_documento
    				WHERE id_usuario = '".$idUsuario."'";

		return ControlDb::getAll($strQuery);
    }

    public function setIdUsuario($id_usuario){
		$this->id_usuario = $id_usuario;
    }

    public function getIdUsuario(){
		return $this->id_usuario;
    }

    public function setIdDocumento($id_documento){
		$this->id_documento = $id_documento;
    }

    public function getIdDocumento(){
		return $this->id_documento;
    }

}
?>