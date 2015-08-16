<div id="contentLogin">
	<h2>Autenticação</h2>
	<form method="post" action="{$POST_LOGIN}" id="formLogin">
		{if $MENS_ERRO}
		<div class="erroTopoLogin">
			<span>{$MENS_ERRO}</span>
		</div>
        {/if}
		<fieldset>
		    <label>Email:</label>
		    <span class="small_input">
		    	<input class="small" name="email" id="email" type="text" onkeypress="javascript:verificaKey(event);" />
		    </span>
		    <span class="negative" id="log_erro"></span>
		    <br />
		    <label>Senha:</label>
		    <span class="small_input">
		    	<input class="small" name="passwd" id="passwd" type="password" onkeypress="javascript:verificaKey(event);" />
		    </span>
		    <span class="negative" id="psw_erro"></span>
		    <input type="hidden" name="param" value="{$PARAM_LOGIN}" />
		    <br />
			<p>&nbsp;</p>
		    <input type="submit" name="Submit" value="Entrar" onclick="javascript:validaLogin();" class="botao" />
			<input type="button" name="Cancelar" value="Cancelar" onclick="javascript:confirmaSairLogin('?', 'Deseja sair?');" class="botao" />
		    <span class="clear">&nbsp;</span>
			
			{FACEBOOK metodo="getButtonFB"}
		</fieldset>
	</form>
</div>