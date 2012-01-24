<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class GaleriasDAO extends AbsModelDao{

	public $_table = "fwk_galeria";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_galeria";

	public function cadastrar($xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_GALERIA);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_galeria = $objDoc->getIdDocumento();
			
			$this->id_portal = parent::getCtrlConfiguracoes()->getIdPortal();
			
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 21/04/2011
	 */
	public function alterar($id,$xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_GALERIA);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_galeria = $objDoc->getIdDocumento();
			
			$this->id_portal = parent::getCtrlConfiguracoes()->getIdPortal();
			
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdGaleria(){
		return $this->id_galeria;
	}
	
	public function getGaleriaById($id){
		$strQuery = "SELECT
						id_galeria, titulo_galeria, bigode_galeria, identificador_galeria
					FROM 
						".$this->_table."
					WHERE 
						".$this->_id." = '".$id."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().")";
		$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,3));
		return $arrDados;
	}
	
	public function getGaleriaByIdentificador($ident){
		$strQuery = "SELECT
						id_foto, id_album, titulo_ficheiro, bigode_ficheiro, texto_ficheiro, 
						identificador_ficheiro, id_ficheiro
					FROM 
						".$this->_table."
					WHERE 
						UPPER(identificador_ficheiro) ='".strtoupper($ident)."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().")";
		$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,3));
		return $arrDados;
	}
	
	public function buscaFotosGaleria($idGaleria=null){
    	$strQuery = "SELECT titulo_foto, legenda_foto, ffg.id_foto, ff.id_foto AS 'id_nome_arquivo'
					FROM fwk_fotos_galeria ffg
					INNER JOIN fwk_fotos ff ON ffg.id_foto = ff.id_foto
    				WHERE id_galeria = '".$idGaleria."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().")";
    	return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery,0));
	}
	
	
    public function getGaleriasByIdPortal($idPortal, $limit){
    	$intPag = ($intPag>0)?(int)$intPag-1:$intPag;
    	$inicio = ((int)$intPag) * NUM_ELEMENTOS_LISTAGEM;
    	$strQuery = "SELECT id_galeria, titulo_galeria, bigode_galeria, texto_galeria, identificador_galeria,
    				(SELECT id_foto FROM fwk_fotos_galeria ffg ORDER BY RAND() LIMIT 0,1) AS 'id_capa'
					FROM fwk_galeria fg
    				WHERE id_portal = '".$idPortal."' 
    				ORDER BY id_galeria DESC 
    				LIMIT ".$inicio.", ".NUM_ELEMENTOS_LISTAGEM;
    	return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery,0));
    }
    
    public function getTotalGaleriasByIdPortal($idPortal){
    	$strQuery = "SELECT COUNT(*)
					FROM fwk_galeria
    				WHERE id_portal = '".$idPortal."'";
    	$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,0));
    	return end($arrDados);
    }
	
    public function getTituloGaleriaByIdGaleria($idGaleria){
    	$strQuery = "SELECT titulo_galeria FROM fwk_galeria	WHERE id_galeria = '".$idGaleria."'";
    	$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,0));
    	return end($arrDados);
    }
	

}
?>