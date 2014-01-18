<?php

include "cn.php";

$id_produto=$_POST['id_produto'];

$sql="select * from tab_estent inner join tab_produto on tab_produto.id_produto=tab_estent.id_produto where tab_estent.id_produto=$id_produto order by tab_estent.data desc limit 1";

if ($rs=$cn->query($sql)) {
	if ( $row=$rs->fetch_assoc() ) {
	
		echo "<table>";
		echo "<tr><td colspan=2>" . $row['id_produto'] . " - " . $row['produto'] . "</td></tr>";
		echo "<tr><td>Data:</td><td>" . date_format( date_create($row['data']), 'd/m/Y') . "</td></tr>";
		echo "<tr><td>Custo/Vol:</td><td>R$ " . number_format($row['custo'],2,',','.') . "</td></tr>";
		echo "<tr><td>Entrada:</td><td>" . number_format($row['vol'] * $row['qtdvol'],3,',','.') . " " . $row['tipo'] . "</td></tr>";
		$tipo=$row['tipo'];
		
		$sql="select sum(tab_vendaitem.qtd) as qtd from tab_vendaitem inner join tab_venda on tab_venda.id_venda=tab_vendaitem.id_venda where tab_vendaitem.id_produto=$id_produto and tab_venda.data between '" . date_format( date_create($row['data']), 'Y-m-d H:i:s') . "' and now()";
		if ($rs=$cn->query($sql)) {
			if ( $row=$rs->fetch_assoc() ) {
				echo "<tr><td>Saída:</td><td>" . number_format($row['qtd'],3,',','.') . " $tipo</td></tr>";
			}
		}
		
		echo "</table>";
	}
}

$cn->close();
?>