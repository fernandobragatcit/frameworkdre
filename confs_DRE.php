<?php

ini_set("display_errors", "1");
ini_set("allow_url_fopen", "1");
ini_set("memory_limit", "64M");

//servidor físico
require_once("confs_servidor.php");
require_once("magic_quotes.php");

define("ATIVA_MSG_VALIDACAO_NAVEGADOR", false);
define("PASTA_USUARIO", SERVIDOR_FISICO . "usr/");

define("FWK_LIMIT_PAGINACAO", 20);
//classes
define("FWK_DRE", SERVIDOR_FISICO . "framework/classes/");
define("FWK_MODEL", FWK_DRE . "model/");
define("FWK_EXCEPTION", FWK_DRE . "exception/");
define("FWK_CONTROL", FWK_DRE . "control/");
define("FWK_DAO", FWK_DRE . "dao/");
define("FWK_VIEW", FWK_DRE . "view/");
define("FWK_UTIL", FWK_DRE . "util/");
define("FWK_COMP", FWK_DRE . "componentes/");
define("FWK_TAGS", FWK_DRE . "tags/");

//CHAMADOS ***************************************************************
define("FWK_DRE_CH", SERVIDOR_FISICO . "framework/gerenciadorChamados/");
define("FWK_FORM_CH", FWK_DRE_CH . "formularios/");
define("FWK_CLASSES_CH", FWK_FORM_CH . "classes/");
define("FWK_DAO_CH", FWK_CLASSES_CH . "dao/");
define("FWK_MODEL_CH", FWK_CLASSES_CH . "model/");
define("FWK_VIEW_CH", FWK_CLASSES_CH . "view/");
define("FWK_ESTRUTURA_CH", FWK_FORM_CH . "estrutura/");
define("FWK_CSS_CH", FWK_ESTRUTURA_CH . "css/");
define("FWK_JS_CH", FWK_ESTRUTURA_CH . "js/");
define("FWK_TPLS_CH", FWK_ESTRUTURA_CH . "tpls/");
define("FWK_XML_CH", FWK_ESTRUTURA_CH . "xml/");
//CHAMADOS URL
define("FWK_DRE_CH_URL", RET_SERVIDOR . "framework/gerenciadorChamados/");
define("FWK_FORM_CH_URL", FWK_DRE_CH_URL . "formularios/");
define("FWK_CLASSES_CH_URL", FWK_FORM_CH_URL . "classes/");
define("FWK_DAO_CH_URL", FWK_CLASSES_CH_URL . "dao/");
define("FWK_MODEL_CH_URL", FWK_CLASSES_CH_URL . "model/");
define("FWK_VIEW_CH_URL", FWK_CLASSES_CH_URL . "view/");
define("FWK_ESTRUTURA_CH_URL", FWK_FORM_CH_URL . "estrutura/");
define("FWK_CSS_CH_URL", FWK_ESTRUTURA_CH_URL . "css/");
define("FWK_JS_CH_URL", FWK_ESTRUTURA_CH_URL . "js/");
define("FWK_TPLS_CH_URL", FWK_ESTRUTURA_CH_URL . "tpls/");
define("FWK_XML_CH_URL", FWK_ESTRUTURA_CH_URL . "xml/");
//************************************************************************

define("FWK_HTML", SERVIDOR_FISICO . "framework/html/");
define("FWK_FIS_JS", FWK_HTML . "js/");
define("FWK_TAGS_TPL", FWK_HTML . "tags/");


define("FWK_DEFAULT", SERVIDOR_FISICO . "framework/files/");
define("FWK_CONTEUDOS", FWK_DEFAULT . "conteudos.xml");
//XMLS
define("FWK_XML", SERVIDOR_FISICO . "framework/xml/");
define("FWK_XML_CRUD", SERVIDOR_FISICO . "framework/xml/xmlsCrud/");
define("PRE_NOME_XML", "log_dre_");
//HTML
define("FWK_HTML", SERVIDOR_FISICO . "framework/html/");

define("URL_FWK_HTML", RET_SERVIDOR . "framework/html/");
define("FWK_JS", URL_FWK_HTML . "js/");
define("FWK_CSS", URL_FWK_HTML . "css/");
define("FWK_IMG", URL_FWK_HTML . "imgs/");


define("CONFIG_FILE", "config/config.xml");

define("FWK_HTML_EXCEPTION", FWK_HTML . "exception/");
define("FWK_HTML_ADMINPAGE", FWK_HTML . "adminPage/");
define("FWK_HTML_FORMS", FWK_HTML . "forms/");
define("FWK_HTML_GRID", FWK_HTML . "grid/");
define("FWK_HTML_EMAILS", FWK_HTML . "emails/");
define("FWK_HTML_MENS", FWK_HTML . "mens/");
define("FWK_HTML_MENU", FWK_HTML . "menu/");
define("FWK_HTML_CRUDS", FWK_HTML . "cruds/");
define("FWK_HTML_VIEWS", FWK_HTML . "views/");
define("FWK_HTML_DEFAULT", FWK_HTML . "default/");
define("FWK_HTML_AREAS", FWK_HTML . "areas/");

