<script type="text/javascript">var {$ID_COMP}_val = 0;</script>
<table class="listBoxDre">
	<tr>
		<td class="tdListBoxEsq">
			<select name="{$NOME_COMP}_source" id="{$ID_COMP}_source" multiple="multiple" {$STYLE_COMP} class="{$CLASS_COMP}">
				{$SOURCE_LISTBOX}
			</select>
		</td>
		<td  class="tdListBoxCentro">
			<input type="button" style="width:30px" name="insere" value="&gt;&gt;"
				OnClick="addListBox('{$ID_COMP}_source','{$ID_COMP}_dest','{$NOME_COMP}')" />
			<br />
			<input type="button" style="width:30px" name="deleta" value="&lt;&lt;"
				OnClick="delListBox('{$ID_COMP}_dest','{$ID_COMP}_source','{$NOME_COMP}')" />
			<br />
		</td>
		<td  class="tdListBoxDir">
			<select name="{$NOME_COMP}_dest" id="{$ID_COMP}_dest" multiple="multiple" {$STYLE_COMP} class="{$CLASS_COMP}">
				{$DESTINO_LISTBOX}
			</select>
		</td>
	</tr>

</table>

<div id="campos_{$NOME_COMP}">
{$CAMPOS_ALTERACAO}
</div>