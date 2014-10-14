<?php
require_once (FWK_TAGS."AbsTagsFwk.class.php");
require_once(FWK_DAO."ArquivosDAO.class.php");

class ViewArquivos extends AbsTags{

	private $objArquivo;
	private $strTitle;
	private $strCssLink;

	/**
	 * Função para a criação do link do arquivo
	 * @param IdObj - Id do arquivo
	 *
	 * @author Matheus
	 * @since 1.0 - 19/01/2012
	 */
	public function getLinkArquivo(){
		$strClass = null;
		$arrArquivo = self::getObjArquivo()->buscaCampos(self::getIdObj());
		if(self::getCssLink() != ""){
			$strClass = ' class="'.self::getCssLink().'"';
		}
		print('<a href="'.RET_SERVIDOR.'arquivos/bancofiles/'.$arrArquivo["file_arquivo"].'" target="_blank" '.$strClass.' title="'.$arrArquivo["file_arquivo"].'">'.$arrArquivo["file_arquivo"].'</a>');
	}

	/**
	 * Função que retorna a url do arquivo
	 * @param IdObj - Id do arquivo
	 *
	 * @author Matheus
	 * @since 1.0 - 19/01/2012
	 */
	public function getUrlArquivo(){
		$arrArquivo = self::getObjArquivo()->buscaCampos(self::getIdObj());
		print(RET_SERVIDOR."arquivos/bancofiles/".$arrArquivo["file_arquivo"]);
	}

	/**
	 * Função que retorna o nome do arquivo
	 * @param IdObj - Id do arquivo
	 *
	 * @author Matheus
	 * @since 1.0 - 19/01/2012
	 */
	public function getNomeArquivo(){
		$arrArquivo = self::getObjArquivo()->buscaCampos(self::getIdObj());
		print $arrArquivo["file_arquivo"];
	}

	private function getObjArquivo() {
		if ($this->objArquivo == null)
		$this->objArquivo = new ArquivosDAO();
		return $this->objArquivo;
	}

	public function setTitle($title){
		$this->strTitle = $title;
	}
	public function getTitle(){
		return $this->strTitle;
	}

	public function setCssLink($cssLink){
		$this->strCssLink = $cssLink;
	}
	public function getCssLink(){
		return $this->strCssLink;
	}

	public function executeTag(){

	}

}
?>