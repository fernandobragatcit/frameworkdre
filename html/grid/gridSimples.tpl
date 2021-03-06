<div id="content_sis">
	{$MENS_GRID}
	<h2>{$TITULO_GRID}</h2>
	    <div class="box">
		{if $NUM_DADOS_INI eq "TRUE"}
			{section name="leg" loop="$LEGENDA"}
			   	{if $smarty.section.leg.first}
			    	<div id="legendaIcons">
			    	<strong>Legenda:</strong>
			    {/if}
			    {$LEGENDA[leg].icone} {$LEGENDA[leg].label} 
			    {if !$smarty.section.leg.last}|{else}</div>{/if}
		    {/section}
	        <table>
	            <tbody>
	            <tr>
	                {section name=cont loop=$ARR_TITULOS}
					<th class="col{$smarty.section.cont.iteration}">{$ARR_TITULOS[cont]|truncate:30}</th>
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
	        <p id="pagin">
	            {$PAGINACAO_GRID}
	        </p>
	    </div>
		{else}
			<table>
	            <tbody>
	            <tr>
					<td style="text-align:center; padding: 15px 0px;">
						Ainda não existem ítens cadastrados no sistema para esta área.
					</td>
				</tr>
				</tbody>
	        </table>
		</div>
		{/if}
		<div id="buttonsReport" style="text-align:center">
			<button id="Cadastrar" name="Cadastrar" type="button" onClick="return vaiPara('?c={$VAI_PARA}')"  class="btnReport">Cadastrar</button>
			<button id="Cancelar" name="Cancelar" type="button" onClick="return vaiPara('?')"  class="btnReport">Cancelar</button>
		</div>
 </div>
