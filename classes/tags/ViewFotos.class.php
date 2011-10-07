<?php
require_once (FWK_TAGS."AbsTagsFwk.class.php");
require_once(FWK_DAO."FotosDAO.class.php");

class ViewFotos extends AbsTags{

	private $objFoto;
	private $strTitle;
	private $strCssFoto;
	private $altura;
	private $largura;



	/**
	 * Função para a criação do thumb da imagem
	 * @param IdObj - Id da foto
	 * @param Param1 - Largura
	 * @param Param2 - Altura
	 * @param Param3 - Se vai ter marca d'água ou não | string true or false
	 *
	 * @author Matheus
	 * @since 1.0 - 21/01/2011
	 */
	public function getThumbImg(){
		$arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());

		if(!file_exists(PASTA_UPLOADS_FOTOS.$arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null){
			$arrFoto = self::getObjFoto()->buscaCampos("1");
		}

		$arrFoto = Utf8Parsers::arrayUtf8Encode($arrFoto);

		//Busca o nome do arquivo.
		$arrLinkImg["img"] = $arrFoto["nome_arquivo"];
		//Seta a largura da imagem.
		if(self::getLargura() != "")
		$arrLinkImg["w"] = self::getLargura();
		else
		$arrLinkImg["w"] = self::getParam1();

		//Seta a altura da imagem.
		if(self::getAltura() != "")
		$arrLinkImg["h"] = self::getAltura();
		else
		$arrLinkImg["h"] = self::getParam2();

		//True or False para marca d'agua.
		$arrLinkImg["marca"] = (self::getParam3())?self::getParam3():false;
		
		//Retorna o link criptografado do thumb da imagem.
		$link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

		//Seta o link da img.
		parent::getObjSmarty()->assign("LINK", $link);

		parent::getObjSmarty()->assign("LARGURA_FOTO", $arrLinkImg["w"]);

		parent::getObjSmarty()->assign("ALTURA_FOTO", $arrLinkImg["h"]);
		//Seta o alt da imagem.
		parent::getObjSmarty()->assign("TITLE_FOTO",self::getTitle());

		parent::getObjSmarty()->assign("CSS_FOTO",self::getCssFoto());

		parent::getObjSmarty()->assign("TITULO",$arrFoto["titulo_foto"]);
		
		//True or False para pre-loading.
		parent::getObjSmarty()->assign("PRE_LOADING",(self::getParam4())?self::getParam4():"true");

		$strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL."tagFotoThumb.tpl");
		print ($strTela);
	}

	/**
	 * Função para a criação do link de apliar imagem
	 * @param IdObj - Id da foto
	 *
	 * @author Matheus
	 * @since 1.0 - 21/01/2011
	 */
	public function getMaximizaImg(){
		$arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());

		if(!file_exists(PASTA_UPLOADS_FOTOS.$arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null){
			$arrFoto = self::getObjFoto()->buscaCampos("1");
		}
		$arrFoto = Utf8Parsers::arrayUtf8Encode($arrFoto);

		//Busca o nome do arquivo.
		$arrLinkImg["img"] = $arrFoto["nome_arquivo"];
		//Seta a largura da imagem.
		$arrLinkImg["w"] = 0;
		//Seta a altura da imagem.
		$arrLinkImg["h"] = 0;
		//Retorna o link criptografado da imagem thumb ampliada.
		$link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

		//Seta o link para ampliar img.
		parent::getObjSmarty()->assign("LINK", $link);
		//Seta o alt da imagem.
		parent::getObjSmarty()->assign("TITULO",$arrFoto["titulo_foto"]);

		$strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL."tagFotoMaximiza.tpl");
		print ($strTela);
	}

	public function getLinkFoto(){
		$arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());

		if(!file_exists(PASTA_UPLOADS_FOTOS.$arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null){
			$arrFoto = self::getObjFoto()->buscaCampos("1");
		}

		$arrFoto = Utf8Parsers::arrayUtf8Encode($arrFoto);

		//Busca o nome do arquivo.
		$arrLinkImg["img"] = $arrFoto["nome_arquivo"];
		//Seta a largura da imagem.
		$arrLinkImg["w"] = self::getLargura();
		//Seta a altura da imagem.
		$arrLinkImg["h"] = self::getAltura();
		//True or False para marca d'agua.
		$arrLinkImg["marca"] = self::getParam3();
		//Retorna o link criptografado do thumb da imagem.
		$link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));


		//Seta o link para ampliar img.
		print(RET_SERVIDOR."thumb.php?img=".$link);
	}

	private function getObjFoto() {
		if ($this->objFoto == null)
		$this->objFoto = new FotosDAO();
		return $this->objFoto;
	}

	public function setTitle($title){
		$this->strTitle = $title;
	}
	public function getTitle(){
		return $this->strTitle;
	}

	public function setCssFoto($cssFoto){
		$this->strCssFoto = $cssFoto;
	}
	public function getCssFoto(){
		return $this->strCssFoto;
	}

	public function setAltura($strAltura){
		$this->altura = $strAltura;
	}
	public function getAltura(){
		return $this->altura;
	}
	public function setLargura($strLargura){
		$this->largura = $strLargura;
	}
	public function getLargura(){
		return $this->largura;
	}

	public function executeTag(){

	}

}
?>