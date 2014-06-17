<?php

require_once(FWK_MODEL . "AbsModelDao.class.php");
require_once(FWK_DAO . "UsuariosProvDAO.class.php");
require_once(FWK_MODEL . "GruposUsuario.class.php");
require_once(FWK_CONTROL . "ControlUsuario.class.php");
require_once(FWK_CONTROL . "ControlSessao.class.php");

class UsuariosDAO extends AbsModelDao {

    public $_table = "fwk_usuario";
    public $_id = "id_usuario";
    private $objUsrProv;
    private $objGruposUsuario;

    public function cadastrar($xml, $post, $file) {
        try {
            if (self::testaEmail($post["email_usuario"]) == false) {
                self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
                self::validaForm($xml, $post);
                self::salvaPostAutoUtf8($post);
                $this->password_usuario = self::getObjCripto()->cryptMd5($post["password_usuario"]);
                $this->idioma_usuario = self::getUsuarioSessao()->getIdioma();
                $this->data_cadastro = date("Y-m-d");
                $this->id_tipo_usuario = USUARIO_PROVISORIO;
                self::salvar();
                self::getObjGruposUsuario()->setUsuarioGrupo($this->id_usuario);
                self::enviaEmailUsr($post);
            } else {
                self::vaiPara("formRecuperaSenha&a=FormRecuperaSenha&msg=E-mail já cadastrado, digite seu email para recuperar sua senha.");
                throw new DaoException("E-mail existente na base de dados");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function cadastrarViaFB($xml, $post, $file) {
        try {
            if (self::testaEmail($post["email_usuario"]) == false) {
                self::setIdUserCad(self::getUsuarioSessao()->getIdUsuario());
                self::validaForm($xml, $post);
                self::salvaPostAutoUtf8($post);
                $this->password_usuario = self::getObjCripto()->cryptMd5($post["password_usuario"]);
                $this->idioma_usuario = self::getUsuarioSessao()->getIdioma();
                $this->data_cadastro = date("Y-m-d");
                $this->id_tipo_usuario = USUARIO_ATIVO;
                self::salvar();
                self::getObjGruposUsuario()->setUsuarioGrupo($this->id_usuario);
                self::enviaEmailUsr($post, true);
            } else {
                self::vaiPara("formRecuperaSenha&a=FormRecuperaSenha&msg=E-mail já cadastrado, digite seu email para recuperar sua senha.");
                throw new DaoException("E-mail existente na base de dados");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function alterarUsuarioAtivacao($id, $strKey) {
        try {
            $arrCampos = self::buscaCampos($id);

            $this->id_usuario = $id;
            $this->id_tipo_usuario = USUARIO_ATIVO;
            $this->nome_usuario = $arrCampos["nome_usuario"];
            $this->password_usuario = $arrCampos["password_usuario"];
            $this->email_usuario = $arrCampos["email_usuario"];
            $this->data_cadastro = $arrCampos["data_cadastro"];
            $this->idioma_usuario = $arrCampos["idioma_usuario"];
            self::replace();
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    private function enviaEmailUsr($post, $viaFB = false) {
        try {
            $objMail = new PHPMailer();
            $objMail->SetLanguage("br");
            if (SMTP_ISSMTP) {
                $objMail->IsSMTP();
                $objMail->Host = SMTP_SERV_HOST;
                $objMail->Port = SMTP_SERV_PORTA;
                $objMail->SMTPAuth = SMTP_AUTH;
                $objMail->Username = SMTP_SERV_USER;
                $objMail->Password = SMTP_SERV_PASS; //Senha da caixa postal
            } else {
                $objMail->IsMail();
            }
            $objMail->IsHTML(true);
            $objMail->CharSet = "UTF-8";
            $objMail->From = parent::getCtrlConfiguracoes()->getStrEmailPortal();
            $objMail->FromName = parent::getCtrlConfiguracoes()->getStrTituloPortal();

            $objMail->AddAddress($post["email_usuario"]);
            $objMail->Subject = "Confirmação de Cadastro " . parent::getCtrlConfiguracoes()->getStrTituloPortal();
            $objMail->Body = self::pagMail($post, $viaFB);
            if ($objMail->Send())
                self::vaiPara("ViewCadUsuarios&a=concluiprov&envio=ok");
            else
                self::vaiPara("ViewCadUsuarios&a=concluiprov&envio=erro");
        } catch (Exception $e) {
            self::getObjForm()->registraFormValues($post, true);
            parent::getObjHttp()->escreEm("RESULT_CADASTRO", FWK_HTML_DEFAULT . "erroCadUsuario.tpl");
        }
    }

    private function pagMail($post, $viaFB = false) {
        parent::getObjSmarty()->assign("SUBJECT", "Confirmação de Cadastro no Portal " . parent::getCtrlConfiguracoes()->getStrTituloPortal());
        parent::getObjSmarty()->assign("NOME_PORTAL", parent::getCtrlConfiguracoes()->getStrTituloPortal());
        parent::getObjSmarty()->assign("NOME_USUARIO", $post["nome_usuario"]);

        if ($viaFB) {
            parent::getObjSmarty()->assign("LOGIN", $post["email_usuario"]);
            parent::getObjSmarty()->assign("SENHA", $post["password_usuario"]);
        }

        $strPort = ($_SERVER["SERVER_PORT"] == 80) ? "" : ":" . $_SERVER["SERVER_PORT"];

        parent::getObjSmarty()->assign("LINK_CADASTRO", "http://" . $_SERVER["SERVER_NAME"] . $strPort .
                $_SERVER["PHP_SELF"] . "?c=" . self::getObjCripto()->cryptData("ViewCadUsuarios&a=valida&chave=" .
                        $this->chave . "&id=" . $this->id_usuario));
        return parent::getObjSmarty()->fetch(FWK_HTML_EMAILS . "msgMailCadUsuario.tpl");
    }

    public function testaEmail($email_usuario) {
        if (count(self:: getUsuarioPovByMail($email_usuario)) == 0)
            return false;
        else
            return true;
    }

    public function getUsuarioPovByMail($email_usuario) {
        $strQuery = "SELECT
					email_usuario
				FROM
					fwk_usuario
				WHERE
					email_usuario = '" . $email_usuario . "'";
        return ControlDb::getRow($strQuery, 0);
    }

    public function validaCadastro($strId) {
        try {
            $arrCampos = self::buscaCampos($strId);

            $this->id_usuario = $strId;
            $this->id_tipo_usuario = USUARIO_ATIVO;
            $this->nome_usuario = $arrCampos["nome_usuario"];
            $this->password_usuario = $arrCampos["password_usuario"];
            $this->email_usuario = $arrCampos["email_usuario"];
            $this->data_cadastro = $arrCampos["data_cadastro"];
            $this->idioma_usuario = $arrCampos["idioma_usuario"];
            self::replace();
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function cadastrarByProv($strId, $strKey) {
        try {
            $arrUsrProv = self::getObjUsrProv()->getUsuarioPovByIdKey($strId, $strKey);
            if (count($arrUsrProv) == 0)
                throw new DaoException("Usuário não encontrado na base de dados.");
            $this->nome_usuario = $arrUsrProv["nome_usuario"];
            $this->password_usuario = $arrUsrProv["password_usuario"];
            $this->email_usuario = $arrUsrProv["email_usuario"];
            $this->data_cadastro = date("Y-m-d");
            $this->idioma_usuario = $arrUsrProv["idioma_usuario"];
            self::salvar();
            //self::criaAreaUsr(self::getIdUsuario());
            self::getObjUsrProv()->excluiByProv($strId, $strKey);
            self::getObjGruposUsuario()->setUsuarioGrupo($this->id_usuario);
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function alterar($id, $xml, $post, $file) {
        try {
            $this->id_usuario = $id;
            if ($id) {
                $arrCampos = self::buscaCampos($this->id_usuario);
                $post["data_cadastro"] = $arrCampos["data_cadastro"];
                $post["id_tipo_usuario"] = $arrCampos["id_tipo_usuario"];
            }

            self::validaForm($xml, $post);
            self::alteraPostAutoUtf8($post, $id);
            self::replace();

            if (self::ErrorMsg()) {
                print("<pre>");
                print_r($post);
                die("<br/><br /><h1>" . self::ErrorMsg() . "</h1>");
            }

            if (!$id)
                $arrCampos = self::buscaCampos($this->id_usuario);
            //atualiza-se a sessão logada com os dados modificados
            $objCtrlSessao = new ControlSessao();
            $objCtrlSessao->defineSessao($arrCampos);
            $objCtrlSessao->setSessaoUsuario(SESSAO_FWK_DRE);
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    private function criaAreaUsr($id) {
        try {
            $nomePasta = str_pad($id, 6, "0", STR_PAD_LEFT);

            if (isset($nomePasta) && PASTA_USUARIO_PORTAL . $nomePasta != "") {
                if (is_dir(PASTA_USUARIO_PORTAL . $nomePasta . "/"))
                    throw new CrudException("Ja existe uma pasta com o nome sugerido.");
                if (!mkdir(PASTA_USUARIO_PORTAL . $nomePasta))
                    throw new CrudException("Não foi possível criar a pasta.");
                //Criada a pasta, gera-se a estrutura interna dela.
                //cria a pasta img do usuario
                mkdir(PASTA_USUARIO_PORTAL . $nomePasta . "/img/");

                //cria arquivo: usuario.xml
                $dom = new DOMDocument("1.0", "ISO-8859-1");
                $dom->save(PASTA_USUARIO_PORTAL . $nomePasta . "/usuario.xml");

                //cria arquivo: log.xml
                $dom = new DOMDocument("1.0", "ISO-8859-1");
                $dom->save(PASTA_USUARIO_PORTAL . $nomePasta . "/log.xml");
            }
        } catch (DaoException $e) {
            throw new DaoException($e->getMensagem());
        }
    }

    public function getDadosUsuarioByNome($nomeUsuario) {

        $strQuery = "	SELECT id_usuario, nome_usuario, email_usuario FROM fwk_usuario
						WHERE nome_usuario ='" . $nomeUsuario . "'";
        return ControlDb::getRow($strQuery);
    }

    public function getDadosUsuariosById($idUsuario) {
        $strQuery = "	SELECT id_usuario, nome_usuario, email_usuario, data_cadastro FROM fwk_usuario
						WHERE id_usuario ='" . $idUsuario . "'";

        return ControlDb::getRow($strQuery, $fetch);
    }

    public function getDadosUsuariosByEmail($emailUsuario, $fetch) {
        $strQuery = "	SELECT id_usuario, id_tipo_usuario, nome_usuario, email_usuario, data_cadastro FROM fwk_usuario
						WHERE email_usuario ='" . $emailUsuario . "'";

        return ControlDb::getRow($strQuery, $fetch);
    }

    public function getNomeUsuarioById($id) {
        $strQuery = "SELECT nome_usuario FROM fwk_usuario WHERE id_usuario=" . $id;
        return end(ControlDb::getRow($strQuery, 1));
    }
    public function getDireitosUsuarioById($id) {
        $strQuery = "SELECT id_direitos FROM fwk_direitos_usuario WHERE id_usuario=" . $id;
        return ControlDb::getAll($strQuery, 0);
    }

    private function getObjUsrProv() {
        if ($this->objUsrProv == null)
            $this->objUsrProv = new UsuariosProvDAO();
        return $this->objUsrProv;
    }

    private function getObjGruposUsuario() {
        if ($this->objGruposUsuario == null)
            $this->objGruposUsuario = new GruposUsuario();
        return $this->objGruposUsuario;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

}

?>