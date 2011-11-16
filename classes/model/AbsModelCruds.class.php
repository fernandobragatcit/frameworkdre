<?php
require_once(BIB_ACTIVE_RECORD);

require_once(FWK_MODEL."Upload.class.php");
require_once(FWK_MODEL."Usuario.class.php");

require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_CONTROL."ControlForms.class.php");
require_once(FWK_CONTROL."ControlPost.class.php");
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

require_once(FWK_EXCEPTION."CrudException.class.php");

require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_UTIL."FormataCampos.class.php");
require_once(FWK_UTIL."FormataDatas.class.php");

ADOdb_Active_Record::SetDatabaseAdapter(ControlDb::getBanco());
abstract class AbsModelCruds extends ADOdb_Active_Record{


	protected $objCtrlSessao;
	private $strTipoForm;

	abstract public function cadastrar($xml,$post,$file);

	/**
	 * Método para setar os atributos da classe automaticamente com os dados vindos do post
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
	protected function salvaPostAutoUtf8($post){
		foreach ($post as $key => $data) {
			$this->$key = utf8_decode($data);
		}
    }

    protected function alteraPostAutoUtf8($post,$id, $decode = true,$html=false){
    	$arrCampos = self::buscaCampos($id,ADODB_FETCH_ASSOC);
    	foreach ($post as $key => $data) {
			if($html)
				$data = htmlspecialchars($data, ENT_QUOTES);
			if($arrCampos[$key] != $data  && $data !="" && isset($data) ){
				if($decode)
					$this->$key = utf8_decode($data);
				else
					$this->$key = $data;
			}else{
				$this->$key = $arrCampos[$key];
			}
		}
    }

    /**
	 * Método para verificar a extensão da imagem
	 *
	 * @author André Coura
	 * @since 1.0 - 17/08/2008
	 */
    protected function getExtFoto($file){
		switch($file){
			case "image/jpeg":
				return ".jpg";
				break;
			case "image/bmp":
				return ".bmp";
				break;
			case "image/png":
				return ".png";
				break;
			case "image/gif":
				return ".gif";
				break;
			default:
				throw new CrudException("Formato de Imagem não aceito pelo sistema: ".$file);
		}
    }

	/**
	 * Método para validar o post de acordo com a configuração do xml do formulário
	 *
	 * @see falta implementar a validação e a exceção para a mesma
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
    protected function validaForm($XML,$post){
    	try{
    		$objCtrlPost = new ControlPost($XML);
    		$objCtrlPost->validaPost();
    	}catch(ExceptionForms $e){ }
    }

	/**
	 * Método para salvar os dados setados nos atributos
	 *
	 * @see falta implementar a exceção
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
    public function salvar(){
		if(!$this->save())
			throw new CrudException("Erro ao salvar os dados no banco: <strong>".$this->ErrorMsg()."</strong>");
    }

	/**
	 * Método para deletar
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
    public function deletar($id){
		$arrDados = array("table" => $this->_table,
						  "campo" => $this->_id,
						  "valor" => $id);
		if(!ControlDb::delRowTable($arrDados))
			throw new CrudException("Imppossível deletar o dado da tabela ".$this->_table);
    }

	/**
	 * Método para buscar um determinada linha no banco de dados com o intuito de preencher o formulário para edição
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
	public function buscaCampos($id,$fetchMode = 0){
		return self::buscaCamposTbl($id,$this->_table,$this->_id,$fetchMode);
	}

	protected function buscaCamposTbl($id,$tabela, $campo, $fetchMode = 0){
		$arrDados = array("table" => $tabela,
						  "campo" => $campo,
						  "valor" => $id);
		return ControlDb::selectRowTable($arrDados,$fetchMode);
	}

	protected function verifIntegridadeUTF8($campo, $valor){
		$query = "SELECT COUNT(*) FROM ".$this->_table."
				  WHERE LOWER(".$campo.") = '".strtolower(utf8_decode($valor))."'";
		$arrRet = ControlDb::getBanco()->GetRow($query);
		if($arrRet[0]>0)
			throw new CrudException("Ja existe este registro no sistema.");
	}


	protected function vaiPara($strLocal){
		$objCrypt = new Cryptografia();
		header("Location: ?c=".$objCrypt->cryptData($strLocal));
	}

	/**
	 * Método responsável pelo tratamento do upload do arquivo
	 *
	 * @param String $strNomeFoto: Novo nome do campo da foto
	 * @param String $strField: campo no formulário contando o campo file
	 * @param String $strCaminho: caminho fisico da pasta de destino da imagem
	 *
	 */
    protected function uploadFile($strNomeFoto,$strField,$strCaminho){
		$objUpload = new Upload();
		$objUpload->uploadFile($strField, $strCaminho, $strNomeFoto);
    }

