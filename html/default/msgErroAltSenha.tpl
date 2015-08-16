<div id="content" class="grid_16 alpha omega">
	<div class="grid_11 alpha omega margintopM15">
		<img width="330" height="53" alt="Gallho Topo" src="{$URL_IMAGENS}img-wood-top.png">
    </div>
  	<div class="grid_12 marginTop25 marginBottom25">
       <div class="grid_11">
        <form name="recuperaSenha"  id="loginValidaForm"  action="{$POST_ALT_SENHA}" method="post" class="grid_6 padding10 right form loginCom">
            <h2 class="font30 marginTop">Erro</h2>
            <br />
			<p>Houve um problema ao alterar sua senha.<br /><br />
				Favor tentar novamente dentro de alguns minutos ou entre em contato diretamente com o portal.
				<br /><br />
				Atenciosamente,<br />
				<span class="green"><strong>Equipe do Portal Circuito Serra do Cipó.</strong></span>
			</p>

            <input type="hidden" name="param" value="{$PARAM_LOGIN}" />
        </form>
       </div>
     </div>
  </div>
