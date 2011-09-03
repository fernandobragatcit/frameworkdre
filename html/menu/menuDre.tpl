<div id="container">
	<ul id="navigation-1">
	{section name=cont loop=$ARR_MENU}
		<li><a href="{$ARR_MENU[cont][1]}" title="{$ARR_MENU[cont][0]}">{$ARR_MENU[cont][0]}</a>
			{if $ARR_FILHOS[cont]}
			<ul class="navigation-2">
				{section name=cont2 loop=$ARR_FILHOS[cont]}
				<li class="subMenu2Dre">
					<a href="{$ARR_FILHOS[cont][cont2][1]}" title="{$ARR_FILHOS[cont][cont2][0]}">
						{$ARR_FILHOS[cont][cont2][0]}
					</a>
					{if $ARR_FILHOS2[cont][cont2]}
					<ul class="navigation-3">
						{section name=cont3 loop=$ARR_FILHOS2[cont][cont2]}
							<li class="subMenu2Dre">
								<a href="{$ARR_FILHOS2[cont][cont2][cont3][1]}" title="{$ARR_FILHOS2[cont][cont2][cont3][0]}">
									{$ARR_FILHOS2[cont][cont2][cont3][0]}
								</a>
								<!--
								{if $ARR_FILHOS2[cont][cont2][cont3]}
									<ul class="navigation-3">
										{section name=cont4 loop=$ARR_FILHOS3[cont][cont2][cont3]}
											<li class="subMenu2Dre">
												<a href="{$ARR_FILHOS3[cont][cont2][cont3][cont4][1]}" title="{$ARR_FILHOS3[cont][cont2][cont3][cont4][0]}">
													{$ARR_FILHOS3[cont][cont2][cont3][cont4][0]}
												</a>
											</li>
										{/section}
									</ul>
								{/if}
								-->
							</li>
						{/section}
					</ul>
					{/if}
				</li>
				{/section}
			</ul>
			{/if}
		</li>
	{/section}
	</ul>
</div>
