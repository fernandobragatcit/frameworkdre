<ul class="menulist" id="listMenuRoot">
	{section name=cont loop=$ARR_MENU}
		<li>
			<a href="{$ARR_MENU[cont][2]}">{$ARR_MENU[cont][1]}</a>
			{if $ARR_FILHOS[cont]}
				<ul>
				{section name=cont2 loop=$ARR_FILHOS[cont]}
					<li><a href="{$ARR_FILHOS[cont][cont2][3]}">{$ARR_FILHOS[cont][cont2][2]}</a></li>
				{/section}
				</ul>
			{/if}
		</li>
	{/section}
</ul>