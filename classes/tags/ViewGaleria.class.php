<?php
require_once (FWK_TAGS."AbsTagsFwk.class.php");
require_once (FWK_TAGS."ViewFotos.class.php");
require_once (FWK_DAO."GaleriasDAO.class.php");
require_once (FWK_DAO."FotosGaleriaDAO.class.php");

class ViewGaleria extends AbsTagsFwk{

	private $largura = "";
	private $altura = "";
	private $nome = "";
	private $title = "";
	private $link = "";
	private $randon = "";
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
		
		
		if(self::getNome() != null && self::getNome() != ""){
			$arrFotos = self::getObjFotosGaleria()->getFotosGaleria(trim(self::getNome()));
		}else{
			throw new TagsException("Não foi passado o NOME, identificador a Galeria");
		}
		parent::getObjSmarty()->assign("ARRGALERIA",$arrFotos);
	}
	
	public function setNome($Nome){
		$this->nome = $Nome;
	}
	public function getNome(){
		return $this->nome;
	}
	
	public function setIdGaleria($idGaleria){
		$this->idGaleria = $idGaleria;
	}
	public function getIdGaleria(){
		return $this->idGaleria;
	}
	
	private function getObjGaleria(){
		if($this->objGaleria == null)
		$this->objGaleria = new GaleriasDAO();
		return $this->objGaleria;
	}

	private function getObjFotosGaleria(){
		if($this->objFotosGaleria == null)
		$this->objFotosGaleria = new FotosGaleriaDAO();
		return $this->objFotosGaleria;
	}

	private function getObjViewFotos(){
		if($this->objViewFotos == null)
		$this->objViewFotos = new ViewFotos();
		return $this->objViewFotos;
	}

}

?>