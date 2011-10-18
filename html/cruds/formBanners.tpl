<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório <br /><br /><br />
</p>

<p>
<strong>Use um nome de facil assimilação para o banner, pois será o identificador dele em sua tag.Ex: BANNER1...</strong>
</p>

<div class="formTelaFull">
	<label>{$nome_imagem_label} : <span class="small" id="nome_imagem_erro" style="color:red">{$nome_imagem_error}</span></label> 
	{$nome_imagem_campo}
</div>

<p style="padding-top:10px;">
	<strong>Categoria não é obrigatória, mas serve para agrupar em caso de banners rotativos</strong>
</p>

<div class="formTelaFull">
	<label>{$id_categoria_banner_label} : <span class="small" id="id_categoria_banner_erro">{$id_categoria_banner_error}</span></label> 
	{$id_categoria_banner_campo}
</div>

<div class="formTelaFull">
	<label>{$title_banner_label} : <span class="small" id="title_banner_erro">{$title_banner_error}</span></label> 
	{$title_banner_campo}
</div>

<div class="formTelaFull">
	<label>{$link_banner_label} : <span class="small" id="link_banner_erro">{$link_banner_error}</span></label> 
	{$link_banner_campo}
</div>

<div class="formTelaFull">
	<label>{$nome_arquivo_label} : <span class="small" id="nome_arquivo_erro">{$nome_arquivo_error}</span></label> 
	{$nome_arquivo_campo}
</div>

<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>
