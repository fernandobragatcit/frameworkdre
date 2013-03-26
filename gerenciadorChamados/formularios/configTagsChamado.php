<?php
/**
 * Configurações do Módulo Tags
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 19/07/2010
 */

 define("GERENCIADOR_CHAMADOS",PASTA_FWK."gerenciadorChamados/");
 define("CHA_FORMULARIOS",GERENCIADOR_CHAMADOS."formularios/");
 define("CHA_CLASSES",CHA_FORMULARIOS."classes/");
 define("CHA_ESTRUTURA",CHA_FORMULARIOS."estrutura/");
  
 define("CHA_MODEL",CHA_CLASSES."model/");
 define("CHA_VIEW",CHA_CLASSES."view/");
 
  define("CHA_JS",CHA_ESTRUTURA."js/");
  define("CHA_TPL",CHA_ESTRUTURA."tpls/");
  define("CHA_XML",CHA_ESTRUTURA."xml/");
 
 define("MODULO_TAGS",PASTA_MODULOS."tags/");
 define("TAGS_CLASSES",MODULO_TAGS."classes/");
 define("TAGS_DAO",TAGS_CLASSES."dao/");
 define("TAGS_VIEW",TAGS_CLASSES."view/");
 define("TAGS_UTIL",TAGS_CLASSES."util/");
 define("TAGS_MODEL",TAGS_CLASSES."model/");
 define("TAGS_CONTROL",TAGS_CLASSES."control/");

 define("TAGS_ESTRUTURA",MODULO_TAGS."estrutura/");
 define("TAGS_TPL",TAGS_ESTRUTURA."tpls/");
 define("TAGS_JS",TAGS_ESTRUTURA."js/");
 define("TAGS_XML",TAGS_ESTRUTURA."xml/");

 define("URL_TAGS",RET_SERVIDOR."modulos/tags/");
 define("URL_TAGS_ESTRUTURA",URL_TAGS."estrutura/");
 define("URL_TAGS_JS",URL_TAGS_ESTRUTURA."js/");

 //CONFIG CAMPOS
 define("STATUS_DEFAULT",1);

?>