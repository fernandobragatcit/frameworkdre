<div id="content" class="grid_16 alpha omega container">
	<div class="grid_11 alpha omega margintopM15">
		<img width="330" height="53" alt="Gallho Topo" src="{$URL_IMAGENS}img-wood-top.png">
    </div>
  	<div class="grid_12 marginTop25 marginBottom25">
       <div class="grid_11">
       	<form name="recuperaSenha"  id="loginValidaForm"  action="{$POST_ALT_SENHA}" method="post" class="grid_6 padding10 form loginCom">
            <h2 class="font30 marginTop">Enviado</h2>
            <br />
			<p>Você receberá um email contendo a nova senha de seu usuário no Portal. <br /> <br />
				Caso não o receba, verifique em sua caixa de lixo eletronico (SPAMs). <br /><br />

				Atenciosamente, <br />
				Equipe {$NOME_PORTAL}.
			</p>

            <input type="hidden" name="param" value="{$PARAM_LOGIN}" />
          </form>
       </div>
     </div>
  </div>
