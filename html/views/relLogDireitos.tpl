{literal}
    <script type="text/javascript">
        function exibeAba(div1, div2, aba1, aba2) {
            var divstyle = new String();
            divstyle = document.getElementById(div1).style.display;

            var divAux = new String();
            divAux = document.getElementById(div2).style.display;

            if (divAux == "block" || divAux == "") {
                jQuery("#" + aba2).removeClass("aba");
                jQuery("#" + aba2).addClass("abaDisable");
                jQuery("#" + aba1).removeClass("abaDisable");
                jQuery("#" + aba1).addClass("aba");
                document.getElementById(div2).style.display = "none";
                document.getElementById(div1).style.display = "block";
            } else {
                jQuery("#" + aba2).removeClass("abaDisable");
                jQuery("#" + aba2).addClass("aba");
                jQuery("#" + aba1).removeClass("aba");
                jQuery("#" + aba1).addClass("abaDisable");
                document.getElementById(div2).style.display = "block";
                document.getElementById(div1).style.display = "none";
            }
            return false;
        }


    </script>
{/literal}
<div class="width900 marginBottom25 left  formulario2">
    <h1 class="noMargin">{$TITULO_FORMS}</h1>
</div>

<div class="alinhaBtns marginBottom20 width">{$BTN_CANCELAR}</div>
<div class="estatistica2">
    <div class="perfilEstatistica">
        {if !$EXC && !$STATUS}
            <div class="width900 relative"  >
                <div id="aba1" onclick="exibeAba('divAba1', 'divAba2', 'aba1', 'aba2');" class="width120 padding10 aba left textCenter"><span class="cursorPointer  branco">Dados atualizados</span></div>
                {if !$CAD}
                    <div  id="aba2" onclick="exibeAba('divAba1', 'divAba2', 'aba1', 'aba2');" class="width120 padding10 abaDisable left textCenter"><span class="branco cursorPointer">Dados anteriores</span></div>
                {/if}
            </div>
        {/if}
        <div class="clear"></div>
        <div class="width900 relative margin0">
            <table class="formatosLog marginBottom20 margin0">
                <thead>
                    <tr>
                        <td colspan="1" class=" width100 ">Ação</td>
                        <td colspan="2" class=" width100 ">Nome Responsável</td>
                        <td colspan="2" class=" width100 ">Email Responsável</td>
                        <td colspan="2" class=" width100 ">Nome Usuario</td>
                        <td colspan="2" class=" width100 ">Email Usuario</td>
                        <td class=" width100 ">Data da Ação</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="1" class=" width100 ">{$ARRLOG.descricao}</td>
                        <td colspan="2" class=" width100 ">{$ARRLOG.nome_usuario_alt}</td>
                        <td colspan="2" class=" width100 ">{$ARRLOG.email_usuario_alt}</td>
                        <td colspan="2" class=" width100 ">{$ARRLOG.nome_usuario}</td>
                        <td colspan="2" class=" width100 ">{$ARRLOG.email_usuario}</td>
                        <td class=" width100 ">{$ARRLOG.data_log|date_format:"%d/%m/%Y %H:%M:%S"}</td>
                    </tr>
                </tbody>
            </table> 
        </div>
        {if !$EXC && !$STATUS}
            <div id="divAba1" style="display: block;">
                <table class="formatosLog">
                    <thead>
                        <tr>
                            <td class="width100 paddingRight15 ">Dados atualizados</td>
                        </tr>
                    </thead>
                    <tbody > 
                        {if $DADOS_NOVO}
                            <tr > 
                                <td class="marginBottom10">
                                    {$DADOS_NOVO}
                                </td>
                            </tr>
                        {/if}
                    </tbody>
                </table>
            </div>
            {if !$CAD}
                <div id="divAba2" style="display: none;">

                    <table class="formatosLog">
                        <thead>
                            <tr>
                                <td class="width100 paddingRight15 ">Dados anteriores</td>
                            </tr>
                        </thead>
                        <tbody > 
                            {if $DADOS_ANT}
                                <tr > 
                                    <td class="marginBottom10">
                                        {$DADOS_ANT}
                                    </td>
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            {/if}
        {/if}
    </div>
</div>
<div class="alinhaBtns width">{$BTN_CANCELAR}</div>
