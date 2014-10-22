<?php
require_once(FWK_MODEL."AbsModelDao.class.php");
require_once (FWK_DAO."FotosDAO.class.php");

class BannersDAO extends AbsModelDao{

	private $objFotos;

	public $_table = "fwk_banner";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_banner";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 21/04/2011
	 */
	public function cadastrar($xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_BANNERS);
			$objDoc->cadastrar($xml, $post, $file);
			$this->id_banner = $objDoc->getIdDocumento();
			
			$this->id_portal = parent::getCtrlConfiguracoes()->getIdPortal();

			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($post);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 21/04/2011
	 */
	public function alterar($id,$xml,$post,$file){
		try{
			$objDoc = new DocumentoDAO();
			$objDoc->setTipoDocumento(TIPODOC_BANNERS);
			$objDoc->alterar(((!isset($id)||$id=="")?null:$id), $xml, $post, $file);
			$this->id_banner = $objDoc->getIdDocumento();
			
			$this->id_portal = parent::getCtrlConfiguracoes()->getIdPortal();

			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
			if(self::ErrorMsg()){
				print("<h1>".__CLASS__."</h1>");
				print("<pre>");
				print_r($this);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
	}

	public function getIdBanner(){
		return $this->id_banner;
	}


	public function alterarStatusBanner($id) {
		try {
			$this->id_banner = $id;
			$arrCampos = self::buscaCampos($id, 0);
			self::alteraPostAutoUtf8($arrCampos,$id);
			$this->nome_estilo_css = $arrCampos["nome_imagem"];
			if ($arrCampos["status_banner"] == "N") {
				$this->status_banner = "S";
			}
			if ($arrCampos["status_banner"] == "S") {
				$this->status_banner = "N";
			}
			self::replace();
		} catch (DaoException $e) {
			throw new DaoException($e->getMensagem());
		}
	}

	public function deletarBanner($id) {
		try {
			$arrCampos = self::buscaCampos($id,0);
			self::getObjFotos()->deletaFoto($arrCampos["id_foto"]);
			self::deletar($id);
		} catch (CrudException $e) {
			throw new CrudException($e->getMensagem());
		}
	}

	private function getObjFotos(){
		if($this->objFotos == null)
			$this->objFotos = new FotosDAO();
		return $this->objFotos;
	}

	public function getBanner($strBanner){
		$strQuery = "SELECT
						id_foto, id_categoria_banner, nome_imagem, title_banner, link_banner, status_banner, id_banner
					FROM 
						fwk_banner
					WHERE
						UPPER(nome_imagem) = '".strtoupper($strBanner)."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().")";
		$arrDados = ControlDb::getRow($strQuery,3);
		return $arrDados;
	}

	public function getBannerAleatorio($strBanner){
		$strQuery = "SELECT
						id_foto, title_banner, link_banner
					FROM 
						fwk_banner fb
						INNER JOIN fwk_tipo_basico ftb ON ftb.id_tipo_basico = fb.id_categoria_banner
					WHERE
						UPPER(ftb.desc_tipo_basico) = '".strtoupper($strBanner)."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().") 
					ORDER BY 
						RAND()";
		$arrDados = ControlDb::getRow($strQuery,3);
		return $arrDados;
	}

	public function getBannerAleatorioById($idCatBanner){
		$strQuery = "SELECT
						id_foto, title_banner, link_banner
					FROM 
						fwk_banner fb
						INNER JOIN fwk_tipo_basico ftb ON ftb.id_tipo_basico = fb.id_categoria_banner
					WHERE
						ftb.id_tipo_basico = '".$idCatBanner."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().") 
					ORDER BY 
						RAND()";
		$arrDados = ControlDb::getRow($strQuery,3);
		return $arrDados;
	}
	
	public function getBannersByStrCateg($strCateg){
		$strQuery = "SELECT
						id_foto, title_banner, link_banner,nome_imagem
					FROM 
						fwk_banner fb
						INNER JOIN fwk_tipo_basico ftb ON ftb.id_tipo_basico = fb.id_categoria_banner
					WHERE
						UPPER(ftb.desc_tipo_basico) = '".strtoupper($strCateg)."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().") 
					ORDER BY 
						nome_imagem";
		$arrDados = ControlDb::getAll($strQuery,3);
		return $arrDados;
	}
	public function getBannersComDescFotoByStrCateg($strCateg){
		$strQuery = "SELECT
						fb.id_foto, fb.title_banner, fb.link_banner,fb.nome_imagem,f.nome_arquivo
					FROM 
						fwk_banner fb 
                                                INNER JOIN fwk_fotos f ON fb.id_foto=f.id_foto 
						INNER JOIN fwk_tipo_basico ftb ON ftb.id_tipo_basico = fb.id_categoria_banner
					WHERE
						UPPER(ftb.desc_tipo_basico) = '".strtoupper($strCateg)."' AND
						(id_portal = ".PORTAL_SISTEMA." OR id_portal = ".parent::getCtrlConfiguracoes()->getIdPortal().") 
					ORDER BY 
						nome_imagem";
		$arrDados = ControlDb::getAll($strQuery,0);
		return $arrDados;
	}

}
?>