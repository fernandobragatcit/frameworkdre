<div id="reportDre">
	<div id="tituloReport">
		<h1>{$TITULO_REPORT}</h1>
	</div>
	{$MENS_REPORT}
	{if $NUM_DADOS_INI eq "TRUE"}
		<table id="tableReport">
			<thead>
				<tr id="headReport">
					{section name=cont loop=$ARR_TITULOS}
					<th {$ARR_TITULOS[cont][0]}>{$ARR_TITULOS[cont][1]|truncate:30}</th>
					{/section}
				</tr>
			</thead>
			<tbody>
				{section name=cont1 loop=$ARR_DADOS}
					<tr>
					{section name=cont2 loop=$ARR_DADOS[cont1]}
						<td>{$ARR_DADOS[cont1][cont2]}</td>
					{/section}
					</tr>
				{/section}
			</tbody>
		</table>
		<div id="paginaReport">
			{$PAGINACAO_REPORT}
		</div>
	{else}
		<div style="text-align:center">
			Ainda não existem ítens cadastrados no sistema para esta área.
		</div>
	{/if}
	<div id="buttonsReport" style="text-align:center">
		{$BUTTONS_REPORT}
	</div>
</div>
