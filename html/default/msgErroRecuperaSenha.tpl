<div id="content" class="grid_16 alpha omega container">
	<div class="grid_11 alpha omega margintopM15">
		<img width="330" height="53" alt="Gallho Topo" src="{$URL_IMAGENS}img-wood-top.png">
    </div>
  	<div class="grid_12 marginTop25 marginBottom25">
       <div class="grid_11">
        <form name="recuperaSenha"  id="loginValidaForm"  action="{$POST_ALT_SENHA}" method="post" class="grid_6 padding10 form loginCom">
            <h2 class="font30 marginTop">Erro</h2>
            <br />
			<p>Houve um problema ao recuperar sua senha ou o email não se encontra em nossa base de dados.<br /><br />
				Favor <a href="{$FORM_ALT_SENHA}" class="darkGray"><strong>tentar novamente</strong></a>
				dentro de alguns minutos ou entre em contato diretamente com o portal.
				<br /><br />
				Atenciosamente,<br />
				<span class="green"><strong>Equipe do Portal Circuito Serra do Cipó.</strong></span>
			</p>

            <input type="hidden" name="param" value="{$PARAM_LOGIN}" />
        </form>
       </div>
     </div>
  </div>
