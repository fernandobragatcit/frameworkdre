<span style="float:left">Número de Registros: {$NUMREGS_REPORT} </span>
<br />
{if $PAGINACAO == true}
<span id="linksPaginacao">
	{if $PAGATUAL_REPORT == 1}
		&lt;&lt; Primeira&nbsp;&nbsp;
		&lt; Anterior Página&nbsp;&nbsp;
	{else}
		<a href="{$LINK_PRIMEIRAPAG}">&lt;&lt; Primeira</a>&nbsp;&nbsp;
		<a href="{$LINK_PAGANTERIOR}">&lt; Anterior</a> Página&nbsp;&nbsp;
	{/if}
	{$PAGATUAL_REPORT} de {$NUMPAGS_REPORT}&nbsp;&nbsp;
	{if $PAGATUAL_REPORT == $NUMPAGS_REPORT}
		Próxima &gt;&nbsp;&nbsp;
		Última &gt;&gt;
	{else}
		<a href="{$LINK_PROXIMAPAG}">Próxima &gt;</a>&nbsp;&nbsp;
		<a href="{$LINK_ULTIMAPAG}">Última &gt;&gt;</a>
	{/if}
</span>
{/if}