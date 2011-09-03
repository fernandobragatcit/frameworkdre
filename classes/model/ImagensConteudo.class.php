<?php
require_once(FWK_MODEL."AbsModelDao.class.php");


class ImagensConteudo extends AbsModelDao {
    /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_imagens";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_imagem";

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "nome_imagem";
	private $strNomeCampoZip = "nome_imagem_zip";

    public function cadastrarImagem($xml,$post,$file,$campo){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->nome_imagem = $file[$campo]["name"];
			$this->tipo_imagem = end(explode(".", self::getExtFile($file[$campo]["type"])));
			$this->tiposArquivos = array(".jpg",".png",".bmp",".gif");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			self::getObjUploadFile()->setOverwrite("N");
			self::uploadFile("", $campo, DEPOSITO_IMGS);
			$this->data_usuario_cad = date("Y-m-d");
			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::salvar();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}catch(UploadException $e2){
			throw new UploadException($e2->getMensagem());
		}
    }

    public function cadastrarImagemZip($xml,$post,$file,$zip,$campo){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->nome_imagem = $zip;
			$this->tipo_imagem = end(explode(".", $zip));
			self::getObjUploadFile()->setOverwrite("N");
			self::copyFile(DEPOSITO_IMGS."zip/", DEPOSITO_IMGS, $this->nome_imagem);
			$this->data_usuario_cad = date("Y-m-d");
			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::salvar();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}catch(UploadException $e2){
			throw new UploadException($e2->getMensagem());
		}
    }

	public function alterar($id,$xml,$post,$file){
		try{
			$this->id_imagem = $id;
			$arrCampos = self::buscaCampos($id,0);
			$extensao = self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tiposArquivos = array(".jpg",".png",".bmp",".gif");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			if(in_array($extensao,$this->tiposArquivos)){
				self::validaForm($xml,$post);
				$this->nome_imagem = $file[$this->strNomeCampo]["name"];
				$this->tipo_imagem = end(explode(".",self::getExtFile($file[$this->strNomeCampo]["type"])));

				if($file[$this->strNomeCampo]["name"] != "" ){
					try{
						self::apagaArquivoFisico($id,$this->strNomeCampo,DEPOSITO_IMGS);
					}catch(DaoException $e){
						//mesmo que não consiga apagar o arquivo, procigo com o processo de upload
					}
					self::alteraPostAutoUtf8($post,$id);
					self::getObjUploadFile()->setOverwrite("N");
					self::uploadFile("", $this->strNomeCampo, DEPOSITO_IMGS);
					$this->data_usuario_cad = date("Y-m-d");
				}
			}else{
				$this->nome_imagem = $arrCampos[$this->strNomeCampo];
				$this->tipo_imagem = $arrCampos["tipo_imagem"];
			}

			self::setIdUserCad($arrCampos["id_usuario_cad"],$arrCampos["data_cadastro"]);
			self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			self::replace();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }


    public function deletarImagem($id){
    	try{
	    	self::apagaArquivoFisico($id,$this->strNomeCampo,DEPOSITO_IMGS);
	    	self::deletar($id);
    	}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

    public function getExtFile($file){
		return self::getExtFiles($file);
	}

}
?>