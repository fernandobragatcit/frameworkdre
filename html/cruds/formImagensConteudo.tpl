<p>
<!--	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
-->
</p>
{if $NOME_IMAGENS}
Nome da imagem atual: {$NOME_IMAGENS}

<label>{$nome_imagem_label} :
    <span class="small" id="nome_imagem_erro">{$nome_imagem_error}</span>
</label> {$nome_imagem_campo}
{/if}
{if !$NOME_IMAGENS}
<label>{$nome_imagem_label} :
    <span class="small" id="nome_imagem_erro">{$nome_imagem_error}</span>
</label> {$nome_imagem_campo}

<label>{$nome_imagem_zip_label} :
    <span class="small" id="nome_imagem_zip_erro">{$nome_imagem_zip_error}</span>
</label> {$nome_imagem_zip_campo}
{/if}
<div id="btnsForm">
    {$Cadastrar_campo} {$Cancelar_campo}
</div>