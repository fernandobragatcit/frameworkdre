<div class="formTelaFull">
    <div class="alinhaBtns marginTop10 marginBottom10">{$Salvar_campo} {$Cancelar_campo}</div>
</div>

<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de
	preenchimento obrigatório
</p>
<div class="formTelaFull">
	
	<label>{$nome_celula_label} : <span class="mensErroForms" id="nome_celula_erro">{$nome_celula_error}</span> </label>
	{$nome_celula_campo}
</div>
<div class="formMeiaTela" >
	
	<label>{$id_unidade_label} :  <span class="mensErroForms" id="id_unidade_erro">{$id_unidade_error}</span></label>
	{$id_unidade_campo}
</div>
<div class="formMeiaTela">
	
	<label>{$gestor_celula_label} :  <span class="mensErroForms" id="gestor_celula_erro">{$gestor_celula_error}</span></label>
	{$gestor_celula_campo}
</div>
<div class="formMeiaTela">
	
	<label>{$supervisor_celula_label} :  <span class="mensErroForms" id="gestor_celula_erro">{$supervisor_celula_error}</span></label>
	{$supervisor_celula_campo}
</div>
<div class="formMeiaTela">
	
	<label>{$supervisor_celula_2_label} :  <span class="mensErroForms" id="gestor_celula_2_erro">{$supervisor_celula_2_error}</span></label>
	{$supervisor_celula_2_campo}
</div>
<div class="formTelaFull">
	
	<label>{$idColabCelula_label} :  <span class="mensErroForms" id="idColabCelula_erro">{$idColabCelula_error}</span></label>
	{$idColabCelula_campo}
</div>
<div class="formTelaFull">
	
	<label>{$idCursoCelula_label} :  <span class="mensErroForms" id="idCursoCelula_erro">{$idCursoCelula_error}</span></label>
	{$idCursoCelula_campo}
</div>
<div class="formTelaFull">
	<div class="alinhaBtns">{$Salvar_campo} {$Cancelar_campo}</div>
        {if $ULTIMA_ALTERACAO}
        <div class="clear"></div>
        <div class="marginTop20">
            <p><span class="campoObrigatorio">*</span> {$ULTIMA_ALTERACAO}</p>
        </div>
    {/if}
</div>