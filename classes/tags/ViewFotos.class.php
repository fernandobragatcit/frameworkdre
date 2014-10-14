<?php

require_once (FWK_TAGS . "AbsTagsFwk.class.php");
require_once(FWK_DAO . "FotosDAO.class.php");
require_once(FWK_DAO . "FotosCropDAO.class.php");

class ViewFotos extends AbsTags {

    private $objFoto;
    private $strTitle;
    private $strTextoLink;
    private $strCssFoto;
    private $strStyleFoto;
    private $altura;
    private $largura;
    private $marca;
    private $strPreLoading;
    private $cropX;
    private $cropY;
    private $cropX2;
    private $cropY2;
    private $miniatura;
    private $tagRecorteView;
    private $cssRecorte;
    private $ordemRecorte;
    private $idImgRecorte;
    private $largura_miniatura;
    private $altura_miniatura;

    /**
     * Função para a criação do thumb da imagem
     * @param IdObj - Id da foto
     * @param Param1 - Largura
     * @param Param2 - Altura
     * @param Param3 - Se vai ter marca d'água ou não | string true or false
     *
     * @author Matheus
     * @since 1.0 - 21/01/2011
     */
    public function getThumbImg() {
        $arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());
        $dadosCrop = self::getObjFotoCrop()->buscaDadosCropByTagEfoto(self::getTagRecorteView(), self::getIdObj());

