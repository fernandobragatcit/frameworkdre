<?php
require_once (FWK_MODEL."AbsModelDao.class.php");

class DocumentoDAO extends AbsModelDao {

	public $_table = "fwk_documento";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_documento";

	public function getIdDocumento() {
		return $this->id_documento;
	}

	public function setTipoDocumento($intIdTipoDoc) {
		$this->id_tipo_documento = $intIdTipoDoc;
	}

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 12/12/2010
	 */
	public function cadastrar($xml, $post, $file) {
		try {
			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::salvar();
			if (self::ErrorMsg()) {
				parent::debug($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 12/12/2010
	 */
	public function alterar($id, $xml, $post, $file) {
		try {
			$this->id_documento = $id;
			self::validaForm($xml, $post);
			self::alteraPostAutoUtf8($post, $id);
			if ($id != null) {
				$arrCampos = self::buscaCampos($id);
				self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
				self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			} else {
				self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			}
			self::replace();

			if (self::ErrorMsg()) {
				print ("<h1>Erro DocumentoDAO: </h1>");
				print ("<pre>");
				print_r($this);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	/**
	 * Método responsável por retornar o tipo e id do documento
	 *
	 * @param $idDocumento: id do documento
	 * @return array Id do tipo documento e sua respectiva descrição
	 *
	 * @author Wellington
	 * @author André
	 * @since 1.0 - 21/01/2011
	 * @since 2.0 - 19/02/2011
	 *
	 * */
	public function getTipoDocumento($idDocumento) {
		$strQuery = "SELECT
						tip.id_tipo_documento, tip.tipo_documento
					FROM
						fwk_documento doc INNER JOIN fwk_tipo_documento tip
						ON doc.id_tipo_documento = tip.id_tipo_documento
					WHERE
						doc.id_documento ='".$idDocumento."'";
		return ControlDB::getRow($strQuery);
	}


	/**
	 * Metodo que, passando o id do documento, retorna seu respectivo DAO.
	 *
	 * @param int $idTipoDocumento: Id do tipo de documento.
	 * @return AbsModelDao Dao do respectivo documento
	 *
	 * @author Welligton
	 * @since 2.0 - 19/02/2011
	 * @package classes/dao
	 */
	public function getObjDaoTipoDocumento($idTipoDocumento) {
		switch ($idTipoDocumento) {
			case TIPODOC_MUNICIPIO :
				return new MunicipioDAO();
			case TIPODOC_SERVEQUIP :
				return new ServicoEquipDAO();
			case TIPODOC_ATRATIVO :
				return new AtrativoDAO();
			case TIPODOC_DICAS :
				return new DicasDAO();
			case TIPODOC_NOTICIAS :
				return new NoticiasDAO();
			case TIPODOC_FOTOS :
				return new FotosDAO();
			case TIPODOC_EVENTO_TEMP :
				return new EventosDAO();
			case TIPODOC_REGIAO :
			    return new RegiaoTuristicaDAO();
			default :
				return null;
		}
	}
	
	/**
	 * Método responsável por retornar a descrição do tipo
	 *
	 * @param $idTipo: id do tipo
	 * @return array descrição do tipo documento 
	 *
	 * @author Matheus
	 * @since 1.0 - 20/05/2011
	 *
	 * */
	public function getDescTipo($idTipo) {
		$strQuery = "SELECT
						tipo_documento
					FROM
						fwk_tipo_documento
					WHERE
						id_tipo_documento ='".$idTipo."'";
		return ControlDB::getRow($strQuery);
	}

}
?>