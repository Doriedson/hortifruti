<?php
if( !isset($_SESSION) ) {
	session_start();
}

include "cn.php";

	$sql="select * from tab_ordemcompraitem left join tab_produto on tab_produto.id_produto=tab_ordemcompraitem.id_produto left join tab_catctspag on tab_catctspag.id_catctspag=-tab_ordemcompraitem.id_produto where tab_ordemcompraitem.id_oc=" . $_POST['id_oc'];// . " order by tab_produto.produto";

	if ( $rs=$cn->query($sql) ) {
		if ( $row=$rs->fetch_assoc() ) {
			
			echo "<table><thead><tr><th>Produto</th><th>Qtd</th></tr></thead>";
			
			do {
				if( is_null( $row['produto'] ) ) {
					echo "<tr><td>" . $row['obs'] . "</td>";
					echo "<td><input type='text' size='7' maxlength='7' pattern='\d+(,\d{0,3})?' onchange='ChangeOC(" . $row['id_ocitem'] . ",1,this);' style='text-align:center;' placeholder='" . number_format($row['vol1'],3,',','.') . "' /></td>";
				} else {
					echo "<tr><td>" . $row['produto'] . "</td>";
					echo "<td>" . number_format($row['vol1'],3,',','.') . "</td>";
				}

				echo "</tr>";

			} while ($row=$rs->fetch_assoc());
			
			echo "</table>";
		} else {
			echo "Não há itens na Ordem de Compra.";
		}
	}

$cn->close();
?>
