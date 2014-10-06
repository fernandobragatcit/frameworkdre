<div class="container">
    <h1 class="noMargin">{$FORM}</h1>
    {$MARCADOR_GRID}   
    <div class="span12 marginLeft0">
        <p><strong>{if $NOME_USUARIO_DIR}Usuário: {/if}{if $NOME_GRUPO}Grupo: {/if}</strong>{if $NOME_USUARIO_DIR}{$NOME_USUARIO_DIR}{/if}{if $NOME_GRUPO}{$NOME_GRUPO}{/if}</p>
        <p><strong>Direito: </strong>{$DIREITO}</p>
        <p>{if $NOME_USUARIO_DIR}O usuário <strong>{$NOME_USUARIO_DIR}</strong> {/if}{if $NOME_GRUPO}O grupo <strong>{$NOME_GRUPO}</strong> {/if} no direto <strong>{$DIREITO}</strong> poderá ter as permissões:</p>
    </div>

    <div class="span12 marginLeft0">
        <form action="{$SALVAR}" name="subpermissoes" method="POST" id="subpermissoes" >
            <table class="table tabelaAcoes">
                <thead class="breadcrumb textCenter">
                    <tr>
                        <td>AÇÃO</td>
                        <td>SELECIONAR</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="icon-plus-sign"></i> Cadastrar</td>
                        <td class="breadcrumb"><input  type="checkbox" name="chcadastrar" {if $CHCAD}checked="true"{/if} id="chcadastrar" value="1"></td>
                    </tr>
                    <tr>
                        <td><i class="icon-edit"></i> Alterar</td>
                        <td class="breadcrumb"><input  type="checkbox" name="chalterar" id="chalterar" {if $CHALT}checked="true"{/if}  value="2"></td>
                    </tr>
                    <tr>
                        <td><i class="icon-remove"></i> Excluir</td>
                        <td class="breadcrumb"><input  type="checkbox" name="chexcluir" id="chexcluir" {if $CHEXC}checked="true"{/if} value="3"></td>
                    </tr>
                </tbody>
            </table>
            <div class="alinhaBtns width">
                <input class="btn" type="submit" value="Salvar"/>
                <a class="btn marginLeft12"   href="{$VOLTAR}" >Voltar Para Etapa 2</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    {if $MSG}
    alert('{$MSG}');
    {/if}
</script>
