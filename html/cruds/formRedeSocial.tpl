<p>
	O campo marcado com <span class="campoObrigatorio">*</span> é de preenchimento obrigatório
</p>

<div class="formTelaFull">
	<label>{$descricao_label} :
	    <span class="small error" id="descricao_erro">{$descricao_error}</span>
	</label> {$descricao_campo}
</div>

<div class="formTelaFull">
	<label>{$link_label} :
	    <span class="small error" id="link_erro">{$link_error}</span>
	</label> {$link_campo}
</div>
<div class="formTelaFull">
	<label>{$nome_arquivo_label} :
	    <span class="small error" id="nome_arquivo_erro">{$nome_arquivo_error}</span>
	</label> {$nome_arquivo_campo}
</div>
<div class="formMeiaTela" >
    {if $ID_FOTO neq ""}
        <a href='{FOTO metodo="getLinkFoto" idObj="$ID_FOTO" largura="0"}' id="maximizaFotoForm" >
            {FOTO metodo="getThumbImg" idObj="$ID_FOTO" altura="80" largura="100" cssFoto="alinhaFotoForm"}
        </a>
        <label style="width: 140px; padding-top: 20px">Clique na foto para ampliar</label>
    {else}
        <label style="margin-left: 0px; width: 300px; padding-top: 20px">não possui imagem cadastrada</label>
    {/if}
</div>
<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>
