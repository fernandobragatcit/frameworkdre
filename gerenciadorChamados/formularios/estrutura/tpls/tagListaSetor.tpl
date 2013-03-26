<div class=" padding10 width930">
    <h1>{$TITULO}</h1>
    <div class="gridFGV">
        <table class="tableMenu">
            <thead >
                <tr>
                    <th ><span>Id</span></th>
                    <th ><span>Setor</span></th>
                    <th ><span>E-mail</span></th>
                    <th ><span>Ações</span></th>
                </tr>
            </thead>
            <tbody>
                {section name="cont" loop="$SETORES"}
                    {if $smarty.section.cont.iteration is odd}
                        <tr class="highlight">
                        {else}
                        <tr>
                        {/if}
                        <td style="background-color: #F0FFFF"><span id="{$smarty.section.cont.iteration}" value="{$SETORES[cont].id_setor}">{$SETORES[cont].id_setor}</span></td>
                        <td style="background-color: #F0FFFF"><span id="{$smarty.section.cont.iteration}" value="{$SETORES[cont].setor}">{$SETORES[cont].setor}</span></td>
                        <td style="background-color: #F0FFFF"><span id="{$smarty.section.cont.iteration}" value="{$SETORES[cont].email_setor}">{$SETORES[cont].email_setor}</span></td>
                        <td style="background-color: #F0FFFF"><span id="{$smarty.section.cont.iteration}" value="{$SETORES[cont].email_setor}">{$SETORES[cont].email_setor}</span></td>
                    </tr>
                {/section}
            </tbody>
        </table>
    </div>
    {if $PAGINACAO eq "true"}
        <div class="grid_14 suffix_4">
            {if $PAG_ANTERIOR}
                <a href="javascript:void(0);" onclick="paginacao('{$PAG_ANTERIOR}');" class="marginRight55 button width65 marginTop20 left">Mais antigos</a>
            {/if}
        </div>
        <div class="grid_4 marginLeft25">
            {if $PAG_PROXIMO}
                <a href="javascript:void(0);" onclick="paginacao('{$PAG_PROXIMO}');" class="button marginTop20 left">Mais recentes</a>
            {/if}
        </div>
    {/if}
</div>
