{if $IDFOTO}
    {if $LINKFOTO}
        <a href="{$LINKFOTO}" title="{$TITLE}">
        {/if}
        {FOTO metodo="getThumbImg" idObj="$IDFOTO" largura="$LARGURA" altura="$ALTURA"}
        {if $LINKFOTO}
        </a>
    {/if}
{/if}