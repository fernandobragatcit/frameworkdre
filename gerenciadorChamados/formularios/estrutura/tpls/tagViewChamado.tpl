<div class=" padding10 width930">
    <h1>Visualizar Dados do Chamado</h2>
        <h2>Informações</h2>
        <div class="fundoBranco">
            <table border="2">
                <tr>
                    <td class="InfoChamado">Número:</td><td class="infoResChamado"> {$CHAMADOS.id_chamado}</td>
                </tr>
                <tr>
                    <td class="InfoChamado">data cadastro:</td><td class="infoResChamado"> {$CHAMADOS.data_entrada}</td>
                </tr>
                <tr>
                    <td class="InfoChamado">status:</td><td class="infoResChamado">
                        {if $CHAMADOS.status eq "Novo"}
                            <div class="viewChaNovo">{$CHAMADOS.status}</div>
                        {/if}
                        {if $CHAMADOS.status eq "Em Andamento"}
                            <div class="viewChaAndamento">{$CHAMADOS.status}</div>
                        {/if}
                        {if $CHAMADOS.status eq "Resolvido"}
                            <div class="viewChaResolvido">{$CHAMADOS.status}</div>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class="InfoChamado">prioridade:</td><td class="infoResChamado"> 
                        {if $CHAMADOS.prioridade eq "Imediato"}
                            <div class="pImediato">{$CHAMADOS.prioridade}</div>
                        {/if}
                        {if $CHAMADOS.prioridade eq "Urgente"}
                            <div class="pUrgente">{$CHAMADOS.prioridade}</div>
                        {/if}
                        {if $CHAMADOS.prioridade eq "Alta"}
                            <div class="pAlta">{$CHAMADOS.prioridade}</div>
                        {/if}
                        {if $CHAMADOS.prioridade eq "Normal"}
                            <div class="pNormal">{$CHAMADOS.prioridade}</div>
                        {/if}
                        {if $CHAMADOS.prioridade eq "Baixa"}
                            <div class="pBaixa">{$CHAMADOS.prioridade}</div>
                        {/if}
                        {if $CHAMADOS.prioridade eq "Nenhuma"}
                            <div class="pNenhuma">{$CHAMADOS.prioridade}</div>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class="InfoChamado">setor:</td><td class="infoResChamado"> {$CHAMADOS.setor}</td>
                </tr>
                <tr>
                    <td class="InfoChamado">resumo:</td><td class="infoResChamado"> {$CHAMADOS.resumo_chamado}</td>
                </tr>
                <tr>
                    <td class="InfoChamado" >descrição:</td><td class="infoResChamado"> {$CHAMADOS.descricao_chamado}</td>
                </tr>
            </table>
            <br />
        </div>
        <h2>históricos</h2>
        <div class="fundoBranco">
            Aqui vai vim os históricos do Chamado.
        </div>
        <h2>Botões necessários</h2>

</div>




