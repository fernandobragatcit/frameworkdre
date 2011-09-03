<div id="content_sis" style="padding-top: 20px;">
	<h2>{$TITULO_FORMS}</h2>
	<form action="{$ACTION_FORM}" name="{$NOME_FORM}" method="{$METHOD}" id="{$ID_FORM}" {$SUBMIT_FORM} {$CSS_FORM} {$JS_FORM} {$ONSUBMIT_FORM} enctype="multipart/form-data">
		<fieldset>
			<div id="topoFormDre"></div>
			{$ESTRUTURA_FIELDS_FORMS}
		</fieldset>
	</form>
	<script>
		{$VALIDACAO_FORM_JS}
	</script>
</div>
