<br />
<div class="multiUploads">
	<span>Defina o número de arquivos que gostaria de enviar simultaneamente:</span>
	<select id="selQtdeCamposUp" name="selQtdeCamposUp" {$STYLE_COMP} class="{$CLASS_COMP}"  title="{$TITLE_COMP}"
	onChange="javascript:selCampoMUpLoad(this.value,'{$NOME_COMP}')">
	  {section name=cont start=1 loop=$NUM_UPLOADS+1}
	  <option value="{$smarty.section.cont.index}">{$smarty.section.cont.index}</option>
	  {/section}
	</select>
	<div id="camposMultUpload">
		<label>Arquivo 1 :
	    	<span class="small error" id="{$NOME_COMP}_0_erro"></span>
	    </label>
	    <input type="file" name="{$NOME_COMP}_0" id="{$NOME_COMP}_0" />
	</div>
</div>


