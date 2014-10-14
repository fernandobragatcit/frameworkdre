<div id="content" class="grid_16 alpha omega container">
  	<div class="grid_12 marginTop25 marginBottom25">
       <div class="grid_11">
        <form name="recuperaSenha"  id="loginValidaForm"  action="{$POST_REC_SENHA}" method="post" class="grid_6 padding10 form loginCom">
            <h2 class="font30 marginTop">Recuperar Senha</h2>
            <br />
			<p>Para redefinir a senha, digite o endereço de e-mail usado para acessar ao Portal. Será enviado a nova senha para seu e-mail.</p>
            <div id="loginEmail" class="marginTop25">
	            <label>Email </label>
	            <input class="width" name="email" id="emailLogin" type="text" onkeypress="javascript:verificaKey(event);">
            </div>
            <br />

            <button id="Recuperar" name="Recuperar" type="submit" >Enviar</button>
            <input type="hidden" name="param" value="{$PARAM_LOGIN}" />
        </form>
       </div>
     </div>
  </div>