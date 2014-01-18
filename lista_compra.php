<?php
include "cn.php";
include "funcoes.php";

?>
<h1>:: Lista de Compra (<input type='button' onclick="TINY.box.show({url:'nova_lc.php',opacity:20,topsplit:3});" value='Nova LC' />)</h1>
<?php

$sql="select * from tab_listacompra";

if ($rs=$cn->query($sql)) {
	if( $row=$rs->fetch_assoc() ) {
		echo "<table><tr><th align='left'>Lista de Compra</th><th></th><th></th></tr>";

		do {
			echo "<tr><td>" . $row['descricao'] . "</td><td align='center'></td><td><input type='button' value='Lista' onclick=\"TINY.box.show({url:'nova_lc.php',opacity:20,topsplit:3, post: 'id=" . $row['id_lc'] . "'})\" /><input type='button' value='Itens' onclick=\"ItemLC(" . $row['id_lc'] . ");\" /></td></tr>";
		} while( $row=$rs->fetch_assoc() );
		
		echo "</table>";

	} else {
		echo "<tr><td colspan='6'>Não há lista(s) de Compra(s) cadastrada.</td></tr>";
	}
}

$cn->close();
?>