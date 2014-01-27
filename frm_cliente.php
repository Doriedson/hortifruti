<script>
	$(document).ready(function(){
		ListaCli();
	});
</script>

<h1>:: Clientes / Funcionários</h1>

<div id='wCadCli' class='window'></div>

<table id='tab_cliente'>
	<thead>
	<tr>
		<th>Código</th>
		<th>Nome</th>
		<th>Telefone</th>
		<th><input type='button' onclick='CadCli(0);' value='novo'></th>
	</tr>
	</thead>
	<tbody>
		<? echo $str ?>
	</tbody>
</table>	
