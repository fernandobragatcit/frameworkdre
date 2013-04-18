
<div class="width900 left  formulario2">
    <h1 class="noMargin">{$TITULO_FORMS}</h1>
    <h2 class="marginTop10 marginBottom10">{$NOME_REFERENCIAL}</h2>
    <p class="listIncritos marginBottom10">{$DATA}</p>
    <form class="marginTop10 marginBottom65" >     
        <table class="formMeiaTela">
            <tr>
                <td > <span class="left">Data Inicial:</span></td>
                <td>&nbsp;</td>
                <td > <span class="left width12">Data Final:</span></td> 
            </tr>
            <tr>
                <td><input type="text" title="Data Inicial" value="{$DATAINI}"  id="dataIni" onkeyup="mascara_data(this.value,'dataIni','dataFim')" maxlength="10" name="dataIni" class="larguraData left"/></td>
                <td >&nbsp;</td>
                <td><input type="text" title="Data Final" value="{$DATAFIM}" id="dataFim" onkeyup="mascara_data(this.value,'dataFim','dataFim')" maxlength="10"  name="dataFim" class="larguraData left "/></td>
            </tr>
        </table>
        <div class="filtroRodizio"> 
            {if $MSG}
                <span class=" left marginLeft12 vermelhoEscuro font14">{$MSG}</span>
            {/if}
            <a class="button marginLeft12 marginRight10 marginTop50" onclick="this.href='{$LINKFILTRO}&data1='+document.getElementById('dataIni').value+'&data2='+document.getElementById('dataFim').value" href="#" >Filtrar</a>
            {$BTN_CANCELAR}   

        </div>

    </form >  <br /> <br />
    <div class="abaSisChamados" id="abaChamados">
        <strong ><a id="chamados" class="zurichRegular block"  href="javascript:void(0);" onclick="mudaCorFundo('abaChamados','abaabrechamados','abasetor','abaCadastrarSetor','abaPrioridade','abaCadastrarPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','chamados')" >Chamados </a></strong>
    </div>
    <div class="abaSisChamados" id="abaabrechamados">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaabrechamados', 'abaChamados','abasetor','abaCadastrarSetor','abaPrioridade','abaCadastrarPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','abrechamados')" >Abrir Chamado </a></strong>
    </div>
    <div class="abaSisChamados" id="abasetor">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abasetor','abaChamados','abaabrechamados','abaCadastrarSetor','abaPrioridade','abaCadastrarPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','setor')" >Setor </a></strong>
    </div>
    <div class="abaSisChamados" id="abaCadastrarSetor">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaCadastrarSetor','abaChamados','abaabrechamados','abasetor','abaPrioridade','abaCadastrarPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','cadastrarsetor')" >Cadastrar Setor </a></strong>
    </div>
    <div class="abaSisChamados" id="abaPrioridade">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaPrioridade','abaChamados','abaabrechamados','abasetor','abaCadastrarSetor','abaCadastrarPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','prioridade')" >Prioridade </a></strong>
    </div>
    <div class="abaSisChamados" id="abaCadastrarPrioridade">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaCadastrarPrioridade','abaChamados','abaabrechamados','abasetor','abaCadastrarSetor','abaPrioridade','abaStatus','abaCadastrarStatus');mudarAba('{$PARAMS}','cadastrarprioridade')" >Cadastrar Prioridade </a></strong>
    </div>
    <div class="abaSisChamados" id="abaStatus">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaStatus','abaCadastrarPrioridade','abaChamados','abaabrechamados','abasetor','abaCadastrarSetor','abaPrioridade','abaCadastrarStatus');mudarAba('{$PARAMS}','status')" >Status </a></strong>
    </div>
    <div class="abaSisChamados" id="abaCadastrarStatus">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaCadastrarStatus','abaStatus','abaCadastrarPrioridade','abaChamados','abaabrechamados','abasetor','abaCadastrarSetor','abaPrioridade');mudarAba('{$PARAMS}','cadastrarstatus')" >Cadastrar Status </a></strong>
    </div>

    <div class="conteudoChamadoIbs">
        {$CONTEUDO}
    </div>
</div>

<div class="alinhaBtns width">{$BTN_CANCELAR}</div>
{if $ID}
    <script type="text/javascript">
        mudarCor('{$ID}','#B3C3D0');
    </script>
{/if}
{if $DEFAULT}
    <script type="text/javascript">
        mudaCorFundo('abaChamados','abasetor','abaCadastrarSetor','abaabrechamados','abaPrioridade');
    </script>
{/if}

{if $ABA_SELECIONADA}
    {literal} 
        <script type="text/javascript">
        var aba='{/literal}{$ABA_SELECIONADA}{literal}';
        mudaCorAba(aba);
        </script>
    {/literal} 
{/if}
