<div class=" padding10 width930">
    <h1>{$TITULO}</h1>

    <form action="{$SALVAR}" name="abrirSetor" method="post">
        <table class="centroContato" border="2">
            <tr>
                <td>Setor:<span class="campoObrigatorio">*</span></td>
                <td><input type="text" name="setor" size="80"></td>
            <tr/>
            <tr>
                <td>E-mail: &nbsp &nbsp</span></td>
                <td><input type="text" name="email_setor" size="80"></td>
            <tr/>
        </table>
        
        <div class="buttonsReport botoes alinharBotoes">
            <input style="color:#EAEAEB !important; font-weight: bold; background-color: #386280; padding: 6px 20px; border-radius:10px;" title="" class="btnGrid btnForm_ie7"
                   type="submit" value="Salvar"/>
            <a style="color:#EAEAEB !important; border-radius:10px;" href="javascript:void(0);" onclick="location.href = '{$URL_SERVIDOR}?m={$LINK_CANCELAR}';" title="" class="btnGrid btnForm_ie7">Cancelar</a>
        </div>
    </form>


</div>
