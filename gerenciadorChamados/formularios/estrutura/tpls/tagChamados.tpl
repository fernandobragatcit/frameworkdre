
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
        <strong ><a id="chamados" class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaChamados','abasetor','abaCadastrarSetor','abaabrechamados','abaContatos');mudarAbaCrm('{$PARAMS}','chamados')" >Chamados </a></strong>
    </div>
    <div class="abaSisChamados" id="abaabrechamados">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaabrechamados','abasetor','abaCadastrarSetor','abaContatos','abaChamados');mudarAbaCrm('{$PARAMS}','abrechamados')" >Cadastrar Chamados </a></strong>
    </div>
    <div class="abaSisChamados" id="abaCadastrarSetor">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaCadastrarSetor','abasetor','abaContatos','abaabrechamados','abaChamados');mudarAbaCrm('{$PARAMS}','cadastrarsetor')" >Cadastrar Setor </a></strong>
    </div>
    <div class="abaSisChamados" id="abasetor">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abasetor','abaContatos','abaCadastrarSetor','abaabrechamados','abaChamados');mudarAbaCrm('{$PARAMS}','setor')" >Setor </a></strong>
    </div>
    <div class="abaSisChamados" id="abaContatos">
        <strong ><a class="zurichRegular block"   href="javascript:void(0);" onclick="mudaCorFundo('abaContatos','abasetor','abaCadastrarSetor','abaabrechamados','abaChamados');mudarAbaCrm('{$PARAMS}','contatos')" >Contatos </a></strong>
    </div>

    <div class="conteudoCrmIbs">
        {$CONTEUDO}
    </div>
</div>

<div class="alinhaBtns width">{$BTN_CANCELAR}</div>
{if $ID}
    <script type="text/javascript">
        mudarCor('{$ID}','#B3C3D0');
    </script>
{/if}