
<div class="menuCompartilhe marginLeft10 marginTop25">
<ul>
	<li><span class="noMarginLeft menuCompartilheFavoritos">Favoritos</span>
	{FAVORITOS classe="ViewFavoritos"  param1="$LOCAL_FAV"  tipoObj="$OBJ_FAVORITO" id="$ID_OBJ_FAVORITO" param2="$PARAM2"} <!--  <a href="#">Favoritos</a> -->
	</li>
	<li><span class="menuCompartilheImprimir">Imprimir</span><a href="#">Imprimir</a>
	</li>
	<li><span class="menuCompartilheComentar">Comentar</span><a href="#">Comentar</a>
	</li>
	<li class="noBorder"><span class="noClass"> Compartilhar </span> <a
		href="javascript:showSendFriendEmail()"><span class="compartilheMail">E-mail</span></a>
	<a href="#"><span class="compartilheOrkut">Orkut</span></a> <a href="#"><span
		class="compartilheFacebook">Facebook</span></a> <a href="#"><span
		class="compartilheTwitter">Twitter</span></a></li>
</ul>
</div>
<div id="envieParaUmAmigo"
	class="grid_11 marginTop10 marginLeft10 borderBottom "
	style="display: none;">
<form action="#">
<table class="width">
	<tr>
		<td>
		<h3 class="darkBrown marginTop15">Envie para um amigo</h3>
		</td>
	</tr>
	<tr>
		<td class="paddingTop10"><label>Seu Nome </label> <input name="name"
			value="" class="width marginBottom10" /></td>
		<td rowspan="2" class="paddingLeft20">
			<label>Comentário </label>
			<textarea name="comentario" value="" class="width marginBottom10 comentario" rows="4" />
			</textarea>
		</td>
	</tr>
	<tr>
		<td><label>Seu email </label> <input name="email" value="" class="width marginBottom10" /></td>
	</tr>
	<tr>
		<td class="verticalAlignTop"><label>Enviar para </label> <input
			name="email" value="" class="width marginBottom10" /></td>
		<td class="paddingLeft20 right paddingTop10"><input type="image"
			src="{$URL_IMAGENS}enviar-amigo-btn.jpg" /></td>
	</tr>
</table>
</form>
</div>
