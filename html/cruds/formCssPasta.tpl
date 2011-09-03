<p>
Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>
{if $NOME_CSS}
Nome do CSS atual: {$NOME_CSS}
{/if}
<label>{$nome_estilo_css_label} :
	<span class="small" id="nome_arquivo_erro">{$nome_arquivo_error}</span>
</label> {$nome_estilo_css_campo}

<div id="btnsForm">
    {$Cadastrar_campo} {$Cancelar_campo}
</div>
