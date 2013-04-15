<?php

/**
 * Novo arquivo
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 2.0 - 13/06/2010
 *

  $arrIndexs = array("index.php","prototype.php","tmp_imagem.php","tmp_image2.php","indexTesten.php");
  $_SERVIDOR_FIS = str_replace($arrIndexs,"",$_SERVER["SCRIPT_FILENAME"]);

  //ONLINE
  //$_SERVIDOR_FIS = str_replace($_SERVER["REQUEST_URI"],"",$_SERVIDOR_FIS)."/";

  //LOCAL
  $arr_URI = explode("/",$_SERVER["REQUEST_URI"]);
  //retira a pasta de testes
  $arr_URI[1] = "";
  $_SERVIDOR_FIS = str_replace($arr_URI,"",$_SERVIDOR_FIS);
  do{
  $_SERVIDOR_FIS = str_replace("//","/",$_SERVIDOR_FIS);
  }while(strpos($_SERVIDOR_FIS,"//")>0);

 */
require_once ("classes/util/CaminhoRaiz.class.php");

$caminhoServerRaiz = CaminhoRaiz :: getCaminhoRaiz();

define("CAMINHO_PASTA_PRINCIPAL", "projetodotvaziopontomg/portal/");
define("SERVIDOR_FISICO", $caminhoServerRaiz);
//define("SERVIDOR_FISICO","E:/www/dotmg3/");

define("URL_SERVIDOR", "http://" . $_SERVER["HTTP_HOST"] . "/" . CAMINHO_PASTA_PRINCIPAL);
//define("URL_SERVIDOR","http://ibsfgv.tcit.com.br/");
//define("URL_SERVIDOR","http://".$_SERVER["HTTP_HOST"]."/");
//define("URL_SERVIDOR", "http://" . $_SERVER["HTTP_HOST"] ."/".CAMINHO_PASTA_PRINCIPAL);
// define("URL_SERVIDOR","http://".$_SERVER["HTTP_HOST"]."/");
//define("RET_SERVIDOR",CaminhoRaiz::getRetornoRaiz());
define("RET_SERVIDOR", URL_SERVIDOR);

if ($_SERVER["SERVER_PORT"] != "80")
    define("URL_SITE", "http://" .
            $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
else
    define("URL_SITE", "http://" .
            $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);

//Endereço Virtual
define("URL_VIRTUAL", "http://localhost/".CAMINHO_PASTA_PRINCIPAL);

//se SMTP_ISSMTP for true utiliza estas informacoes.
define("SMTP_ISSMTP", false);
define("SMTP_SERV_HOST", "");
define("SMTP_SERV_PORTA", 55);
define("SMTP_SERV_USER", "exemplo@exemplo.com.br");
define("SMTP_SERV_PASS", "");
define("SMTP_AUTH", true);
?>