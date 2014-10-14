<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>

<div class="formTelaFull">
	<label>{$nome_usuario_label}<span class="campoObrigatorio">*</span> :
	    <span class="small error" id="nome_usuario_erro">{$nome_usuario_error}</span>
	</label> {$nome_usuario_campo}
</div>

{if $GRUPO_USUARIO}
<div class="formTelaFull">
    <label>{$grupo_usuario_label} :
        <span class="small error" id="grupo_usuario_erro">{$grupo_usuario_error}</span>
    </label> {$grupo_usuario_campo}
</div>
{/if}

<div class="formTelaFull">
	<label>{$login_usuario_label} :
		<span class="small error" id="login_usuario_erro">{$login_usuario_error}</span>
	</label> {$login_usuario_campo}
</div>

<div class="formTelaFull">
	<label>{$password_usuario_label} :
		<span class="small error" id="password_usuario_erro">{$password_usuario_error}</span>
	</label> {$password_usuario_campo}
</div>

<div class="formTelaFull">
	<label>{$password_usuario_conf_label} :
		<span class="small error" id="password_usuario_conf_erro">{$password_usuario_conf_error}</span>
	</label> {$password_usuario_conf_campo}
</div>

<div class="formTelaFull">
	<label>{$email_usuario_label} :
		<span class="small error" id="email_usuario_erro">{$email_usuario_error}</span>
	</label> {$email_usuario_campo}
</div>

<div class="formTelaFull">
	<div class="alinhaBtns">{$Cadastrar_campo} {$Cancelar_campo}</div>
</div>