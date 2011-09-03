{section name=cont loop=$ARR_OPTIONS}
	{if $ARR_OPTIONS[cont][0] == $OPTION_SELECTED && $TIPO_OPTIONS != 'listBox'}
		<option value="{$ARR_OPTIONS[cont][0]}" title="{$ARR_OPTIONS[cont][1]|truncate:120}" selected="selected">{$ARR_OPTIONS[cont][1]|truncate:120}</option>
	{else}
		<option value="{$ARR_OPTIONS[cont][0]}" title="{$ARR_OPTIONS[cont][1]|truncate:120}">{$ARR_OPTIONS[cont][1]|truncate:120}</option>
	{/if}
{/section}