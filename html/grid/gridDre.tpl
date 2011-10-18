<div id="content_sis" style="padding-top:20px;">
	{$MENS_GRID}
	<h2>{$TITULO_GRID}</h2>
	    <!--
	    <ul class="tabs">
	        <li><a href="#" title="Item1"><span>Nice!</span></a></li>
	        <li class="active"><a href="#" title="Item2"><span>I love this table</span></a></li>
	    </ul
	    -->
	    <div class="box">
		{if $NUM_DADOS_INI eq "TRUE"}
	        <table style="width:726px">
	            <tbody>
	            <tr>
					<th colspan="{$ARR_TITULOS|@count}">
						<div id="legendaIcons">
					    	<strong>Legenda:</strong>
					    	<img width='11' title='Editar' alt='Icone Editar' src='{$smarty.const.URL_IMAGENS}icons/page_white_edit.png'> Editar |
					    	<img width='11' title='Deletar' alt='Icone Deletar' src='{$smarty.const.URL_IMAGENS}icons/page_white_delete.png'> Deletar
					    </div>
					    <form method="post" class="right" id="formFiltro" action="{$URL_SITE}{$LINK_BUSCAR}">
					    	<input type="text" name="buscaGrid" id="campoFiltro" value="{$VALOR_BUSCA}" />
					    	<input type="submit" value="Buscar" id="submitFiltro" />
					    </form>
					    <br style="clear:both;" />
					</th>
	            </tr>
	            <tr>
	                {section name=cont loop=$ARR_TITULOS}
					<th class="col{$smarty.section.cont.iteration}">{$ARR_TITULOS[cont][1]|truncate:30}</th>
					{/section}
	            </tr>
	            {section name=cont1 loop=$ARR_DADOS}
					{if $smarty.section.cont1.iteration is odd}
					<tr class="highlight">
					{else}
					<tr>
					{/if}
					{section name=cont2 loop=$ARR_DADOS[cont1]}
						<td>{$ARR_DADOS[cont1][cont2]}</td>
					{/section}
					</tr>
				{/section}
	        	</tbody>
	        </table>
	        <div id="buttonsReport" style="text-align:center">
				{$BUTTONS_GRID}
			</div>
			<p id="pagin">
	            {$PAGINACAO_GRID}
	        </p>
	    </div>
		{else}
		<div style="text-align:center;width: 712px; padding: 20px 0">
            Ainda não existem ítens cadastrados no sistema para esta área.
		</div>
		 <div id="buttonsReport" style="text-align:center">
			{$BUTTONS_GRID}
		</div>
		<p id="pagin" style="margin-top:-30px"></div>
		{/if}

 </div>
