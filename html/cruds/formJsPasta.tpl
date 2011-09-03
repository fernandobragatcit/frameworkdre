<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>
{if $NOME_JS}
Nome do Javascript atual: {$NOME_JS}
{/if}
<label>{$nome_javascript_label} :
    <span class="small" id="nome_arquivo_erro">{$nome_arquivo_error}</span>
</label> {$nome_javascript_campo}

<div id="btnsForm">
    {$Cadastrar_campo} {$Cancelar_campo}
</div>
