<?php
require_once (FWK_MODEL."AbsModelDao.class.php");
require_once(FWK_DAO."DocumentoDAO.class.php");

class FotosDAO extends AbsModelDao {

	public $_table = "fwk_fotos";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_foto";

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "nome_arquivo";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 06/08/2010
	 */
	public function cadastrar($xml, $post, $file) {
		try {
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_FOTOS);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_foto = $objDoc->getIdDocumento();
			self::validaForm($xml, $post);
			self::salvaPostAutoUtf8($post);
			
			$this->id_foto = $objDoc->getIdDocumento();

			//Tratamento especifico para a foto
			//$strNovoNomeFoto = "FOT".time();
			$strNovoNomeFoto = "FOT".str_replace(".", "",str_replace(" ", "", microtime()));
			
			$this->nome_arquivo = $strNovoNomeFoto.self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tipo_foto = end(explode(".", self::getExtFile($file[$this->strNomeCampo]["type"])));
			$this->tiposArquivos = array(".jpg",".gif",".png",".bmp");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			self::getObjUploadFile()->setOverwrite("N");
			if($post["multiplos"] == true)
				self::uploadMultiploFiles($this->nome_arquivo, $post["nome_campo"], PASTA_UPLOADS_FOTOS, $post["indice"]);
			else
				self::uploadFile($this->nome_arquivo, $this->strNomeCampo, PASTA_UPLOADS_FOTOS);
			
			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::salvar();
			
			if(self::ErrorMsg()){
				print("<h1>FotosDAO</h1>");
				parent::debug($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
			
		} catch (DaoException $e) {
			print("<h1>FotosDAO</h1> ID Foto: ".$this->id_foto);
			parent::debug($post);
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdFoto(){
		return $this->id_foto;
	}
	public function setNomeCampo($nomeCampo){
		$this->strNomeCampo = $nomeCampo;
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 06/08/2010
	 */
	public function alterar($id, $xml, $post, $file) {
		try {

			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_FOTOS);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_foto = $objDoc->getIdDocumento();

			$arrCampos = self::buscaCampos($id,0);
			self::validaForm($xml, $post);
			self::alteraPostAutoUtf8($post, $id);
			$this->nome_arquivo = $arrCampos["nome_arquivo"];
			if($file[$this->strNomeCampo]["name"] != ""){
				try{
					parent::apagaArquivoFisico($id,$this->strNomeCampo,PASTA_UPLOADS_FOTOS);
				}catch(DaoException $e){ }
				$strNovoNomeFoto = "FOT".time();
				$this->nome_arquivo = $strNovoNomeFoto.self::getExtFile($file[$this->strNomeCampo]["type"]);
				$this->tipo_foto = end(explode(".", self::getExtFile($file[$this->strNomeCampo]["type"])));

				$this->tiposArquivos = array(".jpg",".gif",".png",".bmp",".pjpeg");
				self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
				self::getObjUploadFile()->setOverwrite("N");
				self::uploadFile($this->nome_arquivo, $this->strNomeCampo, PASTA_UPLOADS_FOTOS);
			}else{
				$this->nome_arquivo = $arrCampos[$this->strNomeCampo];
			}
			self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
			self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			self::replace();
			
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function getExtFile($file){
		return self::getExtFiles($file);
	}

	public function buscaFotos() {
		try {
			$strQuery = "SELECT id_foto FROM fwk_fotos";
			return ControlDB::getAll($strQuery);
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}
	
	public function deletaFoto($id){
		try {
			self::apagaArquivoFisico($id, $this->strNomeCampo, PASTA_UPLOADS_FOTOS);
			self::deletar($id);
		} catch (CrudException $e) {
			throw new CrudException($e->getMensagem());
		}
	}

}
?>