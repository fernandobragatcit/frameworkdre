<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class FicheiroDAO extends AbsModelDao{

	public $_table = "fwk_ficheiro";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_ficheiro";

	public function cadastrar($xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_FICHEIRO);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_ficheiro = $objDoc->getIdDocumento();
			
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
			$objDoc->setTipoDocumento(TIPODOC_FICHEIRO);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_ficheiro = $objDoc->getIdDocumento();
			
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

	public function getIdFicheiro(){
		return $this->id_ficheiro;
	}
	
	public function getFicheiroById($id){
		$strQuery = "SELECT
						id_foto, id_album, titulo_ficheiro, bigode_ficheiro, texto_ficheiro, 
						identificador_ficheiro, id_ficheiro
					FROM 
						fwk_ficheiro
					WHERE 
						id_ficheiro ='".$id."'";
		$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,3));
		return $arrDados;
	}
	
	public function getFicheiroByIdentificador($ident){
		$strQuery = "SELECT
						id_foto, id_album, titulo_ficheiro, bigode_ficheiro, texto_ficheiro, 
						identificador_ficheiro, id_ficheiro
					FROM 
						fwk_ficheiro
					WHERE 
						UPPER(identificador_ficheiro) ='".strtoupper($ident)."'";
		$arrDados =  Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,3));
		return $arrDados;
	}
	
	public function getAreaById($idDoc){
    	$strQuery = "SELECT 
    					nome_area_ficheiro
					FROM 
						fwk_ficheiro
					WHERE 
						id_ficheiro = '".$idDoc."'";
		return Utf8Parsers::arrayUtf8Encode(ControlDB::getRow($strQuery,0));
    }

}
?>