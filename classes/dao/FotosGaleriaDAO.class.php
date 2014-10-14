<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class FotosGaleriaDAO extends AbsModelDao{

	public $_table = "fwk_fotos_galeria";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_galeria";

	public function cadastrar($xml,$post,$file){
		try{
			self::salvaGaleria($post["id_galeria"],$post["id_foto"]);
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

	/**
	 * M�todo para alterar somente os campos que realmente foram alterados n�o mexendo com os outros
	 *
	 * @author Andr� Coura
	 * @since 1.0 - 26/07/2010
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			self::salvaGaleria($id,$post["id_foto"]);
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function limpaGaleria($id){
    	ControlDb::delRowTable(array("table" => $this->_table,"campo" => $this->_id, "valor" => $id));
    }

    private function salvaGaleria($idGaleria,$idFoto){
		$objBanco = ControlDb::getBanco();
		$strQuery = "INSERT INTO ".$this->_table."
						(id_galeria, id_foto)
					VALUES
						('".$idGaleria."','".$idFoto."')";
		if(!$objBanco->Execute($strQuery))
			throw new CrudException("Erro ao cadastrar as fotos na Galeria!");
    }
    
    public function getFotosGaleria($strGaleria){
    	$strQuery = "SELECT titulo_foto, legenda_foto, ff.id_foto, ff.legenda_foto
					FROM fwk_fotos_galeria ffg
					INNER JOIN fwk_galeria fg ON ffg.id_galeria = fg.id_galeria
					INNER JOIN fwk_fotos ff ON ffg.id_foto = ff.id_foto
    				WHERE fg.identificador_galeria = '".$strGaleria."'";
    	return ControlDb::getAll($strQuery,0);
    }
    
    public function getFotosGaleriabyId($idGaleria){
    	$strQuery = "SELECT titulo_foto, legenda_foto, ff.id_foto, ff.legenda_foto
					FROM fwk_fotos_galeria ffg
					INNER JOIN fwk_galeria fg ON ffg.id_galeria = fg.id_galeria
					INNER JOIN fwk_fotos ff ON ffg.id_foto = ff.id_foto
    				WHERE fg.id_galeria = '".$idGaleria."'";
    	return ControlDb::getAll($strQuery,0);
    }

}
?>