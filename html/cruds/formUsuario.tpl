<p>
	Os campos marcados com <span class="campoObrigatorio">*</span> são de preenchimento obrigatório
</p>
<label>{$nome_usuario_label}<span class="campoObrigatorio">*</span> :
    <span class="small" id="nome_usuario_erro">{$nome_usuario_error}</span>
</label> {$nome_usuario_campo}

{if $GRUPO_USUARIO}
    <label>{$grupo_usuario_label} :
        <span class="small" id="grupo_usuario_erro">{$grupo_usuario_error}</span>
    </label> {$grupo_usuario_campo}
{/if}

<label>{$login_usuario_label} :
	<span class="small" id="login_usuario_erro">{$login_usuario_error}</span>
</label> {$login_usuario_campo}

<label>{$password_usuario_label} :
	<span class="small" id="password_usuario_erro">{$password_usuario_error}</span>
</label> {$password_usuario_campo}

<label>{$password_usuario_conf_label} :
	<span class="small" id="password_usuario_conf_erro">{$password_usuario_conf_error}</span>
</label> {$password_usuario_conf_campo}

<label>{$email_usuario_label} :
	<span class="small" id="email_usuario_erro">{$email_usuario_error}</span>
</label> {$email_usuario_campo}

<div id="btnsForm">
    {$Cadastrar_campo} {$Cancelar_campo}
</div>
