<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class UsuariosFacebookDAO extends AbsModelDao{

	public $_table = "fwk_facebook";

	public $_id = "id_facebook";

    public function cadastrar($post){
		try{
				self::salvaPostAutoUtf8($post);
		    	$this->idioma_usuario = self::getUsuarioSessao()->getIdioma();
		    	$this->data_cadastro = date("Y-m-d");
				self::salvar();
		}catch(DaoException $e){
			throw new DaoException($e->getMensagem());
		}

    }
    
    public function verificaId($id){
    	$strQuery = "SELECT COUNT(*) FROM ".$this->_table." WHERE ".$this->_id." = '".$id."'";
    	return ControlDb::getRow($strQuery);
    }    
	
    public function verificaEmail($email){
    	$strQuery = "SELECT COUNT(*) FROM ".$this->_table." WHERE email_usuario = '".$email."'
    				UNION (SELECT COUNT(*) FROM  fwk_usuario WHERE email_usuario = '".$email."')";
    	return end(ControlDb::getRow($strQuery));
    }
    
    public function getIdFacebook(){
    	return $this->id_facebook;
    }
}
?>