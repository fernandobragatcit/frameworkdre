<?php

require_once(FWK_EXCEPTION . "TagsException.class.php");

/**
 * Tags de tratamento do conteúdo para o sistema.
 * Fonte retirada do site do smarty <www.smarty.net>
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 07/07/2010
 */
function text_get_template($tpl_name, & $tpl_source, & $smarty_obj) {
    if (1 == 1) {
        $tpl_source = $tpl_name;
        return true;
    } else {
        return false;
    }
}

function text_get_timestamp($tpl_name, & $tpl_timestamp, & $smarty_obj) {
    if (1 == 1) {
        $tpl_timestamp = time();
        return true;
    } else {
        return false;
    }
}

function text_get_secure($tpl_name, & $smarty_obj) {
    return true;
}

function text_get_trusted($tpl_name, & $smarty_obj) {
    
}

/**
 * Tag de publicação de banners no fwk
 *
 * @author André Coura
 * @since 1.0 - 21/04/2011
 */
function getBanner($params) {
    $largura = "";
    $altura = "";
    $nome = "";
    $title = "";
    $link = "";
    $posicao = "";
    $caminho = "";
    $descricao = "";
    $metodo = "";
    $idObj = "";
    extract($params);
    try {
        require_once (FWK_TAGS . "ViewBanners.class.php");
        $objBanner = new ViewBanners();
        $objBanner->setLargura($largura);
        $objBanner->setAltura($altura);
        $objBanner->setNome($nome);
        $objBanner->setTitle($title);
        $objBanner->setLink($link);
        $objBanner->setPosicao($posicao);
        $objBanner->setCaminho($caminho);
        $objBanner->setDescricao($descricao);
        $objBanner->setIdObj($idObj);
        if ($metodo != "")
            $objBanner->$metodo();
        else
            $objBanner->executeTag();
    } catch (TagsException $e) {
        die($e->getMensagem());
    }
}

/**
 * Tag de publicação de banners no fwk
 *
 * @author André Coura
 * @since 1.0 - 21/04/2011
 */
function getDocumento($params) {
    $identificador = "";
    $id = "";
    $variavel = "";
    extract($params);
    try {
        require_once (FWK_TAGS . "ViewFicheiro.class.php");
        $objFicheiro = new ViewFicheiro();
        $objFicheiro->setIdentificador($identificador);
        $objFicheiro->setId($id);
        $objFicheiro->setVariavel($variavel);
        $objFicheiro->executeTag();
    } catch (TagsException $e) {
        die($e->getMensagem());
    }
}

function getFotosTag($params) {
    $metodo = "";
    $altura = "";
    $largura = "";
    $marca = "";
    $preLoading = "";
    $cropX = "";
    $cropY = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $param4 = "";
    $param5 = "";
    $param6 = "";
    $cssFoto = "";
    $styleFoto = "";
    $title = "";
    $tipoObj = "";
    $idObj = "";
    $miniatura = "";
    extract($params);

    require_once(FWK_TAGS . "ViewFotos.class.php");
    $objFotos = new ViewFotos();
    $objFotos->setAltura($altura);
    $objFotos->setLargura($largura);
    $objFotos->setMarca($marca);
    $objFotos->setPreLoading($preLoading);
    $objFotos->setCropX($cropX);
    $objFotos->setCropY($cropY);
    $objFotos->setParam1($param1);
    $objFotos->setParam2($param2);
    $objFotos->setParam3($param3);
    $objFotos->setParam4($param4);
    $objFotos->setParam5($param5);
    $objFotos->setParam6($param6);
    $objFotos->setTitle($title);
    $objFotos->setCssFoto($cssFoto);
    $objFotos->setStyleFoto($styleFoto);
    $objFotos->setTipoObj($tipoObj);
    $objFotos->setIdObj($idObj);
     $objFotos->setTagRecorteView($miniatura);
    if ($metodo != "")
        $objFotos->$metodo();
    else
        $objFotos->executeTag();
}