        if (!file_exists(PASTA_UPLOADS_FOTOS . $arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null) {
            $arrFoto = self::getObjFoto()->buscaCampos("1");
        }

        $arrFoto = $arrFoto;

        //Busca o nome do arquivo.
        $arrLinkImg["img"] = $arrFoto["nome_arquivo"];

        //Seta a largura da imagem.
        if (self::getLargura() != "")
            $arrLinkImg["w"] = self::getLargura();
        else
            $arrLinkImg["w"] = self::getParam1();

        //Seta a altura da imagem.
        if (self::getAltura() != "")
            $arrLinkImg["h"] = self::getAltura();
        else
            $arrLinkImg["h"] = self::getParam2();

        //True or False para marca d'agua.
        if (self::getMarca() != "")
            $arrLinkImg["marca"] = self::getMarca();
        elseif (self::getParam3() != "")
            $arrLinkImg["marca"] = self::getParam3();
        else
            $arrLinkImg["marca"] = false;

        //Seta se será distorcido ou cortado.
        switch (self::getParam6()) {
            case "simples":
                $arrLinkImg["redimensiona"] = "simples";
                break;
            case "preenchimento":
                $arrLinkImg["redimensiona"] = "preenchimento";
                break;
            case "crop":
            default:
                $arrLinkImg["redimensiona"] = "crop";
                if (!empty($dadosCrop["cropX"])) {
                    $arrLinkImg["x"] = ($dadosCrop["cropX"] == 0) ? "" : $dadosCrop["cropX"];
                } elseif (self::getCropX() != "") {
                    $arrLinkImg["x"] = self::getCropX();
                }
                if (!empty($dadosCrop["cropY"])) {
                    $arrLinkImg["y"] = ($dadosCrop["cropY"] == 0) ? "" : $dadosCrop["cropY"];
                } elseif (self::getCropY() != "") {
                    $arrLinkImg["y"] = self::getCropY();
                }
                break;
        }

        //Retorna o link criptografado do thumb da imagem.
        $link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

        //Seta o link da img.
        parent::getObjSmarty()->assign("LINK", $link);

        parent::getObjSmarty()->assign("LARGURA_FOTO", $arrLinkImg["w"]);

        parent::getObjSmarty()->assign("ALTURA_FOTO", $arrLinkImg["h"]);
        //Seta o alt da imagem.
        parent::getObjSmarty()->assign("TITLE_FOTO", self::getTitle());

        parent::getObjSmarty()->assign("CSS_FOTO", self::getCssFoto());

        parent::getObjSmarty()->assign("STYLE_FOTO", self::getStyleFoto());

        parent::getObjSmarty()->assign("TITULO", $arrFoto["titulo_foto"]);

        //True or False para pre-loading.
        parent::getObjSmarty()->assign("PRE_LOADING", (self::getPreLoading()) ? self::getPreLoading() : "true");

        $strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL . "tagFotoThumb.tpl");
        print ($strTela);
    }

    /**
     * Função para a criação do link de apliar imagem
     * @param IdObj - Id da foto
     *
     * @author Matheus
     * @since 1.0 - 21/01/2011
     */
    public function getMaximizaImg() {
        $arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());

        if (!file_exists(PASTA_UPLOADS_FOTOS . $arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null) {
            $arrFoto = self::getObjFoto()->buscaCampos("1");
        }
        $arrFoto = $arrFoto;

        //Busca o nome do arquivo.
        $arrLinkImg["img"] = $arrFoto["nome_arquivo"];
        //Seta a largura da imagem.
        $arrLinkImg["w"] = 0;
        //Seta a altura da imagem.
        $arrLinkImg["h"] = 0;
        //Seta se será distorcido ou cortado.
        switch (self::getParam6()) {
            case "simples":
                $arrLinkImg["redimensiona"] = "simples";
                break;
            case "preenchimento":
                $arrLinkImg["redimensiona"] = "preenchimento";
                break;
            case "crop":
            default:
                $arrLinkImg["redimensiona"] = "crop";
                break;
        }
        //Retorna o link criptografado da imagem thumb ampliada.
        $link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

        //Seta o link para ampliar img.
        parent::getObjSmarty()->assign("LINK", $link);
        //Seta o alt da imagem.
        parent::getObjSmarty()->assign("TITULO", $arrFoto["titulo_foto"]);

        $strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL . "tagFotoMaximiza.tpl");
        print ($strTela);
    }

    public function getLinkFoto() {
        $arrFoto = self::getObjFoto()->buscaCampos(self::getIdObj());

        if (!file_exists(PASTA_UPLOADS_FOTOS . $arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null) {
            $arrFoto = self::getObjFoto()->buscaCampos("1");
        }

        $arrFoto = $arrFoto;

        //Busca o nome do arquivo.
        $arrLinkImg["img"] = $arrFoto["nome_arquivo"];


        //Seta a largura da imagem.
        if (self::getLargura() != "" && self::getLargura() != 0)
            $arrLinkImg["w"] = self::getLargura();
        elseif (self::getParam1() != "")
            $arrLinkImg["w"] = self::getParam1();
        else
            $arrLinkImg["w"] = 600;

        //Seta a altura da imagem.
        if (self::getAltura() != "")
            $arrLinkImg["h"] = self::getAltura();
        else
            $arrLinkImg["h"] = self::getParam2();

        //True or False para marca d'agua.
        if (self::getMarca() != "")
            $arrLinkImg["marca"] = self::getMarca();
        elseif (self::getParam3() != "")
            $arrLinkImg["marca"] = self::getParam3();
        else
            $arrLinkImg["marca"] = false;

        //Seta se será distorcido ou cortado.
        switch (self::getParam6()) {
            case "simples":
                $arrLinkImg["redimensiona"] = "simples";
                break;
            case "preenchimento":
                $arrLinkImg["redimensiona"] = "preenchimento";
                break;
            case "crop":
            default:
                $arrLinkImg["redimensiona"] = "crop";
                break;
        }
        //Retorna o link criptografado do thumb da imagem.
        $link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

        //Seta o link para ampliar img.
        print(RET_SERVIDOR . "thumb.php?img=" . $link);
    }

    public function retornaLinkFoto($idObj, $altura = "", $largura = "", $dimen = "") {
        $arrFoto = self::getObjFoto()->buscaCampos($idObj);

        if (!file_exists(PASTA_UPLOADS_FOTOS . $arrFoto["nome_arquivo"]) || $arrFoto["nome_arquivo"] == '' || $arrFoto["nome_arquivo"] == null) {
            $arrFoto = self::getObjFoto()->buscaCampos("1");
        }

        $arrFoto = $arrFoto;

        //Busca o nome do arquivo.
        $arrLinkImg["img"] = $arrFoto["nome_arquivo"];


        //Seta a largura da imagem.
        if ($largura != "")
            $arrLinkImg["w"] = $largura;
        elseif (self::getParam1() != "")
            $arrLinkImg["w"] = self::getParam1();
        else
            $arrLinkImg["w"] = 600;

        //Seta a altura da imagem.
        if ($altura != "")
            $arrLinkImg["h"] = $altura;
        else
            $arrLinkImg["h"] = self::getParam2();

        //True or False para marca d'agua.
        if (self::getMarca() != "")
            $arrLinkImg["marca"] = self::getMarca();
        elseif (self::getParam3() != "")
            $arrLinkImg["marca"] = self::getParam3();
        else
            $arrLinkImg["marca"] = false;

        //Seta se será distorcido ou cortado.
        switch ($dimen) {
            case "simples":
                $arrLinkImg["redimensiona"] = "simples";
                break;
            case "preenchimento":
                $arrLinkImg["redimensiona"] = "preenchimento";
                break;
            case "crop":
            default:
                $arrLinkImg["redimensiona"] = "crop";
                break;
        }
        //Retorna o link criptografado do thumb da imagem.
        $link = self::getObjCrypt()->cryptData(serialize($arrLinkImg));

        //Seta o link para ampliar img.
        return RET_SERVIDOR . "thumb.php?img=" . $link;
    }

    /**
     * Função para mostrar a tela de recorte de imagem
     *
     * @author Fernando Braga
     * @since 3.0 - 27/03/2014
     */
    public function getRecorteImagem() {
        $dados = self::getObjFotoCrop()->buscaDadosCropByTagEfoto(self::getMiniatura(), self::getIdImgRecorte());
        $nome_foto = self::getObjFotoCrop()->buscaNomeImagem(self::getIdImgRecorte());
        //self::debuga($dados);
        parent::getObjSmarty()->assign("CROPX", $dados['cropX']);
        parent::getObjSmarty()->assign("CROPY", $dados['cropY']);
        parent::getObjSmarty()->assign("CROPX2", $dados['cropX2']);
        parent::getObjSmarty()->assign("CROPY2", $dados['cropY2']);
        parent::getObjSmarty()->assign("TXT_LINK", self::getStrTextoLink());
        parent::getObjSmarty()->assign("TAG", self::getMiniatura());
        parent::getObjSmarty()->assign("CSS", self::getCssRecorte());
        parent::getObjSmarty()->assign("ORDEM_RECORTE", self::getOrdemRecorte());
        parent::getObjSmarty()->assign("ID_IMG_RECORTE", self::getIdImgRecorte());

        $caminho_img_real = URL_BDIMG . $nome_foto;
        $tamanho_real = getimagesize($caminho_img_real);

        //se utilizarmos o método de cortar por uma imagem tamanho default
        if (1 == 0) {
            $imagem = self::retornaLinkFoto(self::getIdImgRecorte(), $tamanho_real[1], $tamanho_real[0], $dimen = "crop");
            $imagesize = $tamanho_real;
        } else {
            if (self::getAltura_miniatura() > self::getLargura_miniatura()) {
                $imagem = self::retornaLinkFoto(self::getIdImgRecorte(), self::getAltura_miniatura(), "", $dimen = "crop");
            } elseif (self::getAltura_miniatura() == self::getLargura_miniatura()) {
                $imagem = self::retornaLinkFoto(self::getIdImgRecorte(), self::getAltura_miniatura(), self::getLargura_miniatura(), $dimen = "crop");
            } else {
                $imagem = self::retornaLinkFoto(self::getIdImgRecorte(), "", self::getLargura_miniatura(), $dimen = "crop");
            }
            //self::debuga($imagem,$tamanho_real);
            $imagesize = getimagesize($imagem);
        }
        if (($imagesize[0] + self::getLargura_miniatura()) < 1000) {
            parent::getObjSmarty()->assign("PREVIEW_FLOAT", "float:right");
        } else {
            parent::getObjSmarty()->assign("PREVIEW_FLOAT", "float:left");
        }
        $idImg = "jcrop" . self::getOrdemRecorte();
        $idPreview = "preview" . self::getOrdemRecorte();
        $img = '<img src="' . $imagem . '" id="' . $idImg . '" ' . $imagesize[3] . ' />';
        $preview = '<img src="' . $imagem . '" id="' . $idPreview . '" />';
        parent::getObjSmarty()->assign("PREVIEW", $preview);
        parent::getObjSmarty()->assign("IMG", $img);
        parent::getObjSmarty()->assign("IMAGEM", $imagem);
        parent::getObjSmarty()->assign("IMAGES_SIZE", $imagesize);
        parent::getObjSmarty()->assign("ALTURA", self::getAltura_miniatura());
        parent::getObjSmarty()->assign("LARGURA", self::getLargura_miniatura());
        parent::getObjSmarty()->assign("ALTURA_SIZE", $imagesize[1]);
        parent::getObjSmarty()->assign("LARGURA_SIZE", $imagesize[0]);

        $strTela = parent::getObjSmarty()->fetch(FWK_TAGS_TPL . "tagRecorteImagem.tpl");
        print ($strTela);
    }

    private function getObjFotoCrop() {
        if ($this->obFotoCrop == null) {
            $this->obFotoCrop = new FotosCropDAO();
        }
        return $this->obFotoCrop;
    }

    private function getObjFoto() {
        if ($this->objFoto == null)
            $this->objFoto = new FotosDAO();
        return $this->objFoto;
    }

    public function setTitle($title) {
        $this->strTitle = $title;
    }

    public function getTitle() {
        return $this->strTitle;
    }

    public function setCssFoto($cssFoto) {
        $this->strCssFoto = $cssFoto;
    }

    public function getCssFoto() {
        return $this->strCssFoto;
    }

    public function setStyleFoto($styleFoto) {
        $this->strStyleFoto = $styleFoto;
    }

    public function getStyleFoto() {
        return $this->strStyleFoto;
    }

    public function setAltura($strAltura) {
        $this->altura = $strAltura;
    }

    public function getAltura() {
        return $this->altura;
    }

    public function setLargura($strLargura) {
        $this->largura = $strLargura;
    }

    public function getLargura() {
        return $this->largura;
    }

    public function setMarca($strMarca) {
        $this->marca = $strMarca;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function setPreLoading($strPreLoading) {
        $this->preLoading = $strPreLoading;
    }

    public function getPreLoading() {
        return $this->preLoading;
    }

    public function setCropX($strCropX) {
        $this->cropX = $strCropX;
    }

    public function getCropX() {
        return $this->cropX;
    }

    public function setCropY($strCropY) {
        $this->cropY = $strCropY;
    }

    public function getCropY() {
        return $this->cropY;
    }

    public function getCropX2() {
        return $this->cropX2;
    }

    public function getCropY2() {
        return $this->cropY2;
    }

    public function setCropX2($cropX2) {
        $this->cropX2 = $cropX2;
    }

    public function setCropY2($cropY2) {
        $this->cropY2 = $cropY2;
    }

    public function executeTag() {
        
    }

    public function getStrTextoLink() {
        return $this->strTextoLink;
    }

    public function setStrTextoLink($strTextoLink) {
        $this->strTextoLink = $strTextoLink;
    }

    public function getMiniatura() {
        return $this->miniatura;
    }

    public function setMiniatura($miniatura) {
        $this->miniatura = $miniatura;
    }

    public function getCssRecorte() {
        return $this->cssRecorte;
    }

    public function setCssRecorte($cssRecorte) {
        $this->cssRecorte = $cssRecorte;
    }

    public function getOrdemRecorte() {
        return $this->ordemRecorte;
    }

    public function setOrdemRecorte($ordemRecorte) {
        $this->ordemRecorte = $ordemRecorte;
    }

    public function getIdImgRecorte() {
        return $this->idImgRecorte;
    }

    public function setIdImgRecorte($idImgRecorte) {
        $this->idImgRecorte = $idImgRecorte;
    }

    public function getTagRecorteView() {
        return $this->tagRecorteView;
    }

    public function setTagRecorteView($tagRecorteView) {
        $this->tagRecorteView = $tagRecorteView;
    }

    public function getLargura_miniatura() {
        return $this->largura_miniatura;
    }

    public function getAltura_miniatura() {
        return $this->altura_miniatura;
    }

    public function setLargura_miniatura($largura_miniatura) {
        $this->largura_miniatura = $largura_miniatura;
    }

    public function setAltura_miniatura($altura_miniatura) {
        $this->altura_miniatura = $altura_miniatura;
    }

}

?>