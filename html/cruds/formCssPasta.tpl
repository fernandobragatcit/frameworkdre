<p>
Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>

{if $NOME_CSS}
	<div class="formTelaFull">
		Nome do CSS atual: {$NOME_CSS}
	</div>
{/if}

<div class="formTelaFull">
	<label>{$nome_estilo_css_label} :
		<span class="small error" id="nome_arquivo_erro">{$nome_arquivo_error}</span>
	</label> {$nome_estilo_css_campo}
</div>


<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>
