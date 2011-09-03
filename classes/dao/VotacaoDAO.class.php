<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class VotacaoDAO extends AbsModelDao{

	public $_table = "fwk_votacao";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_votacao";

	private $strNomeCampo = "id_usuario";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 26/07/2010
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->data_cadastro = date("Y-m-d");
			self::salvar();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 26/07/2010
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_votacao = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			$result = self::buscaCampos($id);
			self::replace();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }



    /** Retornar o conteúdo de Atividade */
	public function getVotos($idDocumento){
    	$strQuery ="SELECT avaliacao FROM fwk_votacao WHERE id_documento = '".$idDocumento."'";
//		die($strQuery);
        return ControlDB::getAll($strQuery);
    }

}
?>