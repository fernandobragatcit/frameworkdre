<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>
<label>{$nome_menu_label} :
    <span class="small" id="nome_menu_erro">{$nome_menu_error}</span>
</label> {$nome_menu_campo}

<label>{$link_menu_label} :
	<span class="small" id="link_menu_erro">{$link_menu_error}</span>
</label> {$link_menu_campo}

<label>{$funcionalidade_label} :
    <span class="small" id="funcionalidade_erro">{$funcionalidade_error}</span>
</label> {$funcionalidade_campo}

<label>{$ordem_menu_label} :
	<span class="small" id="ordem_menu_erro">{$ordem_menu_error}</span>
</label> {$ordem_menu_campo}

<div id="btnsForm">
    {$Cadastrar_campo} {$Cancelar_campo}
</div>
