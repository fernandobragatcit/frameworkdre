<p>
<!--	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
-->
</p>
{if $NOME_IMAGENS}
<div class="formTelaFull">
	Nome da imagem atual: {$NOME_IMAGENS}
</div>
{/if}
<div class="formTelaFull">
	<label>{$nome_imagem_label} :
	    <span class="small" id="nome_imagem_erro">{$nome_imagem_error}</span>
	</label> {$nome_imagem_campo}
</div>

<div class="formTelaFull">
	<label>{$nome_imagem_zip_label} :
	    <span class="small" id="nome_imagem_zip_erro">{$nome_imagem_zip_error}</span>
	</label> {$nome_imagem_zip_campo}
</div>

<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>