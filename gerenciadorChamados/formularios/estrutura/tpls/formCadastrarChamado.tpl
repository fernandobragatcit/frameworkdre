<div class=" padding10 width930">
    <h1>{$TITULO}</h1>
    <br />
    {if $MSG_CH}    
        <div class="{$CLASS_ALERTA}"><center><b><p>{$MSG_CH}</p></b></center>{if $INFO_CH}<center><p>{$INFO_CH}</p></center>{/if}</div>
    {/if}
    <form action="{$SALVAR}" name="abrirChamado" method="post">
        <table class="centroContato" border="2">
            <tr>
                <td>Setor:<span class="campoObrigatorio">*</span></td>
                <td><select title="" class="width202" name="id_setor" id="id_setor">
                        <option value="">Selecione...</option>
                        {section name="i" loop="$ARR_SETOR"}
                            <option title="{$ARR_SETOR[i].setor}" value="{$ARR_SETOR[i].id_setor}">{$ARR_SETOR[i].setor}</option>
                        {/section}
                    </select>
                </td>
            </tr>
            <br />
            <tr>
                <td>Prioridade:<span class="campoObrigatorio">* &nbsp</span></td>
                <td><select title="" class="width202" name="id_prioridade" id="id_prioridade">
                        <option value="">Selecione...</option>
                        {section name="i" loop="$ARR_PRIORIDADE"}
                            <option title="{$ARR_PRIORIDADE[i].prioridade}" value="{$ARR_PRIORIDADE[i].id_prioridade}">{$ARR_PRIORIDADE[i].prioridade}</option>
                        {/section}
                    </select>
                </td>
            </tr>
            <br />
            <tr>
                <td>Resumo:<span class="campoObrigatorio">*</span></td>
                <td><input type="text" name="resumo_chamado" size="80"></td>
            </tr>
            <br />
            <tr>
                <td>Descricao:</td>
                <td><textarea rows="5" name="descricao_chamado" cols="50" style="width: 422px; height: 92px; background-color: #f6f6f8" ></textarea></td>
            </tr>
        </table>
        <div class="buttonsReport botoes alinharBotoes">
            <input style="color:#EAEAEB !important; font-weight: bold; background-color: #386280; padding: 6px 20px; border-radius:10px;" title="" class="btnGrid btnForm_ie7"
                   type="submit" value="Salvar"/>
            <a style="color:#EAEAEB !important; border-radius:10px;" href="javascript:void(0);" onclick="location.href = '{$URL_SERVIDOR}?m={$LINK_CANCELAR}';" title="" class="btnGrid btnForm_ie7">Cancelar</a>
        </div>
    </form>
</div>




