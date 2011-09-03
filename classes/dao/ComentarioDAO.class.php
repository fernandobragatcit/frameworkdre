<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class ComentarioDAO extends AbsModelDao{

public $_table = "fwk_comentario";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_comentario";

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
			$this->id_comentario = $id;
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

    public function getIdComentario(){
    	return $this->id_comentario;
    }
    
    public function getDadosComentario($idDocumento, $inicio, $paginacao = true){
    	$strQuery = "SELECT 
					    	co.nome_comentario, co.data_comentario, 
					    	co.status_comentario, co.desc_comentario,
					    	dc.id_usuario, dc.id_foto
					FROM 
						fwk_comentario co 
						LEFT JOIN usr_dados_complementares dc 
						ON co.id_usuario = dc.id_usuario 
					WHERE 
						co.id_documento = '".$idDocumento."' 
					ORDER BY 
						co.id_comentario DESC ";
					if($paginacao)
						$strQuery .= " LIMIT
							".(((int)$inicio)*NUMS_COMENTARIOS).", ".NUMS_COMENTARIOS;
    	
		return ControlDb::getAll($strQuery); 
    }

}
?>