<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

class UsuarioAdmin extends AbsModelCruds{

    /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_usuario";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_usuario";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			$this->password_usuario = self::getObjCripto()->cryptMd5($post["password_usuario"]);
	    	$this->data_cadastro = date("Y-m-d");
			self::salvar();
			self::setGrupoUsuario($post["grupo_usuario"]);
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

    private function setGrupoUsuario($grupoUsuario){
    	//Salva-se a grupo selecionado para o usuario
		if(isset($grupoUsuario) && $grupoUsuario != ""){
			self::insertGrupoUsuario($grupoUsuario);
		}
		//caso o usuario não tenha a funcionalidade de escolher para qual grupo cadastrar o usuario
		//cadastra-o em todos que ele pertence
		else{
			$arrGrupos = self::getUsuarioSessao()->getGrupoUsuario();
			if(is_array($arrGrupos) && count($arrGrupos) > 0){
				ControlDb::getBanco()->BeginTrans();
				foreach ($arrGrupos as $grupo) {
					self::insertGrupoUsuario($grupo);
				}
				ControlDb::getBanco()->CommitTrans();
			}
		}
    }

    private function insertGrupoUsuario($grupoUsuario){
		ControlDb::getBanco()->Execute("insert into fwk_grupo_usuario (id_usuario,id_grupo)
								values ('".$this->id_usuario."', '".$grupoUsuario."')");
    }

    public function deletarUsuario($id){
		$arrDados = array("table" => "fwk_grupo_usuario",
						  "campo" => "id_usuario",
						  "valor" => $id);
		if(!ControlDb::delRowTable($arrDados))
			throw new CrudException("Imppossível deletar o dado da tabela ".$this->_table);
		parent::deletar($id);
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 17/08/2008
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_usuario = $id;
			$arrCampos = self::buscaCampos($id);
			self::setIdUserCad($arrCampos["id_usuario_cad"]);
			self::setIdUserAlt(self::getUsuarioSessao()->getIdUsuario());
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			$this->password_usuario = self::getObjCripto()->cryptMd5($post["password_usuario"]);
			self::replace();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }
}
?>