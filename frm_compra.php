<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";
include "funcoes.php";

?>
<script>
	$(document).ready(function(){
		$("#lista div:nth-child(1)").show();
		$(".abas li:first div").addClass("selected");      

		listaOC();
		fnAba;
	});
</script>

<h1>:: Ordens de Compra</h1>

<div id='oc_grafico' style="position:fixed; width:40%; right:10px; "></div>

<div class="TabControl">
    <div id="head">
        <ul class="abas">
            <li>
                <div class="aba">
                    <span>Lista de Ordens</span>
                </div>
            </li>
        </ul>
    </div>
    <div id="lista">
        <div class="conteudo">
			<form method="post" onsubmit="return cadOC();">
				<input type="date" id="data" value="<? echo date('Y-m-d'); ?>" required />
				<input type="text" id="descricao" placeholder="Descrição" autofocus required pattern="[A-Z a-z0-9]+" size="20" maxlength="100" />
				<input type="text" id="obs" placeholder="Observação" pattern="[A-Z a-z0-9]+" size="20" maxlength="255" />
				<input type="submit" value="Cadastrar" />
			</form>
			<br />
			<table id='tab_compra'>
				<thead>
					<tr>
						<th>Data</th>
						<th>Ordem de Compra</th>
						<th>Valor</th>
						<th>Usuario</th>
						<th>Obs</th>
						<th></th>
					</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
