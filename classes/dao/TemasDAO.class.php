<?php
require_once(FWK_MODEL."AbsModelDao.class.php");


class TemasDAO extends AbsModelDao{

	public $_table = "fwk_temas";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_tema";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 12/12/2010
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
			if(self::ErrorMsg()){
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
	 * @since 1.0 - 12/12/2010
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_tema = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
			if(self::ErrorMsg()){
				print("<h1>Erro DocumentoDAO: </h1>");
				print("<pre>");
				print_r($this);
				die("<br/><br /><h1>".self::ErrorMsg()."</h1>");
			}
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}
    }

    public function getIdTema(){
    	return $this->id_tema;
    }
    
    public function getTemaUsuario($idUsuario){
    	if(!isset($idUsuario) ||$idUsuario == "")
    		return self::getTemaRandom();
    	$strQuery = "SELECT nome_tema, cabecalho_css, rodape_css 
				    	FROM usr_dados_complementares, fwk_temas 
				    	WHERE  usr_dados_complementares.id_tema = fwk_temas.id_tema
				    		AND usr_dados_complementares.id_usuario = '".$idUsuario."'";
    	
    	$arrDados =  ControlDB::getRow($strQuery,0);
    	
    	if(count($arrDados) == 0 || $arrDados["nome_tema"] == "Aleatório" 
    			|| $arrDados["cabecalho_css"] == null || $arrDados["rodape_css"] == null)
    		return self::getTemaRandom();
    	return $arrDados;
    	
    }
    
    public function getTemaById($idTema){
    	$strQuery = "SELECT nome_tema, cabecalho_css, rodape_css 
				    	FROM fwk_temas 
				    	WHERE  id_tema  = '".$idTema."'";
    	$arrDados =  ControlDB::getRow($strQuery,0);
    	return $arrDados;
    	
    }
    
    public function getTemaRandom(){
    	$strQuery = "SELECT 
    					nome_tema, cabecalho_css, rodape_css 
					FROM 
						fwk_temas 
					WHERE 
						cabecalho_css IS NOT NULL AND  rodape_css IS NOT NULL
					ORDER BY 
						RAND();";
    	
    	$arrDados =  ControlDB::getRow($strQuery,0);
    	return $arrDados;
    }

}
?>