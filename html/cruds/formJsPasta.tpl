<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>
{if $NOME_JS}
<div class="formTelaFull">
	Nome do Javascript atual: {$NOME_JS}
</div>
{/if}

<div class="formTelaFull">
	<label>{$nome_javascript_label} :
	    <span class="small" id="nome_arquivo_erro">{$nome_arquivo_error}</span>
	</label> {$nome_javascript_campo}
</div>

<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>