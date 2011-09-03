{section name=cont loop=$ARR_OPTIONS}
	<input type="radio" {$ARR_OPTIONS[cont][8]} name="{$ARR_OPTIONS[cont][0]}" value="{$ARR_OPTIONS[cont][1]}" id="{$ARR_OPTIONS[cont][2]}" title="{$ARR_OPTIONS[cont][5]}" {$ARR_OPTIONS[cont][3]} {$ARR_OPTIONS[cont][4]} onclick="{$ARR_OPTIONS[cont][9]}" /> <span {$ARR_OPTIONS[cont][6]} {$ARR_OPTIONS[cont][7]}>{$ARR_OPTIONS[cont][5]}</span>
{/section}