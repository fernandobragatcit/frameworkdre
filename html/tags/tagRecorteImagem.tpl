{if $TAG}
    <div class="clear"></div>
    <div class="formMeiaTela {$CSS}">
        <img style="width: 12px;height: 12px;" src="{$smarty.const.FWK_IMG}crop.png"/><a class="fancyrecorte" id="txt_link" href="#conteudoRecorte{$ORDEM_RECORTE}">{$TXT_LINK}</a>
        <script type="text/javascript" src="{$smarty.const.FWK_JS}jquery.Jcrop.js"></script>
        <link rel="stylesheet" href="{$smarty.const.FWK_CSS}jquery.Jcrop.css" type="text/css" />
        {literal}
            <style type="text/css">
                .clear {
                    clear:both;
                }

                .centro {
                    text-align:center!important;
                }

                .direita {
                    text-align:right!important;
                }

                .esquerda {
                    text-align:left!important;
                }

                .fl_right {
                    float:right;
                }

                .clearfix:after { content: "."; clear: both; display: block; height: 0; visibility: hidden; }
                .clearfix { display: inline-block; }
                /* Hide from IE Mac \*/
                .clearfix { display: block; }
                /* End hide from IE Mac */


                a img{ border: none; }

                div#div-jcrop{/literal}{$ORDEM_RECORTE}{literal}{
                    width: {/literal}{$LARGURA_SIZE}{literal}px; 
                    height: {/literal}{$ALTURA_SIZE}{literal}px; 
                    float: left;
                    background-color:#f2f2f2;
                    padding:0px;
                }

                #tit-jcrop a{font-size:12px;color:#216041;}

                .custom .jcrop-vline, .custom .jcrop-hline {
                    background: #FF3366!important;
                }

                div#topbar{
                    margin:0 0 12px 0;
                    background-color:#ccc;
                    padding:12px;
                    -moz-border-radius:6px;
                    -webkit-border-radius:6px;
                    border-radius:6px;
                    width:90%;
                }

                div#topbar ul{ list-style:none; }
                div#topbar ul li{ float:left; margin:0 12px 0 0;} 



                div#debug{
                    margin:12px;
                    margin-left: 0 !important;
                    background-color:#cccccc;
                    padding:12px;
                    position: relative;
                    float: left;
                    color:#fff;
                    width: 100%;
                }
                div#debug p{
                    margin:4px;
                    color: #444444;
                    font-weight: bold;
                }
                div#debug strong{
                    display:block;
                    width:100px;
                    float:left;
                    text-align:right;
                    margin-right:2px;
                }
                div#debug input{
                    background-color:#fff;
                    border:1px solid #ccc;
                    padding:4px;
                }
                #tit-jcrop{
                    background-color: #888888;
                    color: #ffffff;
                    padding: 10px;
                    font-size: 20px;
                    text-transform: uppercase;
                    font-weight: bold;

                }

                #btn-crop
                {
                    border:none;
                    background-color:#777777;
                    color:#fff;
                    font-weight:900;
                    margin-top:12px;
                    padding:8px;
                    border-radius:6px;
                    -moz-border-radius:6px;
                    -webkit-border-radius:6px;
                    cursor:pointer;
                    float: left;
                    margin-bottom: 5px;
                    font-size: 20px;

                }
                a:hover{
                    text-decoration:  none !important;
                }
                div#div-preview{/literal}{$ORDEM_RECORTE}{literal}
                {
                    overflow: hidden; 
                    width: {/literal}{$LARGURA}{literal}px; 
                    height: {/literal}{$ALTURA}{literal}px; 
                    background-color:#ccc;
                    border:12px solid #ccc;
                    {/literal}{$PREVIEW_FLOAT}{literal};
                    z-index: 120;
                }
                .acoes{
                    width: 50%;
                }
                .tela{
                    width: 100%;
                    position: relative;
                    float:left;
                }

            </style>
            <script type="text/javascript">
                jQuery(function() {
                    jQuery('{/literal}{'#jcrop'|cat:$ORDEM_RECORTE}{literal}').Jcrop({
                        onChange: {/literal}{'exibePreview'|cat:$ORDEM_RECORTE}{literal},
                        onSelect: {/literal}{'exibePreview'|cat:$ORDEM_RECORTE}{literal},
                        minSize: [{/literal}{$LARGURA}{literal}, {/literal}{$ALTURA}{literal}],
                        maxSize: [{/literal}{$LARGURA}{literal}, {/literal}{$ALTURA}{literal}],
                        setSelect:   [ {/literal}{$CROPX}{literal}, {/literal}{$CROPY}{literal}, {/literal}{$CROPX2}{literal}, {/literal}{$CROPY2}{literal} ],
                        allowResize: false,
                        addClass: 'custom'
                    });

                });
                function {/literal}{'exibePreview'|cat:$ORDEM_RECORTE}{literal}(c)
                {
                    var rx = {/literal}{$LARGURA}{literal} / c.w;
                    var ry = {/literal}{$ALTURA}{literal} / c.h;
                    jQuery('{/literal}{'#preview'|cat:$ORDEM_RECORTE}{literal}').css({
                        width: Math.round(rx * {/literal}{$IMAGES_SIZE[0]}{literal}) + 'px',
                        height: Math.round(ry * {/literal}{$IMAGES_SIZE[1]}{literal}) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                    jQuery('{/literal}{'#cropX'|cat:$ORDEM_RECORTE}{literal}').val(c.x);
                    jQuery('{/literal}{'#cropY'|cat:$ORDEM_RECORTE}{literal}').val(c.y);
                    jQuery('{/literal}{'#x2_'|cat:$ORDEM_RECORTE}{literal}').val(c.x2);
                    jQuery('{/literal}{'#y2_'|cat:$ORDEM_RECORTE}{literal}').val(c.y2);
                    jQuery('{/literal}{'#w'|cat:$ORDEM_RECORTE}{literal}').val(c.w);
                    jQuery('{/literal}{'#h'|cat:$ORDEM_RECORTE}{literal}').val(c.h);
                }
            </script>
        {/literal}
        <div id="viewRecorte{$ORDEM_RECORTE}" class="viewRecorte{$ORDEM_RECORTE}"  style="display: none;">
            <div id="conteudoRecorte{$ORDEM_RECORTE}">
                <h2 id="tit-jcrop" >Faça o recorte da miniatura: {$TAG|replace:"-":" "}</h2>
                <div class="tela">
                    <div id="div-jcrop{$ORDEM_RECORTE}">
                        {$IMG}
                    </div>
                    <div id="div-preview{$ORDEM_RECORTE}">
                        {$PREVIEW}
                    </div>
                    <div id="debug">
                        <p><strong>X</strong> <input type="text" name="cropX{$ORDEM_RECORTE}" id="cropX{$ORDEM_RECORTE}" value="{$CROPX}" size="5" readonly="false" /> x2 <input type="text" id="x2_{$ORDEM_RECORTE}" name="x2_{$ORDEM_RECORTE}" value="{$CROPX2}" size="5" readonly="false" /> </p>
                        <p><strong>Y</strong> <input type="text" name="cropY{$ORDEM_RECORTE}" id="cropY{$ORDEM_RECORTE}" value="{$CROPY}" size="5" readonly="false" /> y2 <input type="text" id="y2_{$ORDEM_RECORTE}" name="y2_{$ORDEM_RECORTE}" value="{$CROPY2}" size="5" readonly="false" /> </p>
                        <p><strong>Dimensões</strong> <input type="text" id="h{$ORDEM_RECORTE}" size="5" disabled /> x <input type="text" id="w{$ORDEM_RECORTE}" size="5" disabled /></p>
                    </div>
                </div>


                <div class="acoes">
                    <p style="color: red;"><b>Atenção:</b></p> <span>Após concluir o recorte salve o formulário para que suas definições fiquem salvas.</span>
                    <a id="btn-crop" href="javascript:jQuery.fancybox.close();">FINALIZAR RECORTE</a>
                </div>
                <input type="hidden" id="tag_recorte{$ORDEM_RECORTE}" name="tag_recorte{$ORDEM_RECORTE}" value="{$TAG}"/>
                <input type="hidden" id="ordem_recorte" name="ordem_recorte" value="{$ORDEM_RECORTE}"/>

            </div>
        </div>
    </div>
    <div class="clear"></div>
    {literal}
        <script type="text/javascript"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".fancyrecorte").fancybox({
                    fitToView: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    scrolling: 'hide'
                });
            });
        </script>
    {/literal}
{/if}