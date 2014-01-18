<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";
?>

<h1>:: Lançamento de Despesas</h1>
<?php

$sql="select * from tab_catctspag order by catctspag";

if ( $rs=$cn->query($sql) ) {
	if ($row=$rs->fetch_assoc()) {

		echo "<form method='post' onsubmit='return InsumoOC();' >";
		echo "<input type='date' id='data' size='10' value='" . date('Y-m-d') . "' required autofocus />";
		echo "<select id='cat' autofocus>";
	
		do {
			echo "<option value='" . $row['id_catctspag'] . "'>" . $row['catctspag'] . "</option>";
		} while ($row=$rs->fetch_assoc());
		
		echo "</select><br />";
		echo "<input type='text' id='produto' size='20' maxlength='100' required placeholder='Descrição' pattern='[A-Z a-z0-9]+' />";
		echo "<input type='text' id='valor' style='text-align:center;' placeholder='0,00' size='7' maxlength='7' pattern='\d+(,\d{0,2})?' required />";
		echo " <input type='submit' value='Adicionar' /></form><br />";
	} else {
		echo "<p>Para lançar despesas, cadastre uma categoria em Despesas.";
	}
}

$cn->close();
?>

<div id="reldespesa">
	<table id="tab_despesa" width="300">
		<tr><th>Data</th><th>Descrição</th><th>Setor</th><th>Valor</th></tr>
	</table>
</div>
