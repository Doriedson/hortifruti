<?php
if( !isset($_SESSION) ) {
	session_start();
}
?>

<script>
	$(document).ready(function(){
		$("#lista div:nth-child(1)").show();
		$(".abas li:first div").addClass("selected");      

		fnAba();
	});
</script>

<h1>:: Despesa</h1>

<div class="TabControl">
    <div id="head">
        <ul class="abas">
            <li>
                <div class="aba">
                    <span>Lançamento</span>
                </div>
            </li>
            <li>
                <div class="aba">
                    <span>Consulta / Descrição</span>
                </div>
            </li>
            <li>
                <div class="aba">
                    <span>Consulta / Data</span>
                </div>
            </li>
        </ul>
    </div>
    <div id="lista">
        <div class="conteudo">
        	<form method="post" onsubmit="return cadDespesa(this);">
				<input type='date' id="data" size='10' value='<? echo date('Y-m-d') ?>' required autofocus />
				<? 
				$setor=0;
				include 'select_despesa.php';
				echo $catdespesa;
				?>
				<input type='text' id="desc" size='25' maxlength='100' required placeholder='Descrição' pattern='[A-Z a-z0-9]+' />
				<input type='number' id="valor" style='text-align:center;' placeholder='0,00' size='7' min="0.01" max="9999.99" step="0.01" maxlength='7' pattern='\d+(,\d{0,2})?' required />
				<input type="submit" value='Lançar' />
			</form>
        </div>
        <div class="conteudo">
        	<form method="post" onsubmit="return searchDespesa(this,'desc');">
				<input type="text" id="desc" size="50" maxlength="50" pattern="[a-z A-Z0-9,+_\.]+" autofocus required placeholder="Descrição" />
				<input type="submit" value="Busca" />
			</form>
        </div>
        <div class="conteudo">
        	<form method="post" onsubmit="return searchDespesa(this,'data');">
				<input type='date' id="data" size='10' value='<? echo date('Y-m-d') ?>' required autofocus />
				<input type="submit" id="submit" value="Busca" />
			</form>
        </div>
    </div>
</div>

<div id="despesa">
	<span id='err_despesa' class='erro'></span>
	<table id="tab_despesa">
		<THEAD>
			<tr>
				<th width="140px">Data</th>
				<th width="170px">Setor</th>
				<th width="210px">Descrição</th>
				<th width="80px">Valor</th>
				<th>Ações</th>
			</tr>
		</THEAD>

		<TBODY>
		</TBODY>

		<TFOOT>
			<tr>
				<td colspan='3' align='right'>Total: </td>
				<td align='center'>R$ <span id='total_despesa'>0,00</span></td>
				<td></td>
			</tr>			
		</TFOOT>

	</table>
</div>