<?php
require_once (FWK_MODEL."AbsModelDao.class.php");
require_once(FWK_DAO."DocumentoDAO.class.php");

class ArquivosDAO extends AbsModelDao {

	public $_table = "fwk_arquivo";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_arquivo";

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "file_arquivo";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 06/08/2010
	 */
	public function cadastrar($xml, $post, $file) {
		try {
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_ARQUIVO);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_arquivo = $objDoc->getIdDocumento();

			self::validaForm($xml, $post);
			self::salvaPostAutoUtf8($post);

			//Tratamento especifico para a foto
			$this->file_arquivo = $file[$this->strNomeCampo]["name"];
			$this->extensao_arquivo = end(explode(".", self::getExtFile($file[$this->strNomeCampo]["type"])));

			$this->tiposArquivos = array(".pdf",".doc",".docx",".xls",".xlsx");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			self::getObjUploadFile()->setOverwrite("N");
			self::uploadFile($this->file_arquivo, $this->strNomeCampo, PASTA_UPLOADS_ARQUIVOS);

			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::salvar();
			
			if(self::ErrorMsg()){
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
			
			
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdArquivo(){
		return $this->id_arquivo;
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
			$objDoc->setTipoDocumento(TIPODOC_ARQUIVO);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_arquivo = $objDoc->getIdDocumento();

			$arrCampos = self::buscaCampos($id,0);
			self::validaForm($xml, $post);
			self::alteraPostAutoUtf8($post, $id);
			$this->file_arquivo = $arrCampos["file_arquivo"];
			if($file[$this->strNomeCampo]["name"] != ""){
				try{
					parent::apagaArquivoFisico($id,$this->strNomeCampo,PASTA_UPLOADS_ARQUIVOS);
				}catch(DaoException $e){
					//mesmo que não consiga apagar o arquivo, procigo com o processo de upload
				}
				$strNovoNome = "DOC".str_replace(".", "",str_replace(" ", "", microtime()));
				$this->file_arquivo = $strNovoNome.self::getExtFile($file[$this->strNomeCampo]["type"]);
				$this->extensao_arquivo = end(explode(".", self::getExtFile($file[$this->strNomeCampo]["type"])));

				$this->tiposArquivos = array(".pdf",".doc",".docx",".xls",".xlsx");
				self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
				self::getObjUploadFile()->setOverwrite("N");
				self::uploadFile($this->file_arquivo, $this->strNomeCampo, PASTA_UPLOADS_ARQUIVOS);
			}else{
				$this->file_arquivo = $arrCampos[$this->strNomeCampo];
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
			$strQuery = "SELECT id_arquivo FROM ".$this->_table;
			return ControlDB::getAll($strQuery);
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

}
?>