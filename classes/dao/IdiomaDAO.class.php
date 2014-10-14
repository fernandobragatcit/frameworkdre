<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class IdiomaDAO extends AbsModelDao{

	public $_table = "inv_idioma";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "ID_IDIOMA";

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
			$this->id_idioma = $id;
			$arrCampos = self::buscaCampos($id);
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function getIdIdioma(){
    	return $this->id_idioma;
    }

    public function getIdiomaBySigla($strSigla, $utf8=true){
		$strQuery = "SELECT
						nome_idioma
					FROM
						fwk_idioma
					WHERE
						sigla_idioma = '".$strSigla."'";
		$arrResult = ControlDB::getRow($strQuery);
		if($utf8)
			return $arrResult[0];
		return $arrResult[0];
    }
}
?>