<div class=" padding10 width930">
    <h1>{$TITULO}</h1>
    <br />
    {if $MSG_CH}    
        <div class="{$CLASS_ALERTA}"><center><b><p>{$MSG_CH}</p></b></center>{if $INFO_CH}<center><p>{$INFO_CH}</p></center>{/if}</div>
    {/if}
    <br />
    <form action="{$SALVAR}" name="abrirPrioridade" method="post">
        <table class="centroContato" border="2">
            <tr>
                <td>Prioridade:<span class="campoObrigatorio">*</span></td>
                <td><input type="text" name="prioridade" size="80" value="{$prioridade}"></td>
            <tr/>
        </table>

        <div class="buttonsReport botoes alinharBotoes">
            <input style="color:#EAEAEB !important; font-weight: bold; background-color: #386280; padding: 6px 20px; border-radius:10px;" title="" class="btnGrid btnForm_ie7"
                   type="submit" value="Salvar"/>
            <a style="color:#EAEAEB !important; border-radius:10px;" href="javascript:void(0);" onclick="location.href = '{$URL_SERVIDOR}?m={$LINK_CANCELAR}';" title="" class="btnGrid btnForm_ie7">Cancelar</a>
        </div>
    </form>


</div>
