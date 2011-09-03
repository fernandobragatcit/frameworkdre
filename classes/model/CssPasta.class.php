<?php
require_once(FWK_MODEL."AbsModelDao.class.php");
require_once(FWK_CONTROL."ControlXML.class.php");
require_once(FWK_EXCEPTION."XMLException.class.php");
require_once(FWK_CONTROL."ControlSessao.class.php");

class CssPasta extends AbsModelDao {

	/**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_estilo_css";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_estilo_css";

	/**
	 * Nome do input:file para ser tratado por ser um vetor a parte
	 */
	private $strNomeCampo = "nome_estilo_css";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
	public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			$extensao = self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tiposArquivos = array(".css");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			if(in_array($extensao,$this->tiposArquivos)){
				self::salvaPostAutoUtf8($post);
				$this->nome_estilo_css = $file[$this->strNomeCampo]["name"];
				$this->status = "S";
				self::getObjUploadFile()->setOverwrite("N");
				self::uploadFile("", $this->strNomeCampo, DEPOSITO_CSS);
				$this->data_cadastro = date("Y-m-d");
				self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
				self::salvar();

				self::setCssArea($this->nome_estilo_css,$this->id_estilo_css,$this->status);
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
			$this->id_estilo_css = $id;
			$arrCampos = self::buscaCampos($id, 0);
			self::validaForm($xml, $post);
			$this->nome_estilo_css = $file[$this->strNomeCampo]["name"];
			$extensao = self::getExtFile($file[$this->strNomeCampo]["type"]);
			$this->tiposArquivos = array(".css");
			self::getObjUploadFile()->setExtArquivo($this->tiposArquivos);
			if (in_array($extensao, $this->tiposArquivos)) {
				if ($file[$this->strNomeCampo]["name"] != "") {
					try {
						self::apagaArquivoFisico($id, $this->strNomeCampo, DEPOSITO_CSS);
					} catch (DaoException $e) {
						//mesmo que não consiga apagar o arquivo, procigo com o processo de upload
					}
					self::alteraPostAutoUtf8($post, $id);
					self::getObjUploadFile()->setOverwrite("N");
					self::uploadFile("", $this->strNomeCampo, DEPOSITO_CSS);
					$this->status = $arrCampos["status"];

					self::apagaCssXml($id);
					self::setCssArea($this->nome_estilo_css, $arrCampos["id_estilo_css"], $arrCampos["status"]);
				} else {
					$this->nome_estilo_css = $arrCampos[$this->strNomeCampo];
					self::setCssArea($this->nome_estilo_css, $arrCampos["id_estilo_css"], $arrCampos["status"]);
				}
				self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
				self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
				self::replace();
			}
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function alterarStatusCss($id) {
		try {
			$this->id_estilo_css = $id;
			$arrCampos = self::buscaCampos($id, 0);
			$this->nome_estilo_css = $arrCampos["nome_estilo_css"];
			if ($arrCampos["status"] == "N") {
				self::apagaCssXml($id);
				$this->status = "S";
				self::setCssArea($this->nome_estilo_css,$this->id_estilo_css,$this->status);
			}
			if ($arrCampos["status"] == "S") {
				self::apagaCssXml($id);
				$this->status = "N";
				self::setCssArea($this->nome_estilo_css,$this->id_estilo_css,$this->status);
			}
			self::setIdUserCad($arrCampos["id_usuario_cad"], $arrCampos["data_cadastro"]);
			self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			self::replace();
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function deletarCss($id) {
		try {
			self::apagaArquivoFisico($id, $this->strNomeCampo, DEPOSITO_CSS);
			self::apagaCssXml($id);
			self::deletar($id);
		} catch (CrudException $e) {
			throw new CrudException($e->getMensagem());
		}
	}

	public function getExtFile($file) {
		return self::getExtFiles($file);
	}

	public function setCssArea($css, $id, $status) {
		$contador = 0;
		$retorno = 0;
		try {
			if (is_file(CONFIG_FILE))
				$dadosPortal = self::getDadosXmlPasta1(CONFIG_FILE);
			else
				return;

			if (!isset ($dadosPortal->portal->stilos)) {
				$dadosPortal->portal->addChild("stilos");
				$dadosPortal->portal->stilos->addChild("css", $css);
				$dadosPortal->portal->stilos->css->addAttribute("id", $id);
				$dadosPortal->portal->stilos->css->addAttribute("ativo", $status);
			} else {

				for ($j = 0; $j < count($dadosPortal->portal->stilos->css); $j++) {
					if ($dadosPortal->portal->stilos->css[$j]->attributes()->id == $id) {
						//este serve para alterar...
						$dadosPortal->portal->stilos->css[$j] = $css;
						$dadosPortal->portal->stilos->css[$j]->attributes()->id = $id;

						$dadosPortal->portal->stilos->css[$j]->attributes()->ativo = $status;
						$retorno += 1;
						return;
					} else {
						$retorno += 0;
					}
				}
				if ($retorno != 1) {
					//cria a estrutura onde fica salvo o css
					//cria os nós e a estrutura para o título
					$dadosPortal->portal->stilos->addChild("css", $css);
					$index = count($dadosPortal->portal->stilos->css) - 1;
					$dadosPortal->portal->stilos->css[$index]->addAttribute("id", $id);
					$dadosPortal->portal->stilos->css[$index]->addAttribute("ativo", $status);
				}
			}
			self::getObjXml()->salvaXml(CONFIG_FILE, $dadosPortal->asXML());
		} catch (XMLException $e) {
			throw new XMLException($e);
		}
	}

	public function apagaCssXml($id) {
		try {
			if (is_file(CONFIG_FILE))
				$dadosPortal = self::getDadosXmlPasta1(CONFIG_FILE);
			else
				return;
			for ($i = 0; $i < count($dadosPortal->portal->stilos->css); $i++) {
				if ($dadosPortal->portal->stilos->css[$i]->attributes()->id == $id)
					unset ($dadosPortal->portal->stilos->css[$i]);
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