<?php
require_once(FWK_TAGS."AbsTagsFwk.class.php");
require_once(FWK_MODEL."GruposUsuario.class.php");
require_once(FWK_DAO."UsuariosDAO.class.php");
require_once(FWK_DAO."UsuariosFacebookDAO.class.php");
require_once(BIB_FACEBOOK);

class ViewFacebookFB extends AbsTagsFwk {
	
	private $objFB;
	private $dadosFB = null;
	
	public function __construct(){
		$this->dadosFB = self::getCtrlConfiguracoes()->getDadosFB();
	}
	
	public function executeTag(){	}
	
	/**
	 * Metodo utilizado para verificação do acesso via facebook, a tag deve ser utilizada no início do HTML do site, por padrão do FWK, ela se 
	 * encontra no tpl do menu deslogado; Neste método também se encontra a chamada pro cadastro do usuário oriundo do login com o facebook, 
	 * assim como o login do mesmo após o cadastro, ou caso já seja cadastrado.
	 * @author Matheus Vieira <matheus.vieira@tcit.com.br>;
	 * @since 17/01/2012;
	 * @return Usuário logado caso utilize o btn de logar com o facebook;
	 */
	public function verificaUsuarioFb(){	
		if($this->dadosFB["id"] && $this->dadosFB["secretKey"]){
			if(self::getCaminho()!= null && self::getCaminho()!= ""){
				if(self::getObjFb()->getUser() > 0){
					if($_GET["state"]){
						$arrUsrFace = self::getObjFb()->api('/me');
						$qtdReg = self::getObjUsuario()->testaEmail($arrUsrFace["email"]);
						if($qtdReg == 0){
							self::cadastraUsuario($arrUsrFace);
						}
						if(self::getObjUsrSessao()->getIdUsuario() == 0){
							foreach ($arrUsrFace as $key => $valor)
								$dados[] = $key."=".$valor;
							$params = implode("&", $dados);							
							header("Location: ?".self::getTipo()."=".self::getObjCrypt()->cryptData(self::getCaminho()."&".$params));
						}
					}
				}
			}else{
				throw new TagsException("Não foi passado UM caminho para a sequencia da verificação do usuário FaceBook.");
			}
		}
	}

	public function getButtonFB(){
		if($this->dadosFB["id"] && $this->dadosFB["secretKey"]){
			$extra_params = array(
							'scope' => 'email,publish_stream' 
							);
			parent::getObjSmarty()->assign("URL_LOGIN_FB", str_replace("&", "&amp;", self::getObjFb()->getLoginUrl($extra_params)));
			$strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL."tagBotaoFacebook.tpl");
			print $strTela;
		}
	}	
	
	private function cadastraUsuario($arrDados){
		try{
			$post["nome_usuario"] = $arrDados["name"];
			$post["email_usuario"] = $arrDados["email"];
			$post["via_facebook"] = "S";
			$post["idioma_usuario"] = "pt_br";
			$post["password_usuario"] = self::geraSenha();
			self::getObjUsuario()->cadastrarViaFB(FWK_XML."formCadUsuarios.xml",$post,null);
		}catch(CrudException $e){
			self::vaiPara("".__CLASS__."&msg=".$e->getMensagem());
		}
	}

	private function criaSessao($dados){
		try{
			self::getObjCtrSessao()->setFacebook($dados, SESSAO_FWK_DRE);
		}catch(CrudException $e){
			self::vaiPara("".__CLASS__."&msg=".$e->getMensagem());
		}
	}
	
	private function geraSenha(){
		  $CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
		  $max = strlen($CaracteresAceitos)-1;
		  $password = null;
		  for($i=0; $i < 8; $i++) {
		   $password .= $CaracteresAceitos{mt_rand(0, $max)};
		  }
		  return $password;
	}
	
	private function getObjFb(){
		if($this->dadosFB["id"] && $this->dadosFB["secretKey"]){
			if($this->objFB == null){
				$this->objFB = new Facebook(array(
				  'appId'  => $this->dadosFB["id"],
				  'secret' => $this->dadosFB["secretKey"],
				));
			}
			return $this->objFB;
		}
	}
	
	private function getObjUsrFace(){
		if($this->ObjUsrFace == null)
			$this->ObjUsrFace = new UsuariosFacebookDAO();
		return $this->ObjUsrFace;
	}	
	
	private function getObjUsuario(){
		if($this->ObjUsuario == null)
			$this->ObjUsuario = new UsuariosDAO();
		return $this->ObjUsuario;
	}
	
	private function getObjGrupoUsuario(){
		if($this->ObjGrupoUsuario == null)
			$this->ObjGrupoUsuario = new GruposUsuario();
		return $this->ObjGrupoUsuario;
	}

}
?>