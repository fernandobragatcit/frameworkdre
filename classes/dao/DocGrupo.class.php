<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class DocGrupo extends AbsModelDao{

	public $_table = "fwk_documento_grupo";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_grupo";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 *
	 * @author André Coura
	 * @since 1.0 - 22/02/2011 22:01
	 */
	public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->data_comentario = date("Y-m-d");
			$this->status_comentario = "L";
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
	 * @since 1.0 - 22/02/2011 22:01
	 */
	public function alterar($id,$xml,$post,$file){
		try{
			$this->id_grupo = $id;
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

	public function getIdGrupo(){
		return $this->id_grupo;
	}
	
	public function getIdDocumento(){
		return $this->id_usuario;
	}

	public function getIdDoc4Grupo($idUsr){
		$strQuery = "SELECT 
						dg.id_documento
					FROM 
						fwk_grupo gr 
						INNER JOIN fwk_grupo_usuario gu ON  gr.id_grupo = gu.id_grupo
						INNER JOIN fwk_usuario us ON gu.id_usuario = us.id_usuario
						INNER JOIN fwk_documento_grupo dg ON gr.id_grupo = dg.id_grupo
					WHERE 
						us.id_usuario ='".$idUsr."'";
		return ControlDb::getAll($strQuery);
	}


}
?>