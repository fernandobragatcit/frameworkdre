<?php
require_once(FWK_MODEL."AbsModelCruds.class.php");

/**
 * DireitosAdmin
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 06/09/2009
 */
class DireitosAdmin extends AbsModelCruds{

    /**
	 * Atributo obrigatório para definição da tabela a ser trabalhada com Active_Record
	 */
	public $_table = "fwk_direitos";

	/**
	 * Chave primária para utilização em funções genéricas
	 */
	public $_id = "id_direitos";

	/**
	 * Método herdado abstrato a ser implementado em todas as classes modelo para CRUDs
	 */
    public function cadastrar($xml,$post,$file){
		try{
			self::validaForm($xml,$post);
			self::salvaPostAutoUtf8($post);
			self::salvar();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

	/**
	 * Método para alterar somente os campos que realmente foram alterados não mexendo com os outros
	 *
	 * @author André Coura
	 * @since 1.0 - 05/09/2008
	 */
    public function alterar($id,$xml,$post,$file){
		try{
			$this->id_direitos = $id;
			self::validaForm($xml,$post);
			self::alteraPostAutoUtf8($post,$id);
			self::replace();
		}catch(CrudException $e){
			throw new CrudException($e->getMensagem());
		}
    }

    public function setIdMenu($id_menu){
		$this->id_menu = $id_menu;
    }

    public function setIdPortal($id_portal){
		$this->id_portal = $id_portal;
    }

    public function setIdItemMenu($id_item_menu){
		$this->id_item_menu = $id_item_menu;
    }

    public function setNomeDireito($nome_direito){
		$this->nome_direito = $nome_direito;
    }

    public function getIdDireitosAdmin(){
    	if($this->id_direitos == null)
    		$this->id_direitos = $this->id_direitos;//fazer alguma coisa pra evitar o erro que esta dando
    	return $this->id_direitos;
    }

}
?>