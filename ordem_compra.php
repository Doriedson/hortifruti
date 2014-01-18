<?php
include "cn.php";
include "funcoes.php";

?>
<h1>:: Lista de Ordens de Compra em Aberto (<input type='button' onclick="TINY.box.show({url:'nova_oc.php',opacity:20,topsplit:3});" value='Nova OC' />)</h1>

<?php

if( isset($_POST['close_oc']) ) {
	$sql='select * from tab_ordemcompraitem where (vol1=0 or custo=0) and id_oc=' . $_POST['close_oc'];

	if( $rs=$cn->query($sql) ) {
		if( $row=$rs->fetch_assoc() ) {
			echo "<script>TINY.box.show({html:'Há itens na Ordem de Compra com<br />Vol1 ou Custo com valor Zero!'});</script>";
		} else {
			$sql='update tab_ordemcompra set aberto=0 where id_oc=' . $_POST['close_oc'];
			$cn->query($sql);
		}
	}
}

if( isset($_POST['open_oc']) ) {
	$sql='update tab_ordemcompra set aberto=1 where id_oc=' . $_POST['open_oc'];
	$cn->query($sql);
}

$sql="select oc.*, sum(oci.vol1*oci.custo) as vol1, sum(oci.vol2*oci.custo) as vol2, a.usuario from tab_ordemcompra oc inner join tab_acesso a on a.id_entidade=oc.id_entidade left join tab_ordemcompraitem oci on oci.id_oc=oc.id_oc group by oc.id_oc";

if ($rs=$cn->query($sql)) {
	if( $row=$rs->fetch_assoc() ) {
		echo "<table><thead><tr><th>Data</th><th align='left'>Ordem de Compra</th><th>Total 1</th><th>Total 2</th><th>Usuario</th><th>Obs</th><th></th><th></th><th></th></tr></thead>";

		do {
			echo "<tr><td align='center'>" . date_format( date_create($row['data']), 'd/m/Y') . "</td><td>" . $row['descricao'] . "</td><td>R$ " . number_format($row['vol1'],2,',','.') . "</td><td>R$ " . number_format($row['vol2'],2,',','.') . "</td><td align='center'>" . $row['usuario'] . "</td><td align='right'>" . $row['obs'] . "</td><td align='center'></td><td><input type='button' value='Ordem' onclick=\"TINY.box.show({url:'nova_oc.php',opacity:20,topsplit:3, post: 'id=" . $row['id_oc'] . "'})\" />";

			$btItem=(!is_null($row['vol1']))?"<input type='button' value='Fechar OC' onclick='FecharOC(" . $row['id_oc'] . ");' />":"";

			echo ($row['aberto'])?"<input type='button' value='Itens' onclick=\"ItemOC(" . $row['id_oc'] . ");\" />$btItem":"<input type='button' value='Abrir OC' onclick='AbrirOC(" . $row['id_oc'] . ");' /><input type='button' value='Imprimir' onclick='PrintOC(" . $row['id_oc'] . ");' /><input type='button' value='Lançar Entrada' onclick='EntradaEstoque(" . $row['id_oc'] . ");' />";
echo "<input type='button' value='Imprimir' onclick='PrintOC(" . $row['id_oc'] . ");' />";
			echo "</td></tr>";
		} while( $row=$rs->fetch_assoc() );
		
		echo "</table>";

	} else {
		echo "<tr><td colspan='6'>Não há registro de Ordem de Compra em Aberto.</td></tr>";
	}
}

$cn->close();
?>