function getArquivoTag($params) {
    $metodo = "";
    $cssLink = "";
    $title = "";
    $idObj = "";
    extract($params);

    require_once(FWK_TAGS . "ViewArquivos.class.php");
    $objArquivo = new ViewArquivos();
    $objArquivo->setTitle($title);
    $objArquivo->setCssLink($cssLink);
    $objArquivo->setIdObj($idObj);
    if ($metodo != "")
        $objArquivo->$metodo();
    else
        $objArquivo->executeTag();
}

/**
 * TAG Factory, responsável pela chama das classes de tratamento de tags
 * TODO: As tags dessas funções são registradas no ControlSmarty do Framework
 */
function getModuloTags($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $param4 = "";
    $param5 = "";
    $param6 = "";
    $tipoObj = "";
    $idObj = "";
    extract($params);

    if (file_exists(VIEW_TAGS . $classe . ".class.php")) {
        require_once(VIEW_TAGS . $classe . ".class.php");
        $objClasseTag = new $classe();
        if ($param1 != "")
            $objClasseTag->setParam1($param1);
        if ($param2 != "")
            $objClasseTag->setParam2($param2);
        if ($param3 != "")
            $objClasseTag->setParam3($param3);
        if ($param4 != "")
            $objClasseTag->setParam4($param4);
        if ($param5 != "")
            $objClasseTag->setParam5($param5);
        if ($param6 != "")
            $objClasseTag->setParam6($param6);
        if ($tipoObj != "")
            $objClasseTag->setTipoObj($tipoObj);
        if ($idObj != "")
            $objClasseTag->setIdObj($idObj);
        if ($metodo != "")
            $objClasseTag->$metodo();
        else
            $objClasseTag->executeTag();
    }else {
        print("A classe referente à tag: " . $classe . ".class.php não existe e/ou foi informado incorretamente.");
    }
}

