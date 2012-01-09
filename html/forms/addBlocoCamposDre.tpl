{section name=da loop=$DADOS}
	{assign var=id value=$smarty.section.da.index}
	<div {if $CLASS != ""}class="{$CLASS}"{else}style="width: 100%; padding:10px; margin-top:10px; display:block; float:left;"{/if}>
		<span {if $CSSAREA != ""} class="{$CSSAREA}" {else} style="float:left; width:80%; padding:0px 10px 10px 10px; border:1px solid #ccc;" {/if}>
			<span title="Remover" class="right removerBloco" onClick="removeBlocoCampo('{$IDCOMPONENTE}', jQuery(this).parent());"
				style="text-align: center; cursor: pointer; position:relative; top:-8px; float:right; margin-bottom:-40px; margin-right:-8px;">
				<div><img width="12" height="12" alt="Remover" src="{$smarty.const.URL_DEP_IMGS}/close.png" class="marginTop8"></div>
			</span>
			{section name="hid" loop=$ARR_HIDDEN}
				{assign var=val value=$ARR_LINHAS[per].id}
				<input type="hidden" name="{$IDCOMPONENTE}[{$id}][{$ARR_HIDDEN[hid].id}]" class="{$ARR_HIDDEN[hid].class}" value="{$DADOS[da][$val]}" />
			{/section}
			{assign var=aux value=0}
			{section name=lin loop=$CONT_LINHAS}
				{section name=per loop=$ARR_LINHAS}
					{if $ARR_LINHAS[per].linha == $smarty.section.lin.index}
					{assign var=val value=$ARR_LINHAS[per].id}
					{assign var=file value= 'id_'|cat:$ARR_LINHAS[per].id}
						{if $ARR_LINHAS[per].cont < 2}
							{if $aux == 1}
								 </div>
							{else}
								<div style="width: 100%;">
									<span style="float:left; width:100%; margin-top:5px;">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$file]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="large"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""} class="{$ARR_LINHAS[per].class}"{/if} value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}large{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange} >
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
								</div>
							{/if}
							{assign var=aux value=0}
						{else}
							{if $aux == 0}
								<div style="width: 100%;">
					           		<span style="float:left; width:49%; margin-top:5px;">
					           			<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$file]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="medium {$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}small{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
									{assign var=aux value=1}
							{elseif $aux == 1}
					           		<span style="float:right; width:49%; margin-top:5px; padding-left:5px">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$file]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="medium {$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}large{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}

									</span>
								</div>
								{assign var=aux value=0}
							{/if}
						{/if}
					{/if}
				{/section}
			{/section}
		</span>
	</div>
	{if $id <= count($DADOS)}{assign var=id value=$id+1}{/if}
{sectionelse}
	{assign var=id value=0}
	<div {if $CLASS != ""}class="{$CLASS}"{else}style="width: 100%; padding:10px; margin-top:10px; display:block;"{/if}>
		<span {if $CSSAREA != ""} class="{$CSSAREA}" {else} style="float:left; width:80%; padding:0px 10px 10px 10px; border:1px solid #ccc;" {/if}>
			<span title="Remover" class="right removerBloco" onClick="removeBlocoCampo('{$IDCOMPONENTE}', jQuery(this).parent());"
				style="text-align: center; cursor: pointer; position:relative; top:-8px; float:right; margin-bottom:-40px; margin-right:-8px;">
				<div><img width="12" height="12" alt="Remover" src="{$smarty.const.URL_DEP_IMGS}/close.png" class="marginTop8"></div>
			</span>
			{section name="hid" loop=$ARR_HIDDEN}
				{assign var=val value=$ARR_LINHAS[per].id}
				<input type="hidden" name="{$IDCOMPONENTE}[{$id}][{$ARR_HIDDEN[hid].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_HIDDEN[hid].id}" class="{$ARR_HIDDEN[hid].class}" value="{$DADOS[da][$val]}" />
			{/section}
			{assign var=aux value=0}
			{section name=lin loop=$CONT_LINHAS}
				{section name=per loop=$ARR_LINHAS}
					{if $ARR_LINHAS[per].linha == $smarty.section.lin.index}
					{assign var=val value=$ARR_LINHAS[per].id}
						{if $ARR_LINHAS[per].cont < 2}
							{if $aux == 1}
								 </div>
							{else}
								<div style="width: 100%;">
									<span style="float:left; width:100%; margin-top:5px;">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="large"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}large{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
								</div>
							{/if}
							{assign var=aux value=0}
						{else}
							{if $aux == 0}
								<div style="width: 100%;">
					           		<span style="float:left; width:49%; margin-top:5px;">
					           			<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="small {$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}small{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
									{assign var=aux value=1}
							{elseif $aux == 1}
					           		<span style="float:right; width:49%; margin-top:5px; padding-left:5px">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="inputFile"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="small {$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCampo{$id} {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}small{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_{$id}_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[{$id}][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}

									</span>
								</div>
								{assign var=aux value=0}
							{/if}
						{/if}
					{/if}
				{/section}
			{/section}
		</span>
	</div>
	{assign var=id value=1}
{/section}
<div id="{$IDCOMPONENTE}Add"></div>
<br style="clear:both;">
<div class="left adicionarBloco" style="margin-top:10px;">
	<span align="left"><strong><a href="javascript:addBlocoCampo('{$IDCOMPONENTE}');" class="darkBrown">[+] Adicionar nova</a></strong></span>
	<span><input type="hidden" value="{if $id}{$id}{else}0{/if}" id="{$IDCOMPONENTE}Count" name="{$IDCOMPONENTE}Count"></span>
</div>
<div id="{$IDCOMPONENTE}Template" class="none">
	<div {if $CLASS != ""}class="{$CLASS}"{else}style="width: 100%; padding:10px; margin-top:10px; display:block; float:left;"{/if}>
		<span {if $CSSAREA != ""} class="{$CSSAREA}" {else} style="float:left; width:80%; padding:0px 10px 10px 10px; border:1px solid #ccc;" {/if}>
			<span title="Remover" class="right removerBloco" onClick="removeBlocoCampo('{$IDCOMPONENTE}', jQuery(this).parent());"
				style="text-align: center; cursor: pointer; position:relative; top:-8px; float:right; margin-bottom:-40px; margin-right:-8px;">
				<div><img width="12" height="12" alt="Remover" src="{$smarty.const.URL_DEP_IMGS}/close.png" class="marginTop8"></div>
			</span>
			{section name="hid" loop=$ARR_HIDDEN}
				{assign var=val value=$ARR_LINHAS[per].id}
				<input type="hidden" name="{$IDCOMPONENTE}[{$id}][{$ARR_HIDDEN[hid].id}]" class="{$ARR_HIDDEN[hid].class}" value="{$DADOS[da][$val]}" />
			{/section}
			{assign var=aux value=0}
			{section name=lin loop=$CONT_LINHAS}
				{section name=per loop=$ARR_LINHAS}
					{if $ARR_LINHAS[per].linha == $smarty.section.lin.index}
					{assign var=val value=$ARR_LINHAS[per].id}
						{if $ARR_LINHAS[per].cont < 2}
							{if $aux == 1}
								 </div>
							{else}
								<div style="width: 100%;">
									<span style="float:left; width:100%; margin-top:5px;">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}">

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="large"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if}  value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCamponumber {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}large{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
								</div>
							{/if}
							{assign var=aux value=0}
						{else}
							{if $aux == 0}
								<div style="width: 100%;">
					           		<span style="float:left; width:49%; margin-top:5px;">
					           			<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}">

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if} value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCamponumber {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}small{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}
									</span>
									{assign var=aux value=1}
							{elseif $aux == 1}
					           		<span style="float:right; width:49%; margin-top:5px; padding-left:5px">
										<label> {$ARR_LINHAS[per].label}{$ARR_LINHAS[per].obrigatorio}:</label>

										{if $ARR_LINHAS[per].tipo == 'file'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number]" value="{$DADOS[da][$val]}" />
											<input type="hidden" name="{$IDCOMPONENTE}[{$id}][id_{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" />

										{elseif $ARR_LINHAS[per].tipo == 'text'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}class="small"{/if} type="{$ARR_LINHAS[per].tipo}" {$ARR_LINHAS[per].keypress} maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'checkbox'}
											<input {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{/if} value="{$ARR_LINHAS[per].value}" type="{$ARR_LINHAS[per].tipo}"  name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" value="{$DADOS[da][$val]}" {$ARR_LINHAS[per].onchange} />

										{elseif $ARR_LINHAS[per].tipo == 'data'}
											<input class="dataCamponumber {if $ARR_LINHAS[per].class != ""}{$ARR_LINHAS[per].class}"{else}small{/if}" type="text" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" id="{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}" value="{$DADOS[da][$val]}" />
											<script type="text/javascript">jQuery("#{$IDCOMPONENTE}_number_{$ARR_LINHAS[per].id}").mask("99/99/9999");</script>

										{elseif $ARR_LINHAS[per].tipo == 'textarea'}
											<textarea {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} type="{$ARR_LINHAS[per].tipo}" maxlength="{$ARR_LINHAS[per].maxlenght}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]">{$DADOS[da][$val]}</textarea>

										{elseif $ARR_LINHAS[per].tipo == 'select'}
											<select {if $ARR_LINHAS[per].class != ""}class="{$ARR_LINHAS[per].class}"{else}style="width: 100%;"{/if} title="{$ARR_LINHAS[per].label}" name="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]"
													id="{$IDCOMPONENTE}[number][{$ARR_LINHAS[per].id}]" {$ARR_LINHAS[per].onchange}>
												<option value="">Selecione...</option>
												{section name=op loop=$ARR_LINHAS[per].option}
													<option {if $DADOS[da][$val] == $ARR_LINHAS[per].option[op].value} selected {/if} value="{$ARR_LINHAS[per].option[op].value}">{$ARR_LINHAS[per].option[op].label}</option>
												{/section}
											</select>
										{/if}

									</span>
								</div>
								{assign var=aux value=0}
							{/if}
						{/if}
					{/if}
				{/section}
			{/section}
		</span>
	</div>
</div>
