<?php
require_once(FWK_MODEL."AbsModelDao.class.php");
require_once(FWK_DAO."DocumentoDAO.class.php");


class ChamadosDAO extends AbsModelDao{

	public $_table = "fwk_chamados";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_chamado";


	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 12/04/2011
	 */
	public function cadastrar($xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_CELULA);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_celula = $objDoc->getIdDocumento();

			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
				
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdCelula(){
		return $this->id_celula;
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 12/04/2011
	 */
	public function alterar($id,$xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_CELULA);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_celula = $objDoc->getIdDocumento();

			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
				
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdCursosBydIdCel($idRel) {
        $strQuery = "
    		SELECT fc.id_curso, fc.nome_curso, 0 AS 'contador'
    		FROM fgv_celula_curso fcc
    		INNER JOIN fgv_curso fc ON fc.id_curso = fcc.id_curso
    		WHERE fcc.id_celula = '" . $idRel . "' 
    	";
        return Utf8Parsers::matrizUtf8Encode(ControlDb::getAll($strQuery, 0));
    }


}
?>