//tempo de expiração da sessão
define("SESSION_TIME", 12);

//Numero de uploads simultaneos
define("NUM_UPLOADS", 10);

//EMAIL GRID BUGs
define("EMAIL_BUG", "andreccls@gmail.com");


//XMLs de conteudos de áreas
define("CAMINHO_XMLS", "config/");
define("XML_CABECALHO", CAMINHO_XMLS . "cabecalho.xml");
define("XML_SUB_CABECALHO", CAMINHO_XMLS . "subCabecalho.xml");
define("XML_CONT_CENTRO", CAMINHO_XMLS . "central.xml");
define("XML_CENTRO_INF", CAMINHO_XMLS . "centralInf.xml");
define("XML_LAT_DIREITA", CAMINHO_XMLS . "latDireita.xml");
define("XML_LAT_ESQUERDA", CAMINHO_XMLS . "latEsquerda.xml");
define("XML_RODAPE", CAMINHO_XMLS . "rodape.xml");


//Id Portal
define("PORTAL_SISTEMA", 1);

//Funcionalidades
define("DIREITOS_GRUPO", 1);
define("DIREITOS_USUARIO", 2);


//bibliotecas
define("BIBLIOTECAS_DRE", SERVIDOR_FISICO . "framework/libs/");
define("URL_BIBLIOTECAS_DRE", URL_SERVIDOR . "framework/libs/");
define("BIB_SMARTY", BIBLIOTECAS_DRE . "smarty/Smarty.class.php");
define("BIB_MAILER", BIBLIOTECAS_DRE . "phpmailer/class.phpmailer.php");
define("BIB_MAILER_SMTP", BIBLIOTECAS_DRE . "phpmailer/class.smtp.php");
define("BIB_ADODB", BIBLIOTECAS_DRE . "adodb/adodb.inc.php");
define("BIB_PEARL", BIBLIOTECAS_DRE . "PEAR/PEAR.php");
define("BIB_ACTIVE_RECORD", BIBLIOTECAS_DRE . "adodb/adodb-active-record.inc.php");
define("BIB_JSON", BIBLIOTECAS_DRE . "JSON.php");
define("BIB_FPDF", BIBLIOTECAS_DRE . "fpdf/fpdf.php");
define("CAPTCHA_PASTA", BIBLIOTECAS_DRE . "captcha/");
define("BIB_CAPTCHA", CAPTCHA_PASTA . "securimage.php");
define("CAPTCHA_SHOW", URL_BIBLIOTECAS_DRE . "captcha/securimage_show.php");
define("BIB_FACEBOOK", BIBLIOTECAS_DRE . "facebook/src/facebook.php");
define("BIB_ISMOBILE", BIBLIOTECAS_DRE . "ismobile/ismobile.class.php");


define("MSG_ERRO_LOGIN", "Email e/ou senha invalidos.");
define("MSG_ERRO_EMAIL", "Email não cadastrado na nossa base de dados.");
define("ERRO_LEITURA_XML", "Erro ao ler o xml requerido: ");
define("ERRO_ACTION_XML_FORM", "Não foi passado o action para o formulário, sendo um campo obrigatório.");
define("ERRO_TAG_XML_FORM", "Não foi passado a tag para a construção do formulário, sendo um campo obrigatório.");
define("ERRO_NOME_XML_FORM", "Não foi passado o nome para a construção do formulário, sendo um campo obrigatório.");
define("ERRO_TPL_XML_FORM", "Não foi passado o nome do tpl de conteúdo para a construção do formulário, sendo um campo obrigatório.");
define("ERRO_TPL_XML_FORM_INEX", "Não foi pencontrado o tpl de conteúdo para a construção do formulário, sendo um arquivo obrigatório.");

define("ERRO_CONEXAO_DB", "Impossivel conectar-se ao Banco de Dados");

define("FORM_TYPE_ERRO", "O atributo type referente ao tipo de campo a ser criado dever ser informado no XML para a criação do fomulário.");
define("FORM_NAME_ERRO", "O atributo name referente ao nome do campo a ser criado dever ser informado no XML para a criação do fomulário.");
define("FORM_ID_ERRO", "O atributo ID referente ao id do campo a ser criado dever ser informado no XML para a criação do fomulário.");
define("FORM_LABEL_ERRO", "O atributo Label referente ao texto do campo a ser criado dever ser informado no XML para a criação do fomulário.");

define("FORM_IDNAMEVALUE", "Não foi setado nenhum dos parâmetro do butão para definir seu Id, name ou value");
define("FORM_NOACTION", "Não foi setado nenhum dos parâmetro do butão para definir sua ação");

define("FORM_INPUT_TYPE_ERRO", "Não foi passado o tipo de algum componente do XML para o formulário.");
define("FORM_INPUT_NAME_ERRO", "Não foi passado o nome de algum componente do XML para o formulário.");
?>