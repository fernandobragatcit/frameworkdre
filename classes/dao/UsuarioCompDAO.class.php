<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class UsuarioCompDAO extends AbsModelDao{

	public $_table = "usr_dados_complementares";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_usuario_comp";


	//private $strNomeCampo = "foto_usr";

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
			$this->id_usuario_comp = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
//			$result = self::buscaCampos($id);
//			if($file[$this->strNomeCampo]["name"] != ""){
//				try{
//					parent::apagaArquivoFisico($id,$this->strNomeCampo,DEPOSITO_IMGSUSR);
//				}catch(DaoException $e){ }
//				$this->tipo_imagem = end(explode(".", self::getExtFile($file[$this->strNomeCampo]["type"])));
//				$this->foto_usr = "USR".time().self::getExtFile($file[$this->strNomeCampo]["type"]);
//				$this->tiposArquivos = array(".jpg",".png",".bmp",".gif");
//				self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
//				self::uploadFile($this->foto_usr,$this->strNomeCampo,DEPOSITO_IMGSUSR);
//			}else{
//				$this->foto_usr = $result[$this->strNomeCampo];
//			}
			$this->nascimento_usuario = FormataDatas::parseDataSql($post["nascimento_usuario"]);
			self::replace();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function getIdUsuarioComp(){
    	return $this->id_usuario_comp;
    }

	/**
	 * Busca os dados complementáres do usuario
	 *
	 * @author André
	 * @since 1.0 - 02/09/2010
	 */
    public function getDadosCompById($idUsuario){
		$strQuery = "SELECT
						cidade_usuario,
						pais_usuario,
						foto_usr,
						profissao_usuario,
						sexo_usuario,
						nascimento_usuario,
						id_usuario_comp,
						id_endereco_usuario,
						id_contato_usuario,
						id_tema, 
						id_foto
					FROM
						usr_dados_complementares
					WHERE
						id_usuario = '".$idUsuario."'";
		return ControlDB::getRow($strQuery,0);
    }

    public function getDadosUsuarioReserva($idUsuario){

		$strQuery = "SELECT nome_usuario, email_usuario, rua_avenida_usuario, numero_usuario,
					complemento_usuario, bairro_usuario, cep_usuario,
					ddd_usuario, telefone_usuario, cidade_usuario, pais_usuario, sigla
					FROM fwk_usuario fu
					JOIN usr_dados_complementares udc ON fu.id_usuario = udc.id_usuario
					JOIN usr_contato uc ON udc.id_contato_usuario = uc.id_contato_usuario
					JOIN usr_endereco ue ON ue.id_endereco_usuario = udc.id_endereco_usuario
					JOIN fwk_estados fe ON ue.id_estado_usuario = fe.id_estado
					WHERE fu.id_usuario = '".$idUsuario."'";
		//die($strQuery);
		return ControlDB::getRow($strQuery);
    }

    public function getExtFile($file){
		return self::getExtFiles($file);
	}
}
?>