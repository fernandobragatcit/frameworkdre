<div class="navbar navbar_menu_admin">
    <div class="navbar-inner menu_admin">
        <div id="container">
            {section name=cont loop=$ARR_MENU}
                {if $smarty.section.cont.first}
                    <ul class="nav">
                    {/if}
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="{$ARR_MENU[cont][1]}" title="{$ARR_MENU[cont][0]}">{$ARR_MENU[cont][0]}</a>
                        {if $ARR_FILHOS[cont]}
                            {section name=cont2 loop=$ARR_FILHOS[cont]}
                                {if $smarty.section.cont2.first}
                                    <ul class="dropdown-menu">
                                    {/if}
                                    <li {if $ARR_FILHOS2[cont][cont2]}class="dropdown-submenu"{/if}>
                                        <a href="{$ARR_FILHOS[cont][cont2][1]}" title="{$ARR_FILHOS[cont][cont2][0]}">
                                            {$ARR_FILHOS[cont][cont2][0]}
                                        </a>
                                        {if $ARR_FILHOS2[cont][cont2]}
                                            {section name=cont3 loop=$ARR_FILHOS2[cont][cont2]}
                                                {if $smarty.section.cont3.first}
                                                    <ul class="dropdown-menu">
                                                    {/if}
                                                    <li {if $ARR_FILHOS3[cont][cont2][cont3]}class="dropdown-submenu"{/if}>
                                                        <a href="{$ARR_FILHOS2[cont][cont2][cont3][1]}" title="{$ARR_FILHOS2[cont][cont2][cont3][0]}">
                                                            {$ARR_FILHOS2[cont][cont2][cont3][0]}
                                                        </a>
                                                        {if $ARR_FILHOS3[cont][cont2][cont3]}
                                                            {section name=cont4 loop=$ARR_FILHOS3[cont][cont2][cont3]}
                                                                {if $smarty.section.cont4.first}
                                                                    <ul class="dropdown-menu">
                                                                    {/if}
                                                                    <li >
                                                                        <a href="{$ARR_FILHOS3[cont][cont2][cont3][cont4][1]}" title="{$ARR_FILHOS3[cont][cont2][cont3][cont4][0]}">
                                                                            {$ARR_FILHOS3[cont][cont2][cont3][cont4][0]}
                                                                        </a>
                                                                    </li>
                                                                    {if $smarty.section.cont4.last}
                                                                    </ul>
                                                                {/if}
                                                            {/section}
                                                        {/if}
                                                    </li>
                                                    {if $smarty.section.cont3.last}
                                                    </ul>
                                                {/if}
                                            {/section}
                                        {/if}
                                    </li>
                                    {if $smarty.section.cont2.last}
                                    </ul>
                                {/if}
                            {/section}
                        {/if}
                    </li>
                    {if $smarty.section.cont.last}
                    </ul>
                {/if}
            {/section}
            <ul class="nav pull-right">
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Perfil">Olá {$NOME_USUARIO} <i class="icon-user"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="?m=Zm9ybXVsYXJpb3MmZj1DcnVkUGVyZmlsJmE9cGVyZmls" title="Meu Perfil">Meu Perfil</a></li>
                        <li><a href="?m=Zm9ybXVsYXJpb3MmZj1DcnVkUGVyZmlsJmE9Zm9ybUFsdFNlbmhh" title="Alterar Senha">Alterar Senha</a></li>               
                        <li><a href="?c=TG9naW4mYT1Mb2dvdXQ=" title="Sair">Sair</a></li>        
                    </ul>
                </li>          
            </ul>
        </div>
    </div>
</div>