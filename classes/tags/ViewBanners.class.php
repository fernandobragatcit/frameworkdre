<?php
require_once (FWK_TAGS."AbsTagsFwk.class.php");
require_once (FWK_TAGS."ViewFotos.class.php");
require_once(FWK_DAO."BannersDAO.class.php");

class ViewBanners extends AbsTagsFwk{

	private $largura = "";
	private $altura = "";
	private $nome = "";
	private $title = "";
	private $link = "";
	private $randon = "";
	private $metodo = "";
	private $objBanner;


	/**
	 * Método para execução da tag
	 * Busca-se os dados registrados no banco, mas os dados da tags tem previlegio,
	 * ou seja, caso a tag tenha os dados setados, sobreenscrevem os dados do banco.
	 *
	 * (non-PHPdoc)
	 * @see AbsTagsFwk::executeTag()
	 */
	public function executeTag(){
		
		if(self::getMetodo())
		
		if(self::getNome() != null && self::getNome() != ""){
			$arrBanner = self::getObjBanner()->getBanner(trim(self::getNome()));
		}else{
			throw new TagsException("Não foi passado o NOME, identificador do Banner");
		}
		
		if(self::getPosicao() != null && self::getPosicao() != ""){
			parent::getObjSmarty()->assign("POSICAO",self::getPosicao());
		}else{
			throw new TagsException("Não foi passado a POSICAO para o Banner");
		}
		
		if(self::getDescricao() != null && self::getDescricao() != ""){
			parent::getObjSmarty()->assign("DESCRICAO",self::getDescricao());
		}else{
			throw new TagsException("Não foi passado a DESCRICAO para o Banner");
		}

		if(self::getLargura()!=null && self::getLargura() != ""){
			parent::getObjSmarty()->assign("LARGURA",self::getLargura());
		}else{
			throw new TagsException("Não foi passado a Largura do Banner");
		}
		if(self::getAltura()!= null && self::getAltura() != ""){
			parent::getObjSmarty()->assign("ALTURA",self::getAltura());
		}else{
			throw new TagsException("Não foi passado a Altura do Banner");
		}

		if(self::getLink()!= null && self::getLink() != ""){
			$aux = explode("/",self::getLink());
			if(end($aux) == "foto"){
				parent::getObjSmarty()->assign("LINKFOTO",URL_DEP_IMGS.$aux[0]);
			}else
				parent::getObjSmarty()->assign("LINKFOTO",self::getLink());
		}elseif ($arrBanner["link_banner"] != null && $arrBanner["link_banner"] != ""){
			parent::getObjSmarty()->assign("LINKFOTO",$arrBanner["link_banner"]);
		}else{
			parent::getObjSmarty()->assign("LINKFOTO","javascript:void(0);");
		}

		if(self::getTitle()!= null && self::getTitle() != ""){
			parent::getObjSmarty()->assign("TITLE",self::getTitle());
		}elseif ($arrBanner["title_banner"] != null && $arrBanner["title_banner"] != ""){
			parent::getObjSmarty()->assign("TITLE",$arrBanner["title_banner"]);
		}else{
			parent::getObjSmarty()->assign("TITLE",trim(self::getNome()));
		}

		parent::getObjSmarty()->assign("IDFOTO",$arrBanner["id_foto"]);
		
		
		
		$strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL."tagBanner.tpl");
		
		//self::debuga(self::getPosicao());
		
		print ($strTela);

	}
	
	public function bannerAleatorio(){
		if(self::getNome() != null && self::getNome() != ""){
			$arrBanner = self::getObjBanner()->getBannerAleatorio(trim(self::getNome()));
		}else{
			throw new TagsException("Não foi passado o NOME, categoria do Banner");
		}
		if(self::getLargura()!=null && self::getLargura() != ""){
			parent::getObjSmarty()->assign("LARGURA",self::getLargura());
		}else{
			throw new TagsException("Não foi passado a Largura do Banner");
		}
		if(self::getAltura()!= null && self::getAltura() != ""){
			parent::getObjSmarty()->assign("ALTURA",self::getAltura());
		}else{
			throw new TagsException("Não foi passado a Altura do Banner");
		}
		parent::getObjSmarty()->assign("IDFOTO",$arrBanner["id_foto"]);
		$strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL."tagBannerSimples.tpl");
		print ($strTela);
	}
	
	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}
	public function getDescricao(){
		return $this->descricao;
	}
	
	public function setCaminho($caminho){
		$this->caminho = $caminho;
	}
	public function getCaminho(){
		return $this->caminho;
	}
	
	public function setPosicao($posicao){
		$this->posicao = $posicao;
	}
	public function getPosicao(){
		return $this->posicao;
	}
	
	public function setIdentificador($identificador){
		$this->identificador = $identificador;
	}

	public function getIdentificador(){
		return $this->identificador;
	}

	
	
	public function setLargura($largura){
		$this->largura = $largura;
	}

	public function getLargura(){
		return $this->largura;
	}

	public function setAltura($altura){
		$this->altura = $altura;
	}

	public function getAltura(){
		return $this->altura;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	public function getNome(){
		return $this->nome;
	}

	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle(){
		return $this->title;
	}

	public function setLink($link){
		$this->link = $link;
	}
	public function getLink(){
		return $this->link;
	}

	public function setRandon($randon){
		$this->randon = $randon;
	}
	public function getRandon(){
		return $this->randon;
	}

	private function getObjBanner(){
		if($this->objBanner == null)
		$this->objBanner = new BannersDAO();
		return $this->objBanner;
	}
	
	

	public function setMetodo($metodo){
		$this->metodo = $metodo;
	}
	public function getMetodo(){
		return $this->metodo;
	}
	
	private function getObjViewFotos(){
		if($this->objViewFotos == null)
		$this->objViewFotos = new ViewFotos();
		return $this->objViewFotos;
	}

}

?>