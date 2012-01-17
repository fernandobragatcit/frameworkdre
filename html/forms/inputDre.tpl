<input type="{if $TYPE_COMP == "file"}$TYPE_COMP{else}text{/if}" name="{$NOME_COMP}" {$SIZE_COMP} {$ONKEYUP_COMP} {$ONFOCUS_COMP} {$ONKEYDOWN_COMP} {$ONKEYPRESS_COMP} id="{$ID_COMP}" {$STYLE_COMP} {$COMP_ONCLICK} {$ONBLUR_COMP}
class="{$CLASS_COMP}" {$ONCHANGE_COMP} title="{$TITLE_COMP}" {$DIR_COMP} {$MAXLENGTH_COMP} {$LANG_COMP} {$TABINDEX_COMP} {$READONLY} value="{$VALUE_COMP}" {$DISABLED_COMP} />
{$MASK_COMP}

{if $TYPE_COMP == "file" && $COLOCA_NULL == true && $OBRIGATORIO != true}
	<br style="clear:both;" />
	<input type="checkbox" value="1" name="{$NOME_COMP}_null" id="{$ID_COMP}_null" class="campo_anula" /> <label class="label_anula">Nulo</label>
{/if}