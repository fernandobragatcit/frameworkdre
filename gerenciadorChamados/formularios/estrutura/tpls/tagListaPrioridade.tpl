<div class=" padding10 width930">
    <h1>{$TITULO}</h1>
    <div class="gridFGV">
        <table class="tableMenu">
            <thead >
                <tr>
                    <th ><span>Id</span></th>
                    <th ><span>Prioridade</span></th>
                    <th ><span>Data Cadastro</span></th>
                    <th ><span>Cadastrado Por</span></th>
                </tr>
            </thead>
            <tbody>
                {section name="cont" loop="$PRIORIDADES"}
                    {if $smarty.section.cont.iteration%2==0}
                        <tr class="listaPar">
                        {else}
                        <tr class="listaImpar">
                        {/if}
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$PRIORIDADES[cont].id_prioridade}">{$PRIORIDADES[cont].id_prioridade}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$PRIORIDADES[cont].prioridade}">{$PRIORIDADES[cont].prioridade}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$PRIORIDADES[cont].data_cadastro}">{$PRIORIDADES[cont].data_cadastro}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$PRIORIDADES[cont].usu_cadastro}">{$PRIORIDADES[cont].usu_cadastro}</span></td>
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