	protected function apagaArquivoFisico($id,$campo,$pastaImg){
    	$result = self::buscaCampos($id);
		if(isset($result[$campo]) && $result[$campo] != "" && $result[$campo] != null){
			if(is_file($pastaImg.$result[$campo])){
				unlink($pastaImg.$result[$campo]);
			}else{
				throw new CrudException("Não foi possível deletar o arquivo da imagem.");
				return;
			}
		}
    }

    protected function verificaCampos($post,$campo){
    	$arrRet = array();
    	foreach ($post as $indice => $valor) {
			$arrIndice = explode("_",$indice);
			if($arrIndice[0] == $campo)
				$arrRet[] = $valor;
		}
		return $arrRet;
    }

    /**
	 * Método para preencher os dados com os valores vindos do banco
	 *
	 * @author André Coura
	 * @since 1.0 - 14/09/2008
	 */
	public function preencheForm($xml,$id,$crudClass){
		$arrDados = self::buscaCampos($id);
		$objCtrlForm = new ControlForm($xml);
		$objCtrlForm->setTipoForm(self::getTipoForm());
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm($crudClass."&a=altera&id=".$id);
		$objCtrlForm->setId($id);
		$objCtrlForm->registraFormValues($arrDados);
	}

	public function getTipoForm(){
		return $this->strTipoForm;
	}

	public function setTipoForm($strTipoForm){
		$this->strTipoForm = $strTipoForm;
	}

	/**
	 * Consulta as dependências do registro
	 *
	 * @author André Coura
	 * @since 1.0 - 12/10/2008
	 * @return boolean: true caso exista dependencia, false caso negativo
	 */
    protected function checkDependencia($campoSel, $tableFrom, $campoWhere,$id){
		$strQueryVer = "SELECT {$campoSel} FROM {$tableFrom} WHERE {$campoWhere} = ".$id;
		$arrRet = ControlDb::getBanco()->GetAll($strQueryVer);
		return count($arrRet)>0;
    }

    protected function getUsuarioSessao(){
    	$this->objCtrlSessao = new ControlSessao();
		return $this->objCtrlSessao->getObjSessao(SESSAO_FWK_DRE);
    }

    protected function getObjCripto(){
		if($this->objCripto == null)
			$this->objCripto = new Cryptografia();
		return $this->objCripto;
	}

	protected function setIdUserCad($idUserCad){
    	if(is_numeric($idUserCad))
    		$this->id_usuario_cad = (int)$idUserCad;
    	$this->data_cadastro = date("Y-m-d");
    }

    protected function setIdUserAlt($idUserAlt){
		if(is_numeric($idUserAlt))
    		$this->id_usuario_alt = (int)$idUserAlt;
    	$this->data_alteracao = date("Y-m-d");
    }

    public function alteraStatus($id){
		$arrCampos = self::buscaCampos($id);
		self::alteraPostAutoUtf8($arrCampos,$id,false);
		$this->status_publicacao = $arrCampos["status_publicacao"]=="S"?"N":"S";
		self::replace();
    }

    public function setStatusPublicacao($strStatus){
    	$this->status_publicacao = $strStatus;
    }

	/**
	 * Caso não seja setado o status da publicação, seta-se Sim por default
	 */
    public function getStatusPublicacao(){
    	if($this->status_publicacao == null)
    		$this->status_publicacao = "S";
    	return $this->status_publicacao;
    }

    public function setOrigemCadastro($strStatus){
    	$this->origem_cad = $strStatus;
    }

	/**
	 * Caso não seja setado a origem do cadastro, seta Admin por default
	 */
    public function getOrigemCadastro(){
    	if($this->origem_cad == null)
    		$this->origem_cad = "A";
    	return $this->origem_cad;
    }

        /**
	 * Método para verificar a extensão do css
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 22/10/2010
	 */
    protected function getExtCss($file){
		switch($file){
			case "text/css":
				return ".css";
				break;
			default:
				throw new CrudException("Formato de arquivo não aceito pelo sistema: ".$file);
		}
    }
    
	public function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print_r($arrDados[$i]);
			print"<br />";
		}
		die();
	}
}
?>