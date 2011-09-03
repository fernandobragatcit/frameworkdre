<div class="{$ID_COMP}">
<input type="text" name="{$NOME_COMP}" {$SIZE_COMP} id="{$ID_COMP}" {$STYLE_COMP} class="{$CLASS_COMP}" title="{$TITLE_COMP}" {$DIR_COMP} {$LANG_COMP} {$TABINDEX_COMP}  {$COMP_ONCLICK} value="{$VALOR_DATA}" />
<input type="text" onmousedown="javascript:datepicker()" class="datePickerImage btnDatas left" onchange="javascript:copyText('{$ID_COMP}')" style="cursor:pointer">
<script>jQuery('#{$ID_COMP}').mask("99/99/9999");</script>
</div>