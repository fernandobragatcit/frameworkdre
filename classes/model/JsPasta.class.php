<?php
require_once (FWK_MODEL."AbsModelDao.class.php");
require_once (FWK_CONTROL."ControlXML.class.php");
require_once (FWK_EXCEPTION."XMLException.class.php");
require_once (FWK_CONTROL."ControlSessao.class.php");

class JsPasta extends AbsModelDao {
	/**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_javascript";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_javascript";

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "nome_javascript";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
	public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			$extensao = self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tiposArquivos = array(".js");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			if(in_array($extensao,$this->tiposArquivos)){
				self::salvaPostAutoUtf8($post);
				$this->nome_javascript = $file[$this->strNomeCampo]["name"];
				$this->status = "S";
				self::getObjUploadFile()->setOverwrite("N");
				self::uploadFile("", $this->strNomeCampo, DEPOSITO_JS);
				$this->data_cadastro = date("Y-m-d");
				self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
				self::salvar();

				self::setJsArea($this->nome_javascript,$this->id_javascript,$this->status);
				//self::vaiPara(self::getStringClass()."&msg=Ítem alterado com sucesso!");
			}
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}catch(UploadException $e2){
			throw new UploadException($e2->getMensagem());
		}
    }

	public function alterar($id, $xml, $post, $file) {
		try {
			$this->id_javascript = $id;
			$arrCampos = self::buscaCampos($id, 0);
			self::validaForm($xml, $post);
			$this->nome_javascript = $file[$this->strNomeCampo]["name"];
			$extensao = self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tiposArquivos = array(".js");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			if (in_array($extensao, $this->tiposArquivos)) {
				if ($file[$this->strNomeCampo]["name"] != "") {
					try {
						self::apagaArquivoFisico($id, $this->strNomeCampo, DEPOSITO_JS);
					} catch (DaoException $e) {
						//mesmo que não consiga apagar o arquivo, procigo com o processo de upload
					}
					self::alteraPostAutoUtf8($post, $id);
					self::getObjUploadFile()->setOverwrite("N");
					self::uploadFile("", $this->strNomeCampo, DEPOSITO_JS);
					$this->status = $arrCampos["status"];

					self::apagaJsXml($id);
					self::setJsArea($this->nome_javascript, $arrCampos["id_javascript"], $arrCampos["status"]);
				} else {
					$this->nome_javascript = $arrCampos[$this->strNomeCampo];
					self::setJsArea($this->nome_javascript, $arrCampos["id_javascript"], $arrCampos["status"]);
				}
				self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
				self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
				self::replace();
			}
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function alterarStatusJs($id) {
		try {
			$this->id_javascript = $id;
			$arrCampos = self::buscaCampos($id, 0);
			$this->nome_javascript = $arrCampos["nome_javascript"];
			if ($arrCampos["status"] == "N") {
				self::apagaJsXml($id);
				$this->status = "S";
				self::setJsArea($this->nome_javascript,$this->id_javascript,$this->status);
			}
			if ($arrCampos["status"] == "S") {
				self::apagaJsXml($id);
				$this->status = "N";
				self::setJsArea($this->nome_javascript,$this->id_javascript,$this->status);
			}
			self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
			self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			self::replace();
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function deletarJs($id) {
		try {
			self::apagaArquivoFisico($id, $this->strNomeCampo, DEPOSITO_JS);
			self::apagaJsXml($id);
			self::deletar($id);
		} catch (CrudException $e) {
			throw new CrudException($e->getMensagem());
		}
	}

	public function getExtFile($file) {
		return self::getExtFiles($file);
	}

	public function setJsArea($js, $id, $status) {
		$contador = 0;
		$retorno = 0;
		//die($js." -> ".$id);
		try {
			if (is_file(CONFIG_FILE))
				$dadosPortal = self::getDadosXmlPasta1(CONFIG_FILE);
			else
				return;

			if (!isset ($dadosPortal->portal->scripts)) {
				$dadosPortal->portal->addChild("scripts");
				$dadosPortal->portal->scripts->addChild("js", $js);
				$dadosPortal->portal->scripts->js->addAttribute("id", $id);
				$dadosPortal->portal->scripts->js->addAttribute("ativo", $status);
			} else {

				for ($j = 0; $j < count($dadosPortal->portal->scripts->js); $j++) {
					if ($dadosPortal->portal->scripts->js[$j]->attributes()->id == $id) {
						//este serve para alterar...
						$dadosPortal->portal->scripts->js[$j] = $js;
						$dadosPortal->portal->scripts->js[$j]->attributes()->id = $id;

						$dadosPortal->portal->scripts->js[$j]->attributes()->ativo = $status;
						$retorno += 1;
						return;
					} else {
						$retorno += 0;
					}
				}
				if ($retorno != 1) {
					//cria a estrutura onde fica salvo o js
					//cria os nós e a estrutura para o título
					$dadosPortal->portal->scripts->addChild("js", $js);
					$index = count($dadosPortal->portal->scripts->js) - 1;
					$dadosPortal->portal->scripts->js[$index]->addAttribute("id", $id);
					$dadosPortal->portal->scripts->js[$index]->addAttribute("ativo", $status);
				}
			}
			self::getObjXml()->salvaXml(CONFIG_FILE, $dadosPortal->asXML());
		} catch (XMLException $e) {
			throw new XMLException($e);
		}
	}

	public function apagaJsXml($id) {
		try {
			if (is_file(CONFIG_FILE))
				$dadosPortal = self::getDadosXmlPasta1(CONFIG_FILE);
			else
				return;
			for ($i = 0; $i < count($dadosPortal->portal->scripts->js); $i++) {
				if ($dadosPortal->portal->scripts->js[$i]->attributes()->id == $id)
					unset ($dadosPortal->portal->scripts->js[$i]);
			}
			self::getObjXml()->salvaXml(CONFIG_FILE, $dadosPortal->asXML());
		} catch (XMLException $e) {
			throw new XMLException($e);
		}
	}

	private function getDadosXmlPasta1($strXml) {
		try {
			return self::getObjXml()->getXML($strXml);
		} catch (XMLException $e) {
			throw new XMLException($e);
		}
	}

	private function getObjXml() {
		if ($this->objCtrlXml == null)
			$this->objCtrlXml = new ControlXML();
		return $this->objCtrlXml;
	}
}
?>