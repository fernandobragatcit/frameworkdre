{section name=cont loop=$ARR_CHECKS}
	<div class="divCheckBoxGroupByQuery">
		<input type="checkbox" name="{$NOME_COMP}_{$ARR_CHECKS[cont][0]}" {$ONKEYPRESS_COMP} id="{$ID_COMP}" {$STYLE_COMP}
			{$COMP_ONCLICK} class="{$CLASS_COMP}" title="{$TITLE_COMP}" {$LANG_COMP} {$TABINDEX_COMP}
			value="{$ARR_CHECKS[cont][0]}" {$ARR_CHECKS[cont][2]} />
		<label class="labelCheckBoxGroupByQuery">{$ARR_CHECKS[cont][1]}</label>
	</div>
{/section}

