<?php
require_once (FWK_TAGS."AbsTagsFwk.class.php");
require_once(FWK_DAO."FicheiroDAO.class.php");

class ViewFicheiro extends AbsTagsFwk{

	private $identificador = "";
	private $id = "";
	private $variavel = "";
	private $objFicheiro;


	/**
	 * Método para execução da tag
	 * Busca-se os dados registrados no banco, mas os dados da tags tem previlegio,
	 * ou seja, caso a tag tenha os dados setados, sobreenscrevem os dados do banco.
	 *
	 * (non-PHPdoc)
	 * @see AbsTagsFwk::executeTag()
	 */
	public function executeTag(){
		$arrDados = array();
		if(self::getId()!=null && self::getId()!=""){
			$arrDados = self::getObjFicheiro()->getFicheiroById(intval(self::getId()));
		}elseif(self::getIdentificador()!=null && self::getIdentificador()!=""){
			$arrDados = self::getObjFicheiro()->getFicheiroByIdentificador(self::getIdentificador());
		}else{
			throw new TagsException("Não foi passado UM identificador para o ficheiro.");
		}
		
		if(self::getVariavel()!=null &&self::getVariavel()!=""){
			$variavel = self::getVariavel();
		}
		$arrDados = FormataString::colocaHtmlVetor($arrDados);
		//self::debuga($arrDados);
		
		parent::getObjSmarty()->assign($variavel."_TITULO",$arrDados["titulo_ficheiro"]);
		parent::getObjSmarty()->assign($variavel."_BIGODE",$arrDados["bigode_ficheiro"]);
		parent::getObjSmarty()->assign($variavel."_IDFOTO",$arrDados["id_foto"]);
		parent::getObjSmarty()->assign($variavel."_TEXTO",$arrDados["texto_ficheiro"]);
		parent::getObjSmarty()->assign($variavel."_ID",$arrDados["id_ficheiro"]);
		parent::getObjSmarty()->assign($variavel."_IDALBUM",$arrDados["id_album"]);
		parent::getObjSmarty()->assign($variavel."_AREA",$arrDados["nome_area_ficheiro"]);
		
	}

	public function setIdentificador($ident){
		$this->identificador = $ident;
	}

	public function getIdentificador(){
		return $this->identificador;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setVariavel($variavel){
		$this->variavel = $variavel;
	}

	public function getVariavel(){
		return $this->variavel;
	}

	private function getObjFicheiro(){
		if($this->objFicheiro == null){
			$this->objFicheiro = new FicheiroDAO();
		}
		return $this->objFicheiro;
	}

}
?>