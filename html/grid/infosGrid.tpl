{if $ARRLABEL}
    {section name=cont loop=$ARRLABEL}
        <div class="marginTop5 marginBottom10 paddingRight15 width930 font14 left"><strong>{$ARRLABEL[cont]} </strong>{$ARRVALORES[cont]}</div>
    {/section}
{/if}