function getModuloVotacao($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";

    extract($params);

    require_once(VIEW_VOTACAO . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->tagVotacao();
}

function getModuloReserva($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";

    extract($params);

    require_once(VIEW_RESERVA . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->tagReserva();
}

function getModuloPagamento($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";

    extract($params);

    require_once(VIEW_PAGAMENTO . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->tagPagamento();
}

function getModuloPlanosComerciais($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";

    extract($params);

    require_once(VIEW_COMERCIAIS . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->tagPlanosComerciais();
}

function getModuloRoteiro($params) {
    $classe = "";
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";

    extract($params);

    require_once(ROTEIRO_VIEW . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->tagRoteiro();
}

function getModuloFavoritos($params) {
    $classe = "";
    $metodo = "";
    $tipoObj = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $param4 = "";
    $param5 = "";
    $id = "";

    extract($params);

    require_once(FAVORITOS_VIEW . $classe . ".class.php");

    $objClasseTag = new $classe();

    if ($param1 != "")
        $objClasseTag->setParam1($param1);

    if ($param2 != "")
        $objClasseTag->setParam2($param2);

    if ($param3 != "")
        $objClasseTag->setParam3($param3);

    if ($param4 != "")
        $objClasseTag->setParam4($param4);

    if ($param5 != "")
        $objClasseTag->setParam5($param5);

    if ($id != "")
        $objClasseTag->setId($id);

    if ($id != "")
        $objClasseTag->setTipoObj($tipoObj);


    if ($metodo != "")
        $objClasseTag->$metodo();
    else
        $objClasseTag->executeTag();
}

/**
 *
 * Enter description here ...
 * @param unknown_type $params
 */
function getModuloViewBarraServico($params) {
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $id = "";


    extract($params);

    require_once(TAGS_VIEW . "ViewBarraServico.class.php");

    $barra = new ViewBarraServico();

    if ($param1 != "")
        $barra->setParam1($param1);

    if ($param2 != "")
        $barra->setParam2($param2);

    if ($param3 != "")
        $barra->setParam3($param3);

    if ($id != "")
        $barra->setId($id);

    $barra->tagFavorito();
}

function getModuloEstiveAqui($params) {
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $id = "";

    extract($params);

    require_once(ESTIVEAQUI_VIEW . "ViewEstiveAqui.class.php");

    $objEstiveAqui = new ViewEstiveAqui();

    if ($param1 != "")
        $objEstiveAqui->setParam1($param1);

    if ($param2 != "")
        $objEstiveAqui->setParam2($param2);

    if ($param3 != "")
        $objEstiveAqui->setParam3($param3);

    if ($id != "")
        $objEstiveAqui->setId($id);

    if ($idDocumento != "")
        $objEstiveAqui->setIdDocumento($idDocumento);

    $objEstiveAqui->executeTag();
}

function getModuloQuiz($params) {
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $id = "";

    extract($params);
    require_once(QUIZ_VIEW . "ViewQuiz.class.php");

    $objQuiz = new ViewQuiz();

    if ($param1 != "")
        $objQuiz->setParam1($param1);

    if ($param2 != "")
        $objQuiz->setParam2($param2);

    if ($param3 != "")
        $objQuiz->setParam3($param3);

    if ($id != "")
        $objQuiz->setId($id);

    if ($idDocumento != "")
        $objQuiz->setIdDocumento($idDocumento);

    if ($metodo != "")
        $objQuiz->$metodo();
    else
        $objQuiz->executeTag();
}

function getModuloForum($params) {
    $metodo = "";
    $param1 = "";
    $param2 = "";
    $param3 = "";
    $id = "";

    extract($params);
    require_once(FORUM_VIEW . "ViewForum.class.php");

    $objForum = new ViewForum();

    if ($param1 != "")
        $objForum->setParam1($param1);

    if ($param2 != "")
        $objForum->setParam2($param2);

    if ($param3 != "")
        $objForum->setParam3($param3);

    if ($id != "")
        $objForum->setId($id);

    if ($idDocumento != "")
        $objForum->setIdDocumento($idDocumento);

    if ($metodo != "")
        $objForum->$metodo();
    else
        $objForum->executeTag();
}

function getGaleria($params) {
    $idGaleria = "";
    $nome = "";
    extract($params);
    try {
        require_once (FWK_TAGS . "ViewGaleria.class.php");
        $objGaleria = new ViewGaleria();
        $objGaleria->setNome($nome);
        $objGaleria->setIdGaleria($idGaleria);
        $objGaleria->executeTag();
    } catch (TagsException $e) {
        die($e->getMensagem());
    }
}

function getFacebook($params) {
    $metodo = "";
    $tipoObj = "";
    $caminho = "";
    extract($params);

    try {
        require_once (FWK_TAGS . "ViewFacebookFB.class.php");
        $objFB = new ViewFacebookFB();

        if ($tipo != "")
            $objFB->setTipoObj($tipoObj);

        if ($caminho != "")
            $objFB->setCaminho($caminho);

        if ($metodo != "")
            $objFB->$metodo();
        else
            $objFB->executeTag();
    } catch (TagsException $e) {
        die($e->getMensagem());
    }
}

function getRecorteImagem($params) {
    $metodo = "";
    $miniatura = "";
    $txt_link = "";
    $css = "";
    $ordem_recorte = "";
    $idImgRecorte = "";
    $largura_miniatura = "";
    $altura_miniatura = "";
    extract($params);

    require_once(FWK_TAGS . "ViewFotos.class.php");
    $obj = new ViewFotos();
    $obj->setStrTextoLink($txt_link);
    $obj->setMiniatura($miniatura);
    $obj->setCssRecorte($css);
    $obj->setOrdemRecorte($ordem_recorte);
    $obj->setidImgRecorte($idImgRecorte);
    $obj->setAltura_miniatura($altura_miniatura);
    $obj->setLargura_miniatura($largura_miniatura);
    if ($metodo != "")
        $obj->$metodo();
    else
        $obj->executeTag();
}

?>