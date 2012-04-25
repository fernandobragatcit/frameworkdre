<br />

<textarea name="{$NOME_COMP}" id="{$ID_COMP}" {$ROWS_COMP} {$COLS_COMP} {$WRAP_COMP} {$STYLE_COMP} class="{$CLASS_COMP}"
{$STYLE_COMP} {$LANG_COMP} {$TABINDEX}>{$EDIT_VALUE}</textarea>

{literal}
<script type="text/javascript">
	tinyMCE.init({
		{/literal}
		// General options
		//mode : "textareas",
		mode : "exact",
		elements : "{$ID_EDITOR}",
		theme : "advanced",
		width : {$LARGURA_EDITOR},
		height : {$ALTURA_EDITOR},
		//plugins : "pagebreak,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
		plugins : "pagebreak,table,inlinepopups,paste",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,undo,redo,|,link,unlink,|,cleanup,removeformat,|,code",
		theme_advanced_buttons2 : "pagebreak,cut,copy,pastetext,|,tablecontrols{if $EXIBE_STYLES},|,styleselect{/if}",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center",

{*}
/*
		// Theme options default
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
*/
{*}
		// Example content CSS (should be your site CSS)
		{if $CSS_EDITOR}
			content_css : "{$PATH_SERVIDOR}arquivos/css/{$CSS_EDITOR}",
		{/if}
				
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		{$INCLUDE_STYLES}
		{*}
		{literal}
			style_formats : [
				{title : 'Titulo', block : 'h1', classes : 'titulo_editor'},
				{title : 'SubTitulo', block : 'h2', classes : 'subtitulo_editor'},
				{title : 'Texto Azul', inline : 'span', classes : 'blue'},
				{title : 'Link Botão', block : 'a', classes : 'button'},
				{title : 'Example 2', inline : 'span', classes : 'example2'},
				{title : 'Table styles'},
				{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
			], 
		{/literal}
		{*}
{literal}
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		},
		translate_mode : true,
		language : "pt",

		removeformat : [
			{selector : '*', remove : 'all', split : true, expand : false, block_expand : true, deep : true}
		]
	});
</script>
{/literal}

<!-- Some integration calls -->
<div class="clear"></div>
<a href="javascript:;" onmousedown="tinyMCE.get('{$ID_COMP}').show();">[Exibir Editor]</a>
<a href="javascript:;" onmousedown="tinyMCE.get('{$ID_COMP}').hide();">[Ocultar HTML]</a>
<script type="text/javascript">jQuery(".mceLayout").attr('width','620px');</script>

<br />