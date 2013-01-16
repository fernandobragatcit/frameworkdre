<div class="width956 left  formulario2">
    <h1 class="noMargin">{$FORM}</h1>
    {$MARCADOR_GRID}   
    <div class="marginTop5 marginBottom10 paddingRight15 width930 font14 "><strong>{if $NOME_USUARIO}Usuário:{/if}{if $NOME_GRUPO}Grupo:{/if} </strong>{if $NOME_USUARIO}{$NOME_USUARIO}{/if}{if $NOME_GRUPO}{$NOME_GRUPO}{/if}</div>
    <div class="marginTop5 marginBottom10 paddingRight15 width930 font14  "><strong>Direito: </strong>{$DIREITO}</div>
    <div class="marginTop5 marginBottom10 paddingRight15 width930 background8B9FA7 branco padding10">{if $NOME_USUARIO}O usuário <strong>{$NOME_USUARIO}</strong> {/if}{if $NOME_GRUPO}O grupo <strong>{$NOME_GRUPO}</strong> {/if} no direto <strong>{$DIREITO}</strong> poderá ter as permissões:</div>

    <div class="width956 background8B9FA7 floatNone">
        <form action="{$SALVAR}" name="subpermissoes" method="POST" id="subpermissoes" >
            <table class="tabelaAcoes">
                <tr>
                    <td class="width400 " style="background-color: #386280"><p>AÇÃO</p></td>
                    <td class="width400 " style="background-color: #386280" ><p>SELECIONAR</p></td>
                </tr>
                <tr>
                    <td class="width400 " ><p  >Cadastrar</p></td>
                    <td style="background-color: #BECDDA;"><input  type="checkbox" name="chcadastrar" {if $CHCAD}checked="true"{/if} id="chcadastrar" value="1"></td>
                </tr>
                <tr>
                    <td class="width400 " ><p >Alterar</p></td>
                    <td style="background-color: #BECDDA"><input  type="checkbox" name="chalterar" id="chalterar" {if $CHALT}checked="true"{/if}  value="2"></td>
                </tr>
                <tr>
                    <td class="width400 " ><p>Excluir</p></td>
                    <td style="background-color: #BECDDA"><input  type="checkbox" name="chexcluir" id="chexcluir" {if $CHEXC}checked="true"{/if} value="3"></td>
                </tr>
            </table>
            <div class="alinhaBtns width">
                <input class="button marginLeft12 marginRight10 marginTop50" type="submit" value="Salvar"/>
                <a class="button marginLeft12 marginRight10 marginTop50"   href="{$VOLTAR}" >Voltar Para Etapa 2</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    {if $MSG}
        alert('{$MSG}');
    {/if}
</script>
