<div class=" padding10 width930">
    <h1>{$TITULO}</h1>
    <div class="gridFGV">
        <table class="tableMenu">
            <thead >
                <tr>
                    <th ><span>Id</span></th>
                    <th ><span>Resumo</span></th>
                    <th ><span>Solicitante</span></th>
                    <th ><span>Data Entrada</span></th>
                    <th ><span>Setor</span></th>
                    <th ><span>Prioridade</span></th>
                    <th ><span>Status</span></th>
                </tr>
            </thead>
            <tbody>
                {section name="cont" loop="$CHAMADOS"}
                    {if $CHAMADOS[cont].status eq "Novo"}
                        <tr class="statusNovo">
                        {elseif $CHAMADOS[cont].status eq "Em Andamento"}
                        <tr class="statusAndamento">
                        {else $CHAMADOS[cont].status eq "Resolvido"}
                        <tr class="statusResolvido">
                    {/if}
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].id_chamado}">{$CHAMADOS[cont].id_chamado}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].resumo_chamado}">{$CHAMADOS[cont].resumo_chamado}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].nome_usuario}">{$CHAMADOS[cont].nome_usuario}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].data_entrada}">{$CHAMADOS[cont].data_entrada}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].setor}">{$CHAMADOS[cont].setor}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].prioridade}">{$CHAMADOS[cont].prioridade}</span></td>
                        <td ><span id="{$smarty.section.cont.iteration}" value="{$CHAMADOS[cont].status}">{$CHAMADOS[cont].status}</span></td>
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
