<img id="{$ID_COMP}_img" src="{$CAPTCHA_SHOW}" title="{$TITLE_COMP}" {$STYLE_IMG_CAPTCHA} />
<input type="text"  id="{$ID_COMP}" name="{$NOME_COMP}" {$SIZE_COMP} {$MAXLENGTH_COMP} {$LANG_COMP} {$TABINDEX_COMP}
value="{$VALUE_COMP}" {$STYLE_COMP} class="{$CLASS_COMP}" title="{$TITLE_COMP}"/>
<strong>*</strong>
<br />
<a href="javascript:void(carregaImgCaptcha('{$ID_COMP}_img','{$CAPTCHA_SHOW}'))">
	{$TEXTO_LINK_CAPTCHA}
</a>
