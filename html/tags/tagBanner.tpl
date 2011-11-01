{if $LINKFOTO}
	<a href="{$LINKFOTO}" title="{$TITLE}">
{/if}
	{FOTO metodo="getThumbImg" idObj="$IDFOTO" largura="$LARGURA" altura="$ALTURA" param3="false"}
{if $LINKFOTO}
	</a>
{/if}