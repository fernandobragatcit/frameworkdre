<div id="contLogin">
	<img src="{$URL_IMGS}{$IMG_LOGIN}" />
	<div id="mensErros">
		{$MENS_ERRO}
	</div>
	<form name="login" id="formLogin" action="{$POST_LOGIN}" method="post" class="formLogin" onsubmit=" return validaLogin();">
		<fieldset>
			<label class="labelLogin">Name: </label>
			<input type="text" name="login" class="inputLogin" id="login" />
			<br/><br/>
			<label class="labelLogin">Password: </label>
			<input type="password" name="passwd" class="inputLogin" />
			<br/><br/>
			<input type="submit" name="Submit" value="Submit" class="btnLogin" />
		</fieldset>
	</form>
</div>