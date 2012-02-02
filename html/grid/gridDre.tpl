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
	    <form method="post" class="right" id="formFiltro" action="{$URL_SITE}{$LINK_BUSCAR}">
	    	<input type="text" name="buscaGrid" id="campoFiltro" value="{$VALOR_BUSCA}" />
	    	<input type="submit" value="Buscar" id="submitFiltro" />
	    </form>
	    <br style="clear:both;" />
		{section name="leg" loop="$LEGENDA"}
		   	{if $smarty.section.leg.first}
		    	<div id="legendaIcons">
		    	<strong>Legenda:</strong>
		    {/if}
		    {$LEGENDA[leg].icone} {$LEGENDA[leg].label} 
		    {if !$smarty.section.leg.last}|{else}</div>{/if}
	    {/section}
	    <br style="clear:both;" />
	    {if $EXIBE_FILTRO}
    		<button id="abrirFiltro" onclick="abreFechaDiv('filtroGrid')">Exibir filtros</button>
		    <div id="boxFiltro">
				<form method="post" id="filtroGrid" action="{$ACTION_FILTRO}">
		    		<h2>Especificação de Filtros</h2>
		    		
				    <br style="clear:both;" />
			    	<div class="trataFloat">
				    	{section name="fil" loop="$CAMPOS_FILTRO"}
				    		<div class="campoFiltro">
					    		<div><label>{$CAMPOS_FILTRO[fil].label}</label></div>
					    		<div>{$CAMPOS_FILTRO[fil].campo}</div>
				    		</div>
				    	{/section}
				    </div>
				    <div id="alinhaBtnFiltro">
					    <input type="button" onclick="jQuery('#filtroGrid').submit();" value="Buscar" class="btnGrid" />
				    </div>
			    </form>
			</div>
			{if $ABRE_FILTRO}<script type="text/javascript">abreFechaDiv('filtroGrid');</script>{/if}
		    <br style="clear:both;" />
		{/if}
    	
	    {if $NUM_DADOS_INI eq "TRUE"}
	        <table style="width:726px">
	            <tbody>
	            <tr>
					<th colspan="{$ARR_TITULOS|@count}">
					</th>
	            </tr>
	            <tr>
	                {section name=cont loop=$ARR_TITULOS}
					<th class="col{$smarty.section.cont.iteration}  {$ARR_TITULOS[cont].class}">
						{if $ARR_TITULOS[cont][0] == "select"}
							<select title="{$ARR_TITULOS[cont][3]}" class="selectGrid" name="filtro_{$ARR_TITULOS[cont][2]}" 
								id="col{$smarty.section.cont.iteration}" onchange="location.href = '{$URL_MOMENTO}&fil='+this.value;">
							  <option value="{$ARR_TITULOS[cont][5]}">{$ARR_TITULOS[cont][3]}</option>
							  {section name="tlt" loop="$ARR_TITULOS[cont][4]"}
						  	  	<option {$ARR_TITULOS[cont][4][tlt][4]} title="{$ARR_TITULOS[cont][4][tlt][1]}" value="{$ARR_TITULOS[cont][4][tlt][3]}">{$ARR_TITULOS[cont][4][tlt][1]}</option>
						  	  {/section}
							</select>
						{else}
							{$ARR_TITULOS[cont][1]|truncate:30}
						{/if}
					</th>
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
