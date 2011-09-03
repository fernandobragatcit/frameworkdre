<span style="float:left; margin-left: 20px;">Número de Registros: {$NUMREGS_GRID} </span>
<br />
{if $PAGINACAO == true}
<span id="linksPaginacao">
	{if $PAGATUAL_GRID == 1}
		&lt;&lt; Primeira&nbsp;&nbsp;
		&lt; Anterior Página&nbsp;&nbsp;
	{else}
		<a href="{$LINK_PRIMEIRAPAG}">&lt;&lt; Primeira</a>&nbsp;&nbsp;
		<a href="{$LINK_PAGANTERIOR}">&lt; Anterior</a> Página&nbsp;&nbsp;
	{/if}
	{$PAGATUAL_GRID} de {$NUMPAGS_GRID}&nbsp;&nbsp;
	{if $PAGATUAL_GRID == $NUMPAGS_GRID}
		Próxima &gt;&nbsp;&nbsp;
		Última &gt;&gt;
	{else}
		<a href="{$LINK_PROXIMAPAG}">Próxima &gt;</a>&nbsp;&nbsp;
		<a href="{$LINK_ULTIMAPAG}">Última &gt;&gt;</a>
	{/if}
</span>
{/if}