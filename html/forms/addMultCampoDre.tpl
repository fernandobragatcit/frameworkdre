<div id="contentAddMultCampoText_0" class="spacoComponente">
{section name=cont loop=$ARR_CAMPOS}
	<input type="text" name="{$NOME_COMP}_{$smarty.section.cont.index}" id="{$NOME_COMP}_{$smarty.section.cont.index}" {$STYLE_COMP} class="{$CLASS_COMP}" title="{$TITLE_COMP}" value="{$ARR_CAMPOS[cont][1]}" />
	<input type="hidden" name="{$ID_COMP}_{$smarty.section.cont.index}" id="{$ID_COMP}_{$smarty.section.cont.index}" value="{$ARR_CAMPOS[cont][0]}"/>
	
	<button type="button" name="addField" class="btnPeq" title="Adicionar outro Campo de texto"
		onclick="javascript:addCampoText('{$NOME_COMP}_{$smarty.section.cont.index}','{$ID_COMP}_{$smarty.section.cont.index}','{$STYLE_COMP}','{$CLASS_COMP}','{$TITLE_COMP}','{$NOME_BTN_ADD}',1);">
		{$NOME_BTN_ADD}
	</button>
{sectionelse}
	<input type="text" name="{$NOME_COMP}_0" id="{$NOME_COMP}_0" {$STYLE_COMP} class="{$CLASS_COMP}" title="{$TITLE_COMP}" />
	<input type="hidden" name="{$ID_COMP}_0" id="{$ID_COMP}_0" />
	
	<button type="button" name="addField" class="btnPeq" title="Adicionar outro Campo de texto"
		onclick="javascript:addCampoText('{$NOME_COMP}','{$ID_COMP}','{$STYLE_COMP}','{$CLASS_COMP}','{$TITLE_COMP}','{$NOME_BTN_ADD}',1);">
		{$NOME_BTN_ADD}
	</button>
{/section}
</div>
<div id="contentAddMultCampoText_1" class="spacoComponente">
</div>
{$MASK_COMP}