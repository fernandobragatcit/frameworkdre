<?php
require_once(BIB_ACTIVE_RECORD);

require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_CONTROL."ControlForms.class.php");
require_once(FWK_CONTROL."ControlPost.class.php");
require_once(FWK_CONTROL."ControlDB.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

require_once(FWK_MODEL."Upload.class.php");

require_once(FWK_EXCEPTION."DaoException.class.php");
require_once(FWK_EXCEPTION."UploadException.class.php");
require_once(FWK_CONTROL."ControlSmarty.class.php");

require_once(FWK_UTIL."Cryptografia.class.php");
require_once(FWK_UTIL."FormataString.class.php");
require_once(FWK_UTIL."FormataCampos.class.php");
require_once(FWK_UTIL."FormataDatas.class.php");
require_once(FWK_CONTROL."ControlConfiguracoes.class.php");


ADOdb_Active_Record::SetDatabaseAdapter(ControlDb::getBanco());
class AbsModelDao extends ADOdb_Active_Record{


	private $objCtrlForm;
	private $strTipoForm;
	private $objUpload;
	private $objCtrlConfiguracoes;

	/**
	 * Método para setar os atributos da classe automaticamente com os dados vindos do post
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
	protected function salvaPostAutoUtf8($post){
		foreach ($post as $key => $data) {
			$key = strtolower($key);
			$this->$key = utf8_decode($data);
		}
	}

	public function debuga(){
		$arrDados = func_get_args();
		print("<pre>");
		for($i=0; $i<count($arrDados); $i++){
			print_r($arrDados[$i]);
		}
		die();
	}


	protected function getCtrlConfiguracoes(){
		if($this->objCtrlConfiguracoes == null)
			$this->objCtrlConfiguracoes = new ControlConfiguracoes();
		return $this->objCtrlConfiguracoes;
	}

	public function getObjCtrlForm($strXml){
		if($this->objCtrlForm == null){
			$this->objCtrlForm = new ControlForm($strXml);
		}
		return $this->objCtrlForm;
	}

	protected function alteraPostAutoUtf8($post,$id, $decode = true, $html=false){
		$arrCampos = self::buscaCampos($id);

		foreach ($post as $key => $data) {
			if(!$html)
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
		throw new DaoException("Erro ao salvar os dados no banco: <strong>".$this->ErrorMsg()."</strong>");
		return true;
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

//		if (self::ErrorMsg()) {
//				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
//			}

		if(!ControlDb::delRowTable($arrDados))
//		throw new DaoException("Imppossível deletar o dado da tabela ".$this->_table." id = ".$id);
		throw new DaoException(self::ErrorMsg());
	}

	/**
	 * Método para alterar status do item
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 02/08/2011
	 */
	public function alteraStatus($id){
		$arrDados = array("table" => $this->_table,
						  "campo" => $this->_id,
						  "valor" => $id);

		if(!ControlDb::alteraStatus($arrDados))
		throw new DaoException("Impossível alterar o status da tabela ".$this->_table." id = ".$id);
	}
	
	/**
	 * Método para anular um campo
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 17/10/2011
	 */
	public function anulaCampo($id, $campo){
		$arrDados = array("table" => $this->_table,
						  "campo" => $this->_id,
						  "valor" => $id,
						  "campoNull" => $campo);

		if(!ControlDb::anulaCampo($arrDados))
		throw new DaoException("Impossível anular o campo ".$campo." da tabela ".$this->_table." id = ".$id);
	}
	

	/**
	 * Método para buscar um determinada linha no banco de dados com o intuito de preencher o formulário para edição
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 */
	public function buscaCampos($id,$fetchMode = 0,$campo = null){
		$strCampo = ((!isset($campo))?$this->_id:$campo);
		$arrDados = array("table" => $this->_table,
						  "campo" => $strCampo,
						  "valor" => $id);
		return ControlDb::selectRowTable($arrDados,$fetchMode);
	}

	public function buscaAllCampos($id,$fetchMode = 0,$campo = null){
		$strCampo = ((!isset($campo))?$this->_id:$campo);
		$arrDados = array("table" => $this->_table,
						  "campo" => $strCampo,
						  "valor" => $id);
		return ControlDb::selectAllTable($arrDados,$fetchMode);
	}

	public function buscaAllRows($fetchMode = 0,$campo = null){
		$arrDados = array("table" => $this->_table);
		return ControlDb::selectAllRows($arrDados,$fetchMode);
	}

	public function buscaCampos2wheres($campo1, $valor1,$campo2, $valor2){
		$query = "SELECT * FROM ".$this->_table."
				  WHERE LOWER(".$campo1.") = '".strtolower(utf8_decode($valor1))."'
				  AND LOWER(".$campo2.") = '".strtolower(utf8_decode($valor2))."'";
		ControlDb::getBanco()->SetFetchMode(ADODB_FETCH_ASSOC);
		return ControlDb::getBanco()->GetRow($query);
	}

	protected function verifIntegridadeUTF8($campo, $valor){
		$query = "SELECT COUNT(*) FROM ".$this->_table."
				  WHERE LOWER(".$campo.") = '".strtolower(utf8_decode($valor))."'";
		$arrRet = ControlDb::getBanco()->GetRow($query);
		if($arrRet[0]>0)
		throw new DaoException("Ja existe este registro no sistema.");
	}

	protected function vaiPara($strLocal,$meio = "c"){
		$objCrypt = new Cryptografia();
		header("Location: ?".$meio."=".$objCrypt->cryptData($strLocal));
	}

	protected function verificaCampos($post,$campo){
		$arrRet = array();
		foreach ($post as $indice => $valor) {
			$arrIndice = explode("_",$indice);
			if($arrIndice[0] == $campo){
				$arrRet[] = $valor;}
		}
		return $arrRet;
	}

	/**
	 * Método para preencher os dados com os valores vindos do banco
	 *
	 * @author André Coura
	 * @since 1.0 - 14/09/2008
	 * @since 2.0 - 30/07/2010
	 */
	public function preencheForm($xml,$id,$crudClass,$estrutura=true){
		$arrDados = self::buscaCampos($id,0);
		$arrDados = self::setKeyCaixaBaixa($arrDados);
		$objCtrlForm = self::getObjCtrlForm($xml);
		$objCtrlForm->setTipoForm(self::getTipoForm());
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm($crudClass."&a=altera&id=".$id);
		$objCtrlForm->setId($id);
		$objCtrlForm->registraFormValues($arrDados,false,$estrutura);
	}

	public function preencheFormComDados($xml,$id,$crudClass,$arrDados, $idDocumento = null){
		$arrDados = self::setKeyCaixaBaixa($arrDados);
		$objCtrlForm = self::getObjCtrlForm($xml);
		$objCtrlForm->setTipoForm(self::getTipoForm());
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm($crudClass."&a=altera&id=".$id."&idServEquip=".$idDocumento);
		$objCtrlForm->setId($id);
		$objCtrlForm->registraFormValues($arrDados,false);
	}



	public function getTipoForm(){
		return $this->strTipoForm;
	}

	public function setTipoForm($strTipoForm){
		$this->strTipoForm = $strTipoForm;
	}


	protected function setKeyCaixaBaixa($arrDados){
		$arrNovoDados = array();
		if(count($arrDados)>0){
			foreach ($arrDados as $key => $dados) {
				$arrNovoDados[strtolower($key)] = $dados;
			}
		}
		return $arrNovoDados;
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
		if($this->objCtrlSessao == null)
		$this->objCtrlSessao = new ControlSessao();
		return $this->objCtrlSessao->getObjSessao(SESSAO_FWK_DRE);
	}

	protected function getObjCripto(){
		if($this->objCripto == null)
		$this->objCripto = new Cryptografia();
		return $this->objCripto;
	}

	protected function setIdUserCad($idUserCad, $dataCad = null){
		if(is_numeric($idUserCad))
		$this->id_usuario_cad = (int)$idUserCad;
		if($dataCad == null)
		$this->data_cadastro = date("Y-m-d");
		else
		$this->data_cadastro = $dataCad;
	}

	protected function setIdUserAlt($idUserAlt){
		if(is_numeric($idUserAlt))
		$this->id_usuario_alt = (int)$idUserAlt;
		$this->data_alteracao = date("Y-m-d");
	}

	protected function getObjSmarty(){
		if($this->objSmarty == null)
		$this->objSmarty = ControlSmarty::getSmarty();
		return $this->objSmarty;
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
		try{
			self::getObjUploadFile()->uploadFile($strField, $strCaminho, $strNomeFoto);
		}catch(UploadException $e){
			throw new UploadException($e);
		}
	}

	/**
	 * Método responsável pelo tratamento do upload de arquivos multiplos
	 *
	 * @param String $strNomeFoto: Novo nome do campo da foto
	 * @param String $strField: campo no formulário contando o campo file
	 * @param String $strCaminho: caminho fisico da pasta de destino da imagem
	 *
	 */
	protected function uploadMultiploFiles($strNomeFoto,$strField,$strCaminho,$indice){
		try{
			self::getObjUploadFile()->uploadMultiploFile($strField, $strCaminho, $strNomeFoto,$indice);
		}catch(UploadException $e){
			throw new UploadException($e);
		}
	}

	/**
	 * Método responsável pela copia do arquivo - zip -> pasta
	 *
	 * @param String $nomeArquivo: nome do arquivo da foto
	 * @param String $pastaOrigem: caminho fisico da pasta de origem da imagem
	 * @param String $pastaDestino: caminho fisico da pasta de destino da imagem
	 *
	 */
	protected function copyFile($pastaOrigem, $pastaDestino, $nomeArquivo){
		try{
			self::getObjUploadFile()->copyFile($pastaOrigem, $pastaDestino, $nomeArquivo);
		}catch(UploadException $e){
			throw new UploadException($e);
		}
	}


	protected function getObjUploadFile(){
		if($this->objUpload == null)
		$this->objUpload = new Upload();
		return $this->objUpload;
	}

	protected function apagaArquivoFisico($id,$campo,$pastaImg){
		$result = self::buscaCampos($id,0);
		if(isset($result[$campo]) && $result[$campo] != "" && $result[$campo] != null){
			if(is_file($pastaImg.$result[$campo])){
				unlink($pastaImg.$result[$campo]);
			}else{
				throw new DaoException("Não foi possível deletar o arquivo.");
				return;
			}
		}
	}


	/**
	 * Método para verificar a extensão do arquivo
	 *
	 * deve-se passar o typo do arquivo.
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 26/10/2010
	 */
	protected function getExtFiles($file){
		switch($file){
			case "":
				return "";
				break;
			case "image/jpeg":
			case "image/jpg":
			case "image/pjpeg":
				return ".jpg";
				break;
			case "image/bmp":
				return ".bmp";
				break;
			case "image/png":
			case "image/x-png":
				return ".png";
				break;
			case "image/gif":
				return ".gif";
				break;
			case "application/zip":
				return ".zip";
				break;
			case "text/css":
				return ".css";
				break;
			case "text/x-c":
				return ".js";
				break;
			case "application/x-javascript":
				return ".js";
				break;
			case "application/pdf":
				return ".pdf";
				break;
			default:
				throw new DaoException("Formato de arquivo não aceito pelo sistema: ".$file);
		}
	}

}
?